<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class NovelApiService
{
    private string $baseUrl = 'https://api.jikan.moe/v4';
    
    public function searchNovels(string $query = '', int $page = 1)
    {
        $cacheKey = "novels_search_{$query}_{$page}";
        
        return Cache::remember($cacheKey, 3600, function () use ($query, $page) {
            $response = Http::withOptions(['verify' => false])->get("{$this->baseUrl}/manga", [
                'q' => $query,
                'type' => 'lightnovel',
                'page' => $page,
                'limit' => 20
            ]);
            
            if ($response->successful()) {
                return $response->json()['data'];
            }
            
            return [];
        });
    }
    
    public function getNovelDetail(int $apiId)
    {
        $cacheKey = "novel_detail_{$apiId}";
        
        return Cache::remember($cacheKey, 3600, function () use ($apiId) {
            $response = Http::withOptions(['verify' => false])->get("{$this->baseUrl}/manga/{$apiId}");
            
            if ($response->successful()) {
                return $response->json()['data'];
            }
            
            return null;
        });
    }
    

    public function getPopularNovels(int $page = 1)
    {
        $cacheKey = "novels_popular_{$page}";
        
        return Cache::remember($cacheKey, 7200, function () use ($page) {
            // PERUBAHAN ADA DI SINI: Tambahkan withOptions(['verify' => false])
            $response = Http::withOptions(['verify' => false])->get("{$this->baseUrl}/top/manga", [
                'type' => 'lightnovel',
                'page' => $page,
                'limit' => 20
            ]);
            
            if ($response->successful()) {
                return $response->json()['data'];
            }
            
            return [];
        });
    }
}