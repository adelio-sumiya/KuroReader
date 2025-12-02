<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Services\NovelApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Smalot\PdfParser\Parser as PdfParser;

class AdminChapterController extends Controller
{
    public function __construct(
        protected NovelApiService $apiService
    ) {
    }

    protected function ensureAdmin(): void
    {
        if (! Auth::check() || ! Auth::user()->is_admin) {
            abort(403, 'Admins only.');
        }
    }

    /**
     * Manage chapters for a given MAL / Jikan novel (by apiId).
     */
    public function index(int $apiId): View
    {
        $this->ensureAdmin();

        $novel = $this->apiService->getNovelDetail($apiId);
        if (! $novel) {
            abort(404, 'Novel not found from API.');
        }

        $chapters = Chapter::where('novel_api_id', $apiId)
            ->orderBy('chapter_number')
            ->get();

        return view('admin.chapters.index', [
            'novel'    => $novel,
            'apiId'    => $apiId,
            'chapters' => $chapters,
        ]);
    }

    /**
     * Store a new chapter (text or PDF) for the given MAL / Jikan novel.
     */
    public function store(Request $request, int $apiId): RedirectResponse
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'chapter_number' => ['nullable', 'integer', 'min:1'],
            'title'          => ['required', 'string', 'max:255'],
            'content'        => ['nullable', 'string'],
            // Accept both PDF (with images) and EPUB files
            // max is in kilobytes → 51200 = 50MB
            'chapter_pdf'    => ['nullable', 'file', 'mimetypes:application/pdf,application/epub+zip', 'max:51200'],
        ]);

        // Auto-assign chapter_number if not provided
        if (empty($data['chapter_number'])) {
            $maxNumber = Chapter::where('novel_api_id', $apiId)->max('chapter_number');
            $data['chapter_number'] = $maxNumber ? $maxNumber + 1 : 1;
        }

        $contentHtml = $data['content'] ?? null;
        $pdfPath = null;
        $epubPath = null;

        if ($request->hasFile('chapter_pdf')) {
            $file = $request->file('chapter_pdf');
            $extension = strtolower($file->getClientOriginalExtension());

            if ($extension === 'pdf') {
                // Store original PDF (keeps images)
                $pdfPath = $file->store('chapters_pdfs', 'public');

                // Optional: still parse text for searchable / copyable content
                try {
                    $parser = new PdfParser();
                    $pdf = $parser->parseFile($file->getPathname());
                    $text = $pdf->getText();

                    if ($text) {
                        $escaped = e($text);
                        $contentHtml = $contentHtml
                            ? $contentHtml . '<hr>' . nl2br($escaped)
                            : nl2br($escaped);
                    }
                } catch (\Throwable $e) {
                    // If parsing fails, we still keep the PDF for inline viewing.
                }
            } elseif ($extension === 'epub') {
                // Store original EPUB file
                $epubPath = $file->store('chapters_epub', 'public');
            }
        }

        Chapter::updateOrCreate(
            [
                'novel_api_id'   => $apiId,
                'chapter_number' => $data['chapter_number'],
            ],
            [
                'title'      => $data['title'],
                'content'    => $contentHtml,
                'pdf_path'   => $pdfPath,
                'epub_path'  => $epubPath,
                'created_by' => Auth::id(),
            ]
        );

        return redirect()
            ->route('admin.chapters.index', $apiId)
            ->with('status', 'Chapter saved successfully.');
    }
}


