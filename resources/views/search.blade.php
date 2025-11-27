<!DOCTYPE html>
<html>
<head>
    <title>Web Scraper Reader</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h1 class="mb-4">🔍 Laravel Novel Reader</h1>
    
    <form action="/search" method="GET" class="mb-5">
        <div class="input-group">
            <input type="text" name="keyword" class="form-control" placeholder="Cari judul novel..." value="{{ $keyword ?? '' }}">
            <button class="btn btn-primary" type="submit">Cari</button>
        </div>
    </form>

    @if(isset($results))
    <div class="row">
        @foreach($results as $novel)
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <img src="{{ $novel['cover'] }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">{{ $novel['title'] }}</h5>
                    <a href="/novel?url={{ urlencode($novel['url']) }}" class="btn btn-sm btn-dark w-100">Baca Novel</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</body>
</html>