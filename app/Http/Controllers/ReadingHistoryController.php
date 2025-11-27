<?php

namespace App\Http\Controllers;

use App\Models\ReadingHistory;
use Illuminate\Http\Request;

class ReadingHistoryController extends Controller
{
    /**
     * Update reading progress
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'novel_api_id' => 'required|integer',
            'last_chapter_read' => 'required|integer|min:1'
        ]);
        
        ReadingHistory::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'novel_api_id' => $validated['novel_api_id']
            ],
            [
                'last_chapter_read' => $validated['last_chapter_read'],
                'last_read_at' => now()
            ]
        );
        
        return back()->with('success', 'Reading progress saved!');
    }
    
    /**
     * Get reading history
     */
    public function index()
    {
        $histories = auth()->user()->readingHistories()
            ->orderBy('last_read_at', 'desc')
            ->get();
        
        return view('history.index', compact('histories'));
    }
}