<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class NovelApiService
{
    private string $baseUrl = 'https://api.jikan.moe/v4';
    
    /**
     * Search novels by query
     */
    public function searchNovels(string $query = '', int $page = 1)
    {
        $cacheKey = "novels_search_{$query}_{$page}";
        
        return Cache::remember($cacheKey, 3600, function () use ($query, $page) {
            $response = Http::withOptions(['verify' => false])->get("{$this->baseUrl}/manga", [
                'q' => $query,
                'type' => 'lightnovel',
                'page' => $page,
                'limit' => 20,
                'order_by' => 'score',
                'sort' => 'desc'
            ]);
            
            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }
            
            return [];
        });
    }
    
    /**
     * Get novel detail by ID
     */
    public function getNovelDetail(int $apiId)
    {
        $cacheKey = "novel_detail_{$apiId}";
        
        return Cache::remember($cacheKey, 3600, function () use ($apiId) {
            $response = Http::withOptions(['verify' => false])->get("{$this->baseUrl}/manga/{$apiId}");
            
            if ($response->successful()) {
                return $response->json()['data'] ?? null;
            }
            
            return null;
        });
    }
    
    /**
     * Get popular/top novels
     */
    public function getPopularNovels(int $page = 1)
    {
        $cacheKey = "novels_popular_{$page}";
        
        return Cache::remember($cacheKey, 7200, function () use ($page) {
            $response = Http::withOptions(['verify' => false])->get("{$this->baseUrl}/top/manga", [
                'type' => 'lightnovel',
                'page' => $page,
                'limit' => 20
            ]);
            
            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }
            
            return [];
        });
    }
    
    /**
     * Get novels by genre ID
     */
    public function getNovelsByGenre(int $genreId, int $page = 1)
    {
        $cacheKey = "novels_genre_{$genreId}_{$page}";
        
        return Cache::remember($cacheKey, 3600, function () use ($genreId, $page) {
            $response = Http::withOptions(['verify' => false])->get("{$this->baseUrl}/manga", [
                'genres' => $genreId,
                'type' => 'lightnovel',
                'page' => $page,
                'limit' => 20,
                'order_by' => 'score',
                'sort' => 'desc'
            ]);
            
            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }
            
            return [];
        });
    }
    
    /**
     * Get all available genres
     */
    public function getAllGenres()
    {
        $cacheKey = "all_genres";
        
        return Cache::remember($cacheKey, 86400, function () {
            // Get genres from manga endpoint (includes light novel genres)
            $response = Http::withOptions(['verify' => false])->get("{$this->baseUrl}/genres/manga");
            
            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }
            
            return [];
        });
    }
}