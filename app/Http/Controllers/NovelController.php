<?php

namespace App\Http\Controllers;

use App\Services\NovelApiService;
use Illuminate\Http\Request;
use App\Models\Review;

class NovelController extends Controller
{
    protected NovelApiService $apiService;
    
    public function __construct(NovelApiService $apiService)
    {
        $this->apiService = $apiService;
    }
    
    public function index()
    {
        // try {
            $novels = $this->apiService->getPopularNovels();
            return view('novels.index', compact('novels'));
        // } catch (\Exception $e) {
        //     return view('novels.index', [
        //         'novels' => [],
        //         'error' => 'Failed to load novels. Please try again later.'
        //     ]);
        // }
    }
    

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $page = $request->input('page', 1);
        
        try {
            if (empty($query)) {
                $novels = $this->apiService->getPopularNovels($page);
            } else {
                $novels = $this->apiService->searchNovels($query, $page);
            }
            
            return view('novels.search', compact('novels', 'query', 'page'));
        } catch (\Exception $e) {
            return view('novels.search', [
                'novels' => [],
                'query' => $query,
                'page' => $page,
                'error' => 'Search failed. Please try again.'
            ]);
        }
    }
    

    public function show($apiId)
    {
        try {
            $novel = $this->apiService->getNovelDetail($apiId);
            
            if (!$novel) {
                abort(404, 'Novel not found');
            }
            
            // Get user's library status if authenticated
            $libraryStatus = null;
            $userReview = null;
            $readingHistory = null;
            
            if (auth()->check()) {
                $libraryStatus = auth()->user()->libraries()
                    ->where('novel_api_id', $apiId)
                    ->first();
                    
                $userReview = auth()->user()->reviews()
                    ->where('novel_api_id', $apiId)
                    ->first();
                    
                $readingHistory = auth()->user()->readingHistories()
                    ->where('novel_api_id', $apiId)
                    ->first();
            }
            
            // Get all reviews for this novel
            $reviews = Review::where('novel_api_id', $apiId)
                ->with('user')
                ->latest()
                ->get();
            
            // Calculate average rating
            $averageRating = $reviews->avg('rating');
            
            return view('novels.show', compact(
                'novel', 
                'libraryStatus', 
                'userReview',
                'readingHistory',
                'reviews',
                'averageRating'
            ));
        } catch (\Exception $e) {
            abort(500, 'Failed to load novel details');
        }
    }
}
