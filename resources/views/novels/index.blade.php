@extends('layouts.app')

@section('title', 'Home - Light Novel Reader')

@section('content')
<div class="container">
    <h1 style="margin-bottom: 2rem;">🔥 Popular Light Novels</h1>

    @if(isset($error))
        <div class="alert alert-error">{{ $error }}</div>
    @endif

    @if(empty($novels))
        <div class="card">
            <p>No novels found. Please check your internet connection or try again later.</p>
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
                                ⭐ {{ $novel['score'] ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection