<?php

namespace App\Http\Controllers;

use App\Models\UserLibrary;
use App\Services\NovelApiService;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    protected NovelApiService $apiService;
    
    public function __construct(NovelApiService $apiService)
    {
        $this->apiService = $apiService;
    }
    
    /**
     * Display user's library
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        
        $query = auth()->user()->libraries();
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $libraries = $query->latest()->get();
        
        // Enrich with API data
        $enrichedLibraries = $libraries->map(function ($library) {
            try {
                $novel = $this->apiService->getNovelDetail($library->novel_api_id);
                $library->novel_data = $novel;
            } catch (\Exception $e) {
                $library->novel_data = null;
            }
            return $library;
        });
        
        // Count by status
        $counts = [
            'all' => auth()->user()->libraries()->count(),
            'want_to_read' => auth()->user()->libraries()->where('status', 'want_to_read')->count(),
            'reading' => auth()->user()->libraries()->where('status', 'reading')->count(),
            'completed' => auth()->user()->libraries()->where('status', 'completed')->count(),
        ];
        
        return view('library.index', compact('enrichedLibraries', 'status', 'counts'));
    }
    
    /**
     * Add novel to library
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'novel_api_id' => 'required|integer',
            'status' => 'required|in:want_to_read,reading,completed'
        ]);
        
        try {
            $library = UserLibrary::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'novel_api_id' => $validated['novel_api_id']
                ],
                ['status' => $validated['status']]
            );
            
            return back()->with('success', 'Novel added to your library!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to add novel to library.');
        }
    }
    
    /**
     * Update library status
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:want_to_read,reading,completed'
        ]);
        
        try {
            $library = UserLibrary::where('user_id', auth()->id())
                ->findOrFail($id);
            
            $library->update(['status' => $validated['status']]);
            
            return back()->with('success', 'Status updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update status.');
        }
    }
    
    /**
     * Remove from library
     */
    public function destroy($id)
    {
        try {
            $library = UserLibrary::where('user_id', auth()->id())
                ->findOrFail($id);
            
            $library->delete();
            
            return back()->with('success', 'Novel removed from library!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to remove novel.');
        }
    }
}