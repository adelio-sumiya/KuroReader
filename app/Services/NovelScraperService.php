<?php

namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class NovelScraperService
{
    protected $client;

    public function __construct()
    {
        // Setup Guzzle dengan User-Agent palsu
        $this->client = new Client([
            'timeout'  => 30,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
            ]
        ]);
    }

    // 1. Fungsi SEARCH (Mencari novel berdasarkan kata kunci)
    public function search($query)
    {
        // GANTI URL INI dengan target situs novel Anda
        // Contoh logika:
        $targetUrl = 'https://www.novelupdates.com/series-finder/?sf=1&sh=' . urlencode($query);
        
        try {
            $response = $this->client->get($targetUrl);
            $html = $response->getBody()->getContents();
            $crawler = new Crawler($html);

            // Sesuaikan CSS Selector ini dengan situs target!
            // Contoh di bawah asumsi hasil search ada di div.novel-item
            $results = $crawler->filter('.list-by-word-body ul li')->each(function (Crawler $node) {
                try {
                    return [
                        'title' => $node->filter('.title')->text(),
                        'url'   => $node->filter('a')->attr('href'), // Link ke halaman detail
                        'cover' => $node->filter('img')->attr('src'),
                    ];
                } catch (\Exception $e) {
                    return null; // Skip jika ada elemen error
                }
            });

            return array_filter($results); // Hapus hasil kosong
        } catch (\Exception $e) {
            return [];
        }
    }

    // 2. Fungsi GET CHAPTERS (Mengambil daftar chapter dari halaman novel)
    public function getNovelDetails($url)
    {
        $response = $this->client->get($url);
        $html = $response->getBody()->getContents();
        $crawler = new Crawler($html);

        // Ambil Info Novel
        $data = [
            'title' => $crawler->filter('h1.novel-title')->text(), // Sesuaikan selector
            'cover' => $crawler->filter('div.novel-cover img')->attr('src'),
            'desc'  => $crawler->filter('div.description')->text(),
            'chapters' => []
        ];

        // Ambil List Chapter
        $data['chapters'] = $crawler->filter('ul.chapter-list li a')->each(function (Crawler $node) {
            return [
                'title' => $node->filter('.chapter-title')->text(),
                'url'   => $node->attr('href')
            ];
        });

        return $data;
    }

    // 3. Fungsi GET CONTENT (Mengambil isi teks chapter)
    public function getChapterContent($url)
    {
        $response = $this->client->get($url);
        $crawler = new Crawler($response->getBody()->getContents());

        // Ambil elemen teks (misal: div#chapter-content)
        // filter('p') untuk mengambil semua paragraf
        $content = $crawler->filter('#chapter-content p')->each(function (Crawler $node) {
            return "<p>" . $node->text() . "</p>";
        });

        return implode('', $content);
    }
}