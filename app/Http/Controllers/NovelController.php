<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NovelScraperService;
use App\Models\Novel;
use App\Models\Chapter;

class NovelController extends Controller
{
    protected $scraper;

    public function __construct(NovelScraperService $scraper)
    {
        $this->scraper = $scraper;
    }

    // --- TAMBAHKAN KODE INI (YANG HILANG) ---
    public function index()
    {
        // Ini untuk menampilkan view 'search.blade.php' saat pertama buka web
        return view('search');
    }
    // ----------------------------------------

    public function search(Request $request)
    {
        $query = $request->input('keyword');
        $results = $this->scraper->search($query);
        // Pastikan variabel 'keyword' juga dikirim agar tidak error undefined variable
        return view('search', ['results' => $results, 'keyword' => $query]);
    }

    // ... sisa function show() dan read() biarkan seperti sebelumnya
public function show(Request $request)
    {
        $url = $request->input('url');
        
        // Cek apakah novel sudah ada di Database (Cache)
        $novel = Novel::where('source_url', $url)->first();

        if (!$novel) {
            // Jika belum ada, Scrape dari internet
            $data = $this->scraper->getNovelDetails($url);
            
            // Simpan ke Database
            $novel = Novel::create([
                'title' => $data['title'],
                'source_url' => $url,
                'cover_image' => $data['cover'],
                'description' => $data['desc']
            ]);

            // Simpan Chapter (tapi isinya masih kosong dulu agar cepat)
            foreach ($data['chapters'] as $chap) {
                $novel->chapters()->create([
                    'title' => $chap['title'],
                    'chapter_url' => $chap['url']
                ]);
            }
        }

        return view('novel', ['novel' => $novel]);
    }

    public function read($id)
    {
        $chapter = Chapter::findOrFail($id);

        // Jika konten kosong, scrape dulu
        if (empty($chapter->content)) {
            $content = $this->scraper->getChapterContent($chapter->chapter_url);
            $chapter->update(['content' => $content]);
        }

        return view('read', ['chapter' => $chapter]);
    }
}