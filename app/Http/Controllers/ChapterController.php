<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\ReadingHistory;
use App\Services\NovelApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ChapterController extends Controller
{
    public function __construct(
        protected NovelApiService $apiService
    ) {
    }

    public function show(Chapter $chapter): View
    {
        $novel = $this->apiService->getNovelDetail($chapter->novel_api_id);

        if (Auth::check()) {
            ReadingHistory::updateOrCreate(
                [
                    'user_id'      => Auth::id(),
                    'novel_api_id' => $chapter->novel_api_id,
                ],
                [
                    'last_chapter_read' => $chapter->chapter_number,
                    'last_read_at'      => now(),
                ]
            );
        }

        $previousChapter = Chapter::where('novel_api_id', $chapter->novel_api_id)
            ->where('chapter_number', '<', $chapter->chapter_number)
            ->orderByDesc('chapter_number')
            ->first();

        $nextChapter = Chapter::where('novel_api_id', $chapter->novel_api_id)
            ->where('chapter_number', '>', $chapter->chapter_number)
            ->orderBy('chapter_number')
            ->first();

        return view('chapters.show', [
            'novel'          => $novel,
            'chapter'        => $chapter,
            'previousChapter'=> $previousChapter,
            'nextChapter'    => $nextChapter,
        ]);
    }

    public function next(Chapter $chapter): RedirectResponse
    {
        $next = Chapter::where('novel_api_id', $chapter->novel_api_id)
            ->where('chapter_number', '>', $chapter->chapter_number)
            ->orderBy('chapter_number')
            ->first();

        if (! $next) {
            return redirect()->route('chapters.show', $chapter);
        }

        return redirect()->route('chapters.show', $next);
    }

    public function previous(Chapter $chapter): RedirectResponse
    {
        $prev = Chapter::where('novel_api_id', $chapter->novel_api_id)
            ->where('chapter_number', '<', $chapter->chapter_number)
            ->orderByDesc('chapter_number')
            ->first();

        if (! $prev) {
            return redirect()->route('chapters.show', $chapter);
        }

        return redirect()->route('chapters.show', $prev);
    }
}


