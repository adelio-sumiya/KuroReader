<?php

namespace App\Http\Controllers;

use App\Services\NovelApiService;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Chapter;

class NovelController extends Controller
{
    protected NovelApiService $apiService;
    
    public function __construct(NovelApiService $apiService)
    {
        $this->apiService = $apiService;
    }
    
    public function index()
    {
        try {
            // Fetch all popular novels
            $allNovels = $this->apiService->getPopularNovels();
            
            // Get genres for filter chips
            $allGenres = $this->apiService->getAllGenres();
            
            // First 5 novels for hero carousel
            $heroNovels = array_slice($allNovels, 0, 5);
            
            // Sort novels by different criteria
            $sortedByScore = $allNovels;
            usort($sortedByScore, function ($a, $b) {
                return ($b['score'] ?? 0) <=> ($a['score'] ?? 0);
            });
            
            $sortedByFavorites = $allNovels;
            usort($sortedByFavorites, function ($a, $b) {
                return ($b['favorites'] ?? 0) <=> ($a['favorites'] ?? 0);
            });
            
            $sortedByMembers = $allNovels;
            usort($sortedByMembers, function ($a, $b) {
                return ($b['members'] ?? 0) <=> ($a['members'] ?? 0);
            });
            
            // Sidebar data (first item from each sorted list)
            $topRated = array_slice($sortedByScore, 0, 1);
            $mostFavorited = array_slice($sortedByFavorites, 0, 1);
            $mostActive = array_slice($sortedByMembers, 0, 1);
            
            // Rankings (top 10 of each)
            $powerRanking = array_slice($sortedByScore, 0, 10);
            $collectionRanking = array_slice($sortedByFavorites, 0, 10);
            $activeRanking = array_slice($sortedByMembers, 0, 10);
            
            // Weekly Featured (next 5 after hero)
            $weeklyFeatured = array_slice($allNovels, 5, 5);
            
            // New Releases (next 12 novels)
            $newReleases = array_slice($allNovels, 10, 12);
            
            // Trending (most members, next 12)
            $trending = array_slice($sortedByMembers, 0, 12);
            
            return view('novels.index', [
                'heroNovels' => $heroNovels,
                'novels' => $allNovels,
                'allGenres' => $allGenres,
                'topRated' => $topRated,
                'mostFavorited' => $mostFavorited,
                'mostActive' => $mostActive,
                'weeklyFeatured' => $weeklyFeatured,
                'powerRanking' => $powerRanking,
                'collectionRanking' => $collectionRanking,
                'activeRanking' => $activeRanking,
                'newReleases' => $newReleases,
                'trending' => $trending,
            ]);
        } catch (\Exception $e) {
            return view('novels.index', [
                'heroNovels' => [],
                'novels' => [],
                'allGenres' => [],
                'topRated' => [],
                'mostFavorited' => [],
                'mostActive' => [],
                'weeklyFeatured' => [],
                'powerRanking' => [],
                'collectionRanking' => [],
                'activeRanking' => [],
                'newReleases' => [],
                'trending' => [],
                'error' => 'Failed to load novels. Please try again later.'
            ]);
        }
    }
    

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $page = $request->input('page', 1);
        $selectedGenreId = $request->input('genre');
        $selectedGenre = $request->input('genre_name');
        
        try {
            // Get all genres for filter chips
            $allGenres = $this->apiService->getAllGenres();
            
            // Fetch novels based on search criteria
            if (!empty($selectedGenreId)) {
                // Filter by genre
                $novels = $this->apiService->getNovelsByGenre((int) $selectedGenreId, $page);
            } elseif (empty($query)) {
                // Show popular novels
                $novels = $this->apiService->getPopularNovels($page);
            } else {
                // Search by query
                $novels = $this->apiService->searchNovels($query, $page);
            }
            
            return view('novels.search', [
                'novels' => $novels,
                'query' => $query,
                'page' => $page,
                'allGenres' => $allGenres,
                'selectedGenreId' => $selectedGenreId,
                'selectedGenre' => $selectedGenre,
            ]);
        } catch (\Exception $e) {
            return view('novels.search', [
                'novels' => [],
                'query' => $query,
                'page' => $page,
                'allGenres' => [],
                'selectedGenreId' => null,
                'selectedGenre' => null,
                'error' => 'Search failed. Please try again.'
            ]);
        }
    }
    

    public function show($apiId)
    {
        try {
            $apiId = (int) $apiId;
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

            // Uploaded chapters (admin-managed) for this novel
            $chapters = Chapter::where('novel_api_id', $apiId)
                ->orderBy('chapter_number')
                ->get();
            
            return view('novels.show', compact(
                'novel', 
                'libraryStatus', 
                'userReview',
                'readingHistory',
                'reviews',
                'averageRating',
                'chapters'
            ));
        } catch (\Exception $e) {
            abort(500, 'Failed to load novel details');
        }
    }
}
