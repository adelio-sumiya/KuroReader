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
use ZipArchive;

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
     * Show edit form for a specific chapter.
     */
    public function edit(int $apiId, Chapter $chapter): View
    {
        $this->ensureAdmin();

        if ($chapter->novel_api_id !== $apiId) {
            abort(404, 'Chapter not found for this novel.');
        }

        $novel = $this->apiService->getNovelDetail($apiId);
        if (! $novel) {
            abort(404, 'Novel not found from API.');
        }

        $chapters = Chapter::where('novel_api_id', $apiId)
            ->orderBy('chapter_number')
            ->get();

        return view('admin.chapters.index', [
            'novel'         => $novel,
            'apiId'         => $apiId,
            'chapters'      => $chapters,
            'editingChapter' => $chapter,
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

        // Check if chapter already exists to preserve existing data
        $existingChapter = Chapter::where('novel_api_id', $apiId)
            ->where('chapter_number', $data['chapter_number'])
            ->first();

        // Preserve existing content if not provided
        $contentHtml = $data['content'] ?? ($existingChapter->content ?? null);
        $pdfPath = $existingChapter->pdf_path ?? null;
        $epubPath = $existingChapter->epub_path ?? null;

        if ($request->hasFile('chapter_pdf')) {
            $file = $request->file('chapter_pdf');
            $extension = strtolower($file->getClientOriginalExtension());

            if ($extension === 'pdf') {
                // Delete old PDF if exists
                if ($existingChapter && $existingChapter->pdf_path) {
                    Storage::disk('public')->delete($existingChapter->pdf_path);
                }

                // Store original PDF
                $pdfPath = $file->store('chapters_pdfs', 'public');

                // Extract text and images from PDF and convert to HTML
                try {
                    $parser = new PdfParser();
                    $pdf = $parser->parseFile($file->getPathname());
                    
                    $htmlParts = [];
                    
                    // Extract text from all pages
                    $pages = $pdf->getPages();
                    foreach ($pages as $pageIndex => $page) {
                        try {
                            $pageText = $page->getText();
                            // Clean and check if page has meaningful content
                            $pageText = trim($pageText);
                            
                            // Skip empty pages or pages with only whitespace/special chars
                            if (empty($pageText) || strlen($pageText) < 10 || preg_match('/^[\s\W]+$/', $pageText)) {
                                continue;
                            }
                            
                            // Extract images from this page
                            $pageImages = [];
                            try {
                                $details = $page->getDetails();
                                
                                if (isset($details['XObject']) && is_array($details['XObject'])) {
                                    foreach ($details['XObject'] as $xObjectKey => $xObject) {
                                        try {
                                            if (is_array($xObject) && isset($xObject['Subtype']) && $xObject['Subtype'] === 'Image') {
                                                $imageObj = $page->get('XObject')[$xObjectKey] ?? null;
                                                if ($imageObj) {
                                                    try {
                                                        $imageStream = $imageObj->get('stream');
                                                        if ($imageStream) {
                                                            $imageContent = $imageStream->getContent();
                                                            if ($imageContent && strlen($imageContent) > 100) {
                                                                // Determine image format
                                                                $imageFormat = 'png';
                                                                if (isset($xObject['Filter'])) {
                                                                    $filter = is_array($xObject['Filter']) ? $xObject['Filter'][0] : $xObject['Filter'];
                                                                    if (stripos($filter, 'DCT') !== false || stripos($filter, 'JPX') !== false || stripos($filter, 'JPEG') !== false) {
                                                                        $imageFormat = 'jpeg';
                                                                    }
                                                                }
                                                                
                                                                $base64Image = base64_encode($imageContent);
                                                                $dataUri = "data:image/{$imageFormat};base64,{$base64Image}";
                                                                $pageImages[] = '<div class="pdf-image" style="margin: 1.5rem 0; text-align: center;"><img src="' . e($dataUri) . '" alt="Page ' . ($pageIndex + 1) . '" style="max-width: 100%; height: auto; display: block; margin: 0 auto;" /></div>';
                                                            }
                                                        }
                                                    } catch (\Throwable $imgError) {
                                                        continue;
                                                    }
                                                }
                                            }
                                        } catch (\Throwable $xObjError) {
                                            continue;
                                        }
                                    }
                                }
                            } catch (\Throwable $imgExtractError) {
                                // Continue without images
                            }
                            
                            // Combine text and images for this page
                            $pageHtml = '';
                            if (!empty($pageText)) {
                                $pageHtml .= '<div class="pdf-text" style="text-align: center; margin-bottom: 1.5rem;">' . nl2br(e($pageText)) . '</div>';
                            }
                            if (!empty($pageImages)) {
                                $pageHtml .= implode('', $pageImages);
                            }
                            
                            if (!empty($pageHtml)) {
                                $htmlParts[] = '<div class="pdf-page" style="margin-bottom: 2rem;">' . $pageHtml . '</div>';
                            }
                        } catch (\Throwable $pageError) {
                            continue;
                        }
                    }
                    
                    // If no pages extracted, try overall text extraction
                    if (empty($htmlParts)) {
                        $text = $pdf->getText();
                        $text = trim($text);
                        if (!empty($text) && strlen($text) > 10) {
                            $htmlParts[] = '<div class="pdf-text" style="text-align: center;">' . nl2br(e($text)) . '</div>';
                        }
                    }
                    
                    if (!empty($htmlParts)) {
                        $pdfHtml = implode('', $htmlParts);
                        $contentHtml = $contentHtml
                            ? $contentHtml . '<hr>' . $pdfHtml
                            : $pdfHtml;
                    }
                } catch (\Throwable $e) {
                    // If parsing fails, try basic text extraction
                    try {
                        $parser = new PdfParser();
                        $pdf = $parser->parseFile($file->getPathname());
                        $text = $pdf->getText();
                        $text = trim($text);
                        if (!empty($text) && strlen($text) > 10) {
                            $contentHtml = $contentHtml
                                ? $contentHtml . '<hr><div class="pdf-text" style="text-align: center;">' . nl2br(e($text)) . '</div>'
                                : '<div class="pdf-text" style="text-align: center;">' . nl2br(e($text)) . '</div>';
                        }
                    } catch (\Throwable $e2) {
                        // If all parsing fails, leave content as is
                    }
                }
            } elseif ($extension === 'epub') {
                // Delete old EPUB if exists
                if ($existingChapter && $existingChapter->epub_path) {
                    Storage::disk('public')->delete($existingChapter->epub_path);
                }

                // Store original EPUB file
                $epubPath = $file->store('chapters_epub', 'public');

                // Convert EPUB to HTML
                try {
                    $epubHtml = $this->convertEpubToHtml($file->getPathname());
                    if ($epubHtml) {
                        $contentHtml = $contentHtml
                            ? $contentHtml . '<hr>' . $epubHtml
                            : $epubHtml;
                    }
                } catch (\Throwable $e) {
                    // If conversion fails, leave content as is
                }
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

    /**
     * Delete a chapter.
     */
    public function destroy(int $apiId, Chapter $chapter): RedirectResponse
    {
        $this->ensureAdmin();

        if ($chapter->novel_api_id !== $apiId) {
            abort(404, 'Chapter not found for this novel.');
        }

        // Delete associated files
        if ($chapter->pdf_path) {
            Storage::disk('public')->delete($chapter->pdf_path);
        }
        if ($chapter->epub_path) {
            Storage::disk('public')->delete($chapter->epub_path);
        }

        $chapter->delete();

        return redirect()
            ->route('admin.chapters.index', $apiId)
            ->with('status', 'Chapter deleted successfully.');
    }

    /**
     * Convert EPUB file to HTML.
     */
    protected function convertEpubToHtml(string $epubPath): ?string
    {
        $zip = new ZipArchive();
        
        if ($zip->open($epubPath) !== true) {
            return null;
        }

        $htmlParts = [];
        
        try {
            // Read container.xml to find content.opf
            $containerXml = $zip->getFromName('META-INF/container.xml');
            if (!$containerXml) {
                $zip->close();
                return null;
            }
            
            // Extract content.opf path
            if (!preg_match('/full-path=["\']([^"\']+)["\']/', $containerXml, $matches)) {
                $zip->close();
                return null;
            }
            
            $contentOpfPath = $matches[1];
            $contentOpf = $zip->getFromName($contentOpfPath);
            if (!$contentOpf) {
                $zip->close();
                return null;
            }
            
            // Get directory of content.opf
            $opfDir = dirname($contentOpfPath);
            if ($opfDir === '.' || $opfDir === '') {
                $opfDir = '';
            } else {
                $opfDir .= '/';
            }
            
            // Extract manifest items (HTML/XHTML files) - try multiple patterns
            $manifestItems = [];
            if (preg_match_all('/<item[^>]+href=["\']([^"\']+)["\'][^>]*media-type=["\'](?:application\/xhtml\+xml|text\/html|application\/html)["\']/i', $contentOpf, $manifestMatches)) {
                $manifestItems = $manifestMatches[1];
            } elseif (preg_match_all('/<item[^>]*media-type=["\'](?:application\/xhtml\+xml|text\/html|application\/html)["\'][^>]+href=["\']([^"\']+)["\']/i', $contentOpf, $manifestMatches)) {
                $manifestItems = $manifestMatches[1];
            }
            
            if (empty($manifestItems)) {
                $zip->close();
                return null;
            }
            
            // Extract and combine HTML content
            foreach ($manifestItems as $htmlFile) {
                // Decode URL encoding if present
                $htmlFile = urldecode($htmlFile);
                
                // Handle relative paths
                $fullPath = $htmlFile;
                if (!str_starts_with($htmlFile, '/') && $opfDir) {
                    $fullPath = $opfDir . $htmlFile;
                }
                
                // Normalize path separators
                $fullPath = str_replace(['\\', '../'], ['/', ''], $fullPath);
                
                $htmlContent = $zip->getFromName($fullPath);
                
                if ($htmlContent) {
                    // Clean up HTML - extract body content if present
                    if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $htmlContent, $bodyMatches)) {
                        $htmlContent = $bodyMatches[1];
                    }
                    
                    // Remove script and style tags
                    $htmlContent = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $htmlContent);
                    $htmlContent = preg_replace('/<style[^>]*>.*?<\/style>/is', '', $htmlContent);
                    
                    // Remove empty tags and clean up whitespace
                    $htmlContent = preg_replace('/<p[^>]*>\s*<\/p>/i', '', $htmlContent);
                    $htmlContent = preg_replace('/<div[^>]*>\s*<\/div>/i', '', $htmlContent);
                    $htmlContent = preg_replace('/\s+/', ' ', $htmlContent);
                    $htmlContent = trim($htmlContent);
                    
                    // Skip empty content
                    if (empty($htmlContent) || strlen(strip_tags($htmlContent)) < 10) {
                        continue;
                    }
                    
                    // Fix image paths - convert relative paths to data URIs
                    $htmlContent = preg_replace_callback('/<img[^>]+src=["\']([^"\']+)["\']/i', function ($matches) use ($zip, $opfDir, $fullPath) {
                        $imgPath = $matches[1];
                        
                        // Skip data URIs and absolute URLs
                        if (str_starts_with($imgPath, 'data:') || preg_match('/^https?:\/\//', $imgPath)) {
                            return $matches[0];
                        }
                        
                        // Get directory of current HTML file
                        $htmlDir = dirname($fullPath);
                        if ($htmlDir === '.' || $htmlDir === '') {
                            $htmlDir = $opfDir;
                        } else {
                            $htmlDir .= '/';
                        }
                        
                        // Build full image path
                        $fullImgPath = $htmlDir . $imgPath;
                        $fullImgPath = str_replace(['\\', '../'], ['/', ''], $fullImgPath);
                        
                        $imgData = $zip->getFromName($fullImgPath);
                        if ($imgData && strlen($imgData) > 0) {
                            $imageInfo = @getimagesizefromstring($imgData);
                            $mimeType = $imageInfo ? $imageInfo['mime'] : 'image/png';
                            $base64 = base64_encode($imgData);
                            return str_replace($matches[1], "data:{$mimeType};base64,{$base64}", $matches[0]);
                        }
                        
                        return $matches[0];
                    }, $htmlContent);
                    
                    $htmlParts[] = '<div class="epub-content" style="margin-bottom: 2rem; text-align: center;">' . $htmlContent . '</div>';
                }
            }
        } catch (\Throwable $e) {
            // If conversion fails, return null
        }
        
        $zip->close();
        
        // Clean up and return HTML, removing empty parts
        $finalHtml = !empty($htmlParts) ? implode('', $htmlParts) : null;
        
        // Remove redundant empty divs and clean up
        if ($finalHtml) {
            $finalHtml = preg_replace('/<div[^>]*>\s*<\/div>/i', '', $finalHtml);
            $finalHtml = preg_replace('/\s+/', ' ', $finalHtml);
            $finalHtml = trim($finalHtml);
            
            // If final HTML is too short, return null
            if (strlen(strip_tags($finalHtml)) < 10) {
                return null;
            }
        }
        
        return $finalHtml;
    }
}


