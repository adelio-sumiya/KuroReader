@extends('layouts.app')

@section('content')

<style>
/* ===== GLOBAL ===== */
body {
    background: #0d0d0f;
    color: white;
    font-family: Arial, sans-serif;
}

.home-wrapper {
    max-width: 1400px;
    margin: auto;
    padding: 20px;
}

/* ===== HERO BANNER ala Netflix ===== */
.hero-banner {
    position: relative;
    height: 420px;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 50px;
}

.hero-banner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(65%);
}

/* gradient overlay */
.hero-banner::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(to right, rgba(0,0,0,0.8), transparent);
}

/* text */
.banner-content {
    position: absolute;
    top: 50%;
    left: 50px;
    transform: translateY(-50%);
    max-width: 450px;
}

.banner-title {
    font-size: 42px;
    font-weight: 800;
    margin-bottom: 15px;
}

.banner-desc {
    font-size: 15px;
    color: #eee;
    margin-bottom: 20px;
}

.banner-btn {
    padding: 12px 26px;
    font-size: 15px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-weight: bold;
    margin-right: 10px;
    transition: 0.2s;
}

.banner-btn.read {
    background: white;
    color: black;
}

.banner-btn.add {
    background: rgba(255,255,255,0.25);
    color: white;
}

/* ===== CAROUSEL HORIZONTAL ===== */
.section-title {
    font-size: 22px;
    font-weight: bold;
    margin: 20px 0 10px 8px;
}

.carousel {
    display: flex;
    overflow-x: auto;
    gap: 15px;
    padding-bottom: 25px;
    scroll-snap-type: x mandatory;
}

.carousel::-webkit-scrollbar {
    display: none;
}

/* CARD */
.novel-card {
    flex: 0 0 160px;
    scroll-snap-align: start;
    border-radius: 10px;
    overflow: hidden;
    background: #1a1a1c;
    transition: 0.2s;
    cursor: pointer;
}

.novel-card:hover {
    transform: scale(1.1);
}

.novel-card img {
    width: 100%;
    height: 220px;
    object-fit: cover;
}

.novel-card-title {
    padding: 8px;
    font-size: 14px;
    font-weight: bold;
}

/* ===== CATEGORY STRIP ala Netflix ===== */
.category-strip {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
}

.category-chip {
    background: #1c1c20;
    padding: 8px 18px;
    border-radius: 20px;
    font-size: 14px;
    border: 1px solid rgba(255,255,255,0.1);
    cursor: pointer;
    transition: 0.2s;
}

.category-chip:hover {
    background: #343439;
}
</style>


<div class="home-wrapper">

    {{-- HERO BANNER --}}
    <div class="hero-banner">
        <img src="https://i.ibb.co/SNhwPvf/ta.jpg">
        
        <div class="banner-content">
            <div class="banner-title">Timeless Assassin</div>
            <div class="banner-desc">
                A mysterious world. A lost memory. A deadly trial.  
                Follow Leo in a bloody journey to uncover himself.
            </div>

            <button class="banner-btn read">Read Now</button>
            <button class="banner-btn add">+ Add to Library</button>
        </div>
    </div>


    {{-- CATEGORY STRIP (seperti Netflix genre row) --}}
    <div class="category-strip">
        <div class="category-chip">Action</div>
        <div class="category-chip">Fantasy</div>
        <div class="category-chip">Romance</div>
        <div class="category-chip">Adventure</div>
        <div class="category-chip">System</div>
        <div class="category-chip">Reincarnation</div>
    </div>


    {{-- WEEKLY FEATURED --}}
    <h2 class="section-title">Trending This Week</h2>

    <div class="carousel">
        @foreach($weeklyFeatured as $novel)
        <div class="novel-card">
            <img src="{{ $novel->cover_url }}">
            <div class="novel-card-title">{{ $novel->title }}</div>
        </div>
        @endforeach
    </div>


    {{-- NEW RELEASES --}}
    <h2 class="section-title">New Releases</h2>

    <div class="carousel">
        @foreach($newReleases as $novel)
        <div class="novel-card">
            <img src="{{ $novel->cover_url }}">
            <div class="novel-card-title">{{ $novel->title }}</div>
        </div>
        @endforeach
    </div>


    {{-- TOP RANKING --}}
    <h2 class="section-title">Top Ranking</h2>

    <div class="carousel">
        @foreach($topRanking as $novel)
        <div class="novel-card">
            <img src="{{ $novel->cover_url }}">
            <div class="novel-card-title">{{ $novel->title }}</div>
        </div>
        @endforeach
    </div>

</div>

@endsection
