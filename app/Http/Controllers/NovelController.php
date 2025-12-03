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
            // Get popular novels untuk berbagai section
            $allNovels = $this->apiService->getPopularNovels(1);
            
            // Hero Carousel - Top 5 novels
            $heroNovels = array_slice($allNovels, 0, 5);
            
            // Side Panel - Top 3 by different metrics
            $topRated = collect($allNovels)->sortByDesc('score')->take(3)->values()->all();
            $mostFavorited = collect($allNovels)->sortByDesc('favorites')->take(3)->values()->all();
            $mostActive = collect($allNovels)->sortByDesc('members')->take(3)->values()->all();
            
            // Weekly Featured - novels 6-15
            $weeklyFeatured = array_slice($allNovels, 5, 10);
            
            // Rankings
            $powerRanking = collect($allNovels)->sortByDesc('score')->take(5)->values()->all();
            $collectionRanking = collect($allNovels)->sortByDesc('favorites')->take(5)->values()->all();
            $activeRanking = collect($allNovels)->sortByDesc('members')->take(5)->values()->all();
            
            // New Releases - sort by published date
            $newReleases = collect($allNovels)
                ->sortByDesc(function($novel) {
                    return $novel['published']['from'] ?? '';
                })
                ->take(6)
                ->values()
                ->all();
            
            // Trending - same as most active
            $trending = collect($allNovels)->sortByDesc('members')->take(8)->values()->all();
            
            // Extract genres from all novels
            $allGenres = collect($allNovels)
                ->pluck('genres')
                ->flatten(1)
                ->unique('mal_id')
                ->take(15)
                ->values()
                ->all();
            
            return view('novels.index', compact(
                'heroNovels',
                'topRated',
                'mostFavorited',
                'mostActive',
                'weeklyFeatured',
                'powerRanking',
                'collectionRanking',
                'activeRanking',
                'newReleases',
                'trending',
                'allGenres'
            ));
        } catch (\Exception $e) {
            return view('novels.index', [
                'heroNovels' => [],
                'topRated' => [],
                'mostFavorited' => [],
                'mostActive' => [],
                'weeklyFeatured' => [],
                'powerRanking' => [],
                'collectionRanking' => [],
                'activeRanking' => [],
                'newReleases' => [],
                'trending' => [],
                'allGenres' => [],
                'error' => 'Failed to load novels. Please try again later.'
            ]);
        }
    }
    
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $page = $request->input('page', 1);
        $genreId = $request->input('genre');
        $genreName = $request->input('genre_name', '');
        
        try {
            // Get novels based on search or genre
            if (!empty($query)) {
                $novels = $this->apiService->searchNovels($query, $page);
            } elseif (!empty($genreId)) {
                $novels = $this->apiService->getNovelsByGenre($genreId, $page);
            } else {
                $novels = $this->apiService->getPopularNovels($page);
            }
            
            // Filter by genre if genre is selected and we have novels
            if (!empty($genreId) && !empty($novels)) {
                $novels = array_filter($novels, function($novel) use ($genreId) {
                    if (!isset($novel['genres'])) {
                        return false;
                    }
                    foreach ($novel['genres'] as $genre) {
                        if ($genre['mal_id'] == $genreId) {
                            return true;
                        }
                    }
                    return false;
                });
                $novels = array_values($novels); // Re-index array
            }
            
            // Get all unique genres from results for filter chips
            $allGenres = collect($novels)
                ->pluck('genres')
                ->flatten(1)
                ->unique('mal_id')
                ->sortBy('name')
                ->values()
                ->all();
            
            // If no genres found, get from popular novels
            if (empty($allGenres)) {
                $popularNovels = $this->apiService->getPopularNovels(1);
                $allGenres = collect($popularNovels)
                    ->pluck('genres')
                    ->flatten(1)
                    ->unique('mal_id')
                    ->sortBy('name')
                    ->take(20)
                    ->values()
                    ->all();
            }
            
            return view('novels.search', [
                'novels' => $novels,
                'query' => $query,
                'page' => $page,
                'allGenres' => $allGenres,
                'selectedGenreId' => $genreId,
                'selectedGenre' => $genreName
            ]);
        } catch (\Exception $e) {
            // Get genres even on error
            $popularNovels = $this->apiService->getPopularNovels(1);
            $allGenres = collect($popularNovels)
                ->pluck('genres')
                ->flatten(1)
                ->unique('mal_id')
                ->sortBy('name')
                ->take(20)
                ->values()
                ->all();
                
            return view('novels.search', [
                'novels' => [],
                'query' => $query,
                'page' => $page,
                'allGenres' => $allGenres,
                'selectedGenreId' => $genreId,
                'selectedGenre' => $genreName,
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
