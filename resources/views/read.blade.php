<body class="container py-5" style="max-width: 800px;">
    <div class="d-flex justify-content-between mb-4">
        <a href="/novel?url={{ urlencode($chapter->novel->source_url) }}" class="btn btn-outline-secondary">Daftar Chapter</a>
    </div>

    <h2 class="mb-4 text-center">{{ $chapter->title }}</h2>
    
    <div class="reader-content" style="font-size: 1.2rem; line-height: 1.8;">
        {!! $chapter->content !!}
    </div>

    <div class="mt-5 text-center">
        <button class="btn btn-primary">Chapter Selanjutnya (Belum dibuat)</button>
    </div>
</body>