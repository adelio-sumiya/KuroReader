<body class="container py-5">
    <a href="/" class="btn btn-outline-secondary mb-3">&larr; Kembali</a>
    
    <div class="row">
        <div class="col-md-3">
            <img src="{{ $novel->cover_image }}" class="img-fluid rounded shadow">
        </div>
        <div class="col-md-9">
            <h2>{{ $novel->title }}</h2>
            <p class="text-muted">{{ $novel->description }}</p>
            <hr>
            <h4>Daftar Chapter</h4>
            <div class="list-group">
                @foreach($novel->chapters as $chapter)
                <a href="/read/{{ $chapter->id }}" class="list-group-item list-group-item-action">
                    {{ $chapter->title }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
</body>