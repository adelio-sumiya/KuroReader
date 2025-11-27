@extends('layouts.app')

@section('title', 'Search Novels')

@section('content')
<div class="container">
    <h1 style="margin-bottom: 1rem; color;">🔍 Search Light Novels</h1>

    <!-- Search Form -->
    <div class="card">
        <form action="/novels" method="GET">
            <div style="display: flex; gap: 1rem;">
                <input type="text" 
                       name="q" 
                       placeholder="Search novels..." 
                       value="{{ $query ?? '' }}"
                       style="flex: 1;">
                <button type="submit" class="btn">Search</button>
            </div>
        </form>
    </div>

    @if(isset($error))
        <div class="alert alert-error">{{ $error }}</div>
    @endif

    <!-- Results -->
    @if(!empty($query))
        <h2 style="margin: 2rem 0 1rem;">Results for "{{ $query }}"</h2>
    @endif

    @if(empty($novels))
        <div class="card">
            <p>No novels found. Try a different search term.</p>
        </div>
    @else
        <div class="grid">
            @foreach($novels as $novel)
                <a href="/novels/{{ $novel['mal_id'] }}" style="text-decoration: none; color: inherit;">
                    <div class="novel-card">
                        <img src="{{ $novel['images']['jpg']['large_image_url'] ?? 'https://via.placeholder.com/200x280' }}" 
                             alt="{{ $novel['title'] }}">
                        <div class="novel-card-body">
                            <h3>{{ $novel['title'] }}</h3>
                            <p style="font-size: 0.9rem; color: #666;">
                                ⭐ {{ $novel['score'] ?? 'N/A' }} | 
                                📚 {{ $novel['chapters'] ?? '?' }} chapters
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
