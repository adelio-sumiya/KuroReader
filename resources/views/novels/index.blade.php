@extends('layouts.app')

@section('title', 'KuroReader - Discover Amazing Light Novels')

@section('content')
<div class="homepage">
    <!-- ===== HERO SECTION WITH CAROUSEL & SIDE PANEL ===== -->
    <div class="hero-wrapper">
        <div class="hero-main">
            <!-- Main Carousel -->
            <div class="hero-carousel" id="heroCarousel">
                @foreach($heroNovels as $index => $novel)
                <div class="hero-slide {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
                    <div class="hero-backdrop" style="background-image: url('{{ $novel['images']['jpg']['large_image_url'] ?? '' }}')"></div>
                    <div class="hero-overlay"></div>
                    
                    <div class="hero-content">
                        <span class="hero-badge">{{ $index === 0 ? 'Featured' : 'Top ' . ($index + 1) }}</span>
                        <h1 class="hero-title">{{ $novel['title'] ?? 'Untitled' }}</h1>
                        <p class="hero-synopsis">{{ Str::limit($novel['synopsis'] ?? 'No synopsis available.', 200) }}</p>
                        
                        <div class="hero-meta">
                            @if(isset($novel['score']) && $novel['score'])
                            <span class="meta-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                                {{ number_format($novel['score'], 1) }}
                            </span>
                            @endif
                            
                            @if(isset($novel['chapters']))
                            <span class="meta-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                                {{ $novel['chapters'] }} Ch
                            </span>
                            @endif
                            
                            @if(isset($novel['genres']) && count($novel['genres']) > 0)
                            <span class="meta-item">{{ $novel['genres'][0]['name'] }}</span>
                            @endif
                        </div>
                        
{{--  --}}

                        <div class="hero-actions">
                            <a href="{{ route('novels.show', $novel['mal_id']) }}" class="btn-hero primary">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                </svg>
                                Read Now
                            </a>
                            <a href="{{ route('novels.show', $novel['mal_id']) }}" class="btn-hero secondary">
                                More Info
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
                
                <!-- Carousel Controls -->
                <div class="carousel-controls">
                    <button class="carousel-btn prev" onclick="changeSlide(-1)">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>
                    <button class="carousel-btn next" onclick="changeSlide(1)">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                </div>
                
                <!-- Carousel Indicators -->
                <div class="carousel-indicators">
                    @foreach($heroNovels as $index => $novel)
                    <button class="indicator {{ $index === 0 ? 'active' : '' }}" onclick="goToSlide({{ $index }})"></button>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Side Panel -->
        <div class="hero-sidebar">
            <!-- Top Rated -->
            <div class="mini-card">
                <div class="mini-card-header">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    <span>Top Rated</span>
                </div>
                @if(isset($topRated[0]))
                <a href="{{ route('novels.show', $topRated[0]['mal_id']) }}" class="mini-card-content">
                    <img src="{{ $topRated[0]['images']['jpg']['image_url'] }}" alt="{{ $topRated[0]['title'] }}">
                    <div class="mini-info">
                        <h4>{{ Str::limit($topRated[0]['title'], 30) }}</h4>
                        <span class="mini-metric">★ {{ number_format($topRated[0]['score'] ?? 0, 1) }}</span>
                    </div>
                </a>
                @endif
            </div>
            
            <!-- Most Favorited -->
            <div class="mini-card">
                <div class="mini-card-header">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                    <span>Most Favorited</span>
                </div>
                @if(isset($mostFavorited[0]))
                <a href="{{ route('novels.show', $mostFavorited[0]['mal_id']) }}" class="mini-card-content">
                    <img src="{{ $mostFavorited[0]['images']['jpg']['image_url'] }}" alt="{{ $mostFavorited[0]['title'] }}">
                    <div class="mini-info">
                        <h4>{{ Str::limit($mostFavorited[0]['title'], 30) }}</h4>
                        <span class="mini-metric">♥ {{ number_format($mostFavorited[0]['favorites'] ?? 0) }}</span>
                    </div>
                </a>
                @endif
            </div>
            
            <!-- Most Active -->
            <div class="mini-card">
                <div class="mini-card-header">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span>Most Active</span>
                </div>
                @if(isset($mostActive[0]))
                <a href="{{ route('novels.show', $mostActive[0]['mal_id']) }}" class="mini-card-content">
                    <img src="{{ $mostActive[0]['images']['jpg']['image_url'] }}" alt="{{ $mostActive[0]['title'] }}">
                    <div class="mini-info">
                        <h4>{{ Str::limit($mostActive[0]['title'], 30) }}</h4>
                        <span class="mini-metric">👥 {{ number_format($mostActive[0]['members'] ?? 0) }}</span>
                    </div>
                </a>
                @endif
            </div>
        </div>
    </div>
    <!-- ===== WEEKLY FEATURED ===== -->
    @if(!empty($weeklyFeatured))
    <div class="section">
        <div class="section-header">
            <h2 class="section-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                Weekly Featured
            </h2>
            <a href="{{ route('novels.search') }}" class="view-all-link">View All →</a>
        </div>
        
        <div class="horizontal-scroll">
            @foreach($weeklyFeatured as $novel)
            <a href="{{ route('novels.show', $novel['mal_id']) }}" class="featured-card">
                <div class="featured-cover">
                    <img src="{{ $novel['images']['jpg']['large_image_url'] ?? 'https://via.placeholder.com/200x280' }}" 
                         alt="{{ $novel['title'] }}">
                    @if(isset($novel['score']) && $novel['score'])
                    <span class="cover-badge">★ {{ number_format($novel['score'], 1) }}</span>
                    @endif
                </div>
                <h3 class="featured-title">{{ Str::limit($novel['title'], 35) }}</h3>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- ===== THREE-COLUMN RANKINGS ===== -->
    <div class="rankings-grid">
        <!-- Power Ranking -->
        @if(!empty($powerRanking))
        <div class="ranking-column">
            <h3 class="ranking-header power">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                Power Ranking
            </h3>
            @foreach($powerRanking as $index => $novel)
            <a href="{{ route('novels.show', $novel['mal_id']) }}" class="rank-item">
                <span class="rank-number">{{ sprintf('%02d', $index + 1) }}</span>
                <img src="{{ $novel['images']['jpg']['image_url'] }}" alt="{{ $novel['title'] }}">
                <div class="rank-info">
                    <h4>{{ Str::limit($novel['title'], 30) }}</h4>
                    <span class="rank-metric">★ {{ number_format($novel['score'] ?? 0, 1) }}</span>
                </div>
            </a>
            @endforeach
        </div>
        @endif
        
        <!-- Collection Ranking -->
        @if(!empty($collectionRanking))
        <div class="ranking-column">
            <h3 class="ranking-header collection">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
                Collection Ranking
            </h3>
            @foreach($collectionRanking as $index => $novel)
            <a href="{{ route('novels.show', $novel['mal_id']) }}" class="rank-item">
                <span class="rank-number">{{ sprintf('%02d', $index + 1) }}</span>
                <img src="{{ $novel['images']['jpg']['image_url'] }}" alt="{{ $novel['title'] }}">
                <div class="rank-info">
                    <h4>{{ Str::limit($novel['title'], 30) }}</h4>
                    <span class="rank-metric">♥ {{ number_format($novel['favorites'] ?? 0) }}</span>
                </div>
            </a>
            @endforeach
        </div>
        @endif
        
        <!-- Active Ranking -->
        @if(!empty($activeRanking))
        <div class="ranking-column">
            <h3 class="ranking-header active">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                Active Ranking
            </h3>
            @foreach($activeRanking as $index => $novel)
            <a href="{{ route('novels.show', $novel['mal_id']) }}" class="rank-item">
                <span class="rank-number">{{ sprintf('%02d', $index + 1) }}</span>
                <img src="{{ $novel['images']['jpg']['image_url'] }}" alt="{{ $novel['title'] }}">
                <div class="rank-info">
                    <h4>{{ Str::limit($novel['title'], 30) }}</h4>
                    <span class="rank-metric">👥 {{ number_format($novel['members'] ?? 0) }}</span>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>

    <!-- ===== NEW RELEASES ===== -->
    @if(!empty($newReleases))
    <div class="section">
        <div class="section-header">
            <h2 class="section-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M12 6v6l4 2"></path>
                </svg>
                New Releases
            </h2>
        </div>
        
        <div class="novels-grid">
            @foreach($newReleases as $novel)
            <a href="{{ route('novels.show', $novel['mal_id']) }}" class="novel-card">
                <div class="novel-cover">
                    <img src="{{ $novel['images']['jpg']['large_image_url'] ?? 'https://via.placeholder.com/200x280' }}" 
                         alt="{{ $novel['title'] }}">
                    <div class="novel-overlay">
                        <span class="overlay-btn">Read</span>
                    </div>
                </div>
                <div class="novel-info">
                    <h3>{{ Str::limit($novel['title'], 40) }}</h3>
                    <div class="novel-meta">
                        @if(isset($novel['score']))
                        <span>★ {{ number_format($novel['score'], 1) }}</span>
                        @endif
                        @if(isset($novel['chapters']))
                        <span>{{ $novel['chapters'] }} Ch</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- ===== TRENDING / MOST READ ===== -->
    @if(!empty($trending))
    <div class="section">
        <div class="section-header">
            <h2 class="section-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                    <polyline points="17 6 23 6 23 12"></polyline>
                </svg>
                Trending Now
            </h2>
            <a href="{{ route('novels.search') }}" class="view-all-link">View All →</a>
        </div>
        
        <div class="novels-grid">
            @foreach($trending as $novel)
            <a href="{{ route('novels.show', $novel['mal_id']) }}" class="novel-card">
                <div class="novel-cover">
                    <img src="{{ $novel['images']['jpg']['large_image_url'] ?? 'https://via.placeholder.com/200x280' }}" 
                         alt="{{ $novel['title'] }}">
                    <div class="novel-overlay">
                        <span class="overlay-btn">Read</span>
                    </div>
                    @if(isset($novel['members']))
                    <span class="trending-badge">🔥 {{ number_format($novel['members']) }}</span>
                    @endif
                </div>
                <div class="novel-info">
                    <h3>{{ Str::limit($novel['title'], 40) }}</h3>
                    <div class="novel-meta">
                        @if(isset($novel['score']))
                        <span>★ {{ number_format($novel['score'], 1) }}</span>
                        @endif
                        @if(isset($novel['chapters']))
                        <span>{{ $novel['chapters'] }} Ch</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- ===== FOOTER ===== -->
    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>About KuroReader</h4>
                <p>Your ultimate destination for reading light novels online. Discover thousands of stories from various genres.</p>
            </div>
            
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="{{ route('novels.search') }}">Browse Novels</a></li>
                    <li><a href="/library">My Library</a></li>
                    <li><a href="/history">Reading History</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4>Support</h4>
                <ul>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Report Issue</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4>Follow Us</h4>
                <div class="social-links">
                    <a href="#" title="Twitter">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path>
                        </svg>
                    </a>
                    <a href="#" title="Discord">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515a.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0a12.64 12.64 0 0 0-.617-1.25a.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057a19.9 19.9 0 0 0 5.993 3.03a.078.078 0 0 0 .084-.028a14.09 14.09 0 0 0 1.226-1.994a.076.076 0 0 0-.041-.106a13.107 13.107 0 0 1-1.872-.892a.077.077 0 0 1-.008-.128a10.2 10.2 0 0 0 .372-.292a.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127a12.299 12.299 0 0 1-1.873.892a.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028a19.839 19.839 0 0 0 6.002-3.03a.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419c0-1.333.956-2.419 2.157-2.419c1.21 0 2.176 1.096 2.157 2.42c0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419c0-1.333.955-2.419 2.157-2.419c1.21 0 2.176 1.096 2.157 2.42c0 1.333-.946 2.418-2.157 2.418z"></path>
                        </svg>
                    </a>
                    <a href="#" title="GitHub">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.17 6.839 9.49.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.646.35-1.086.636-1.336-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0 1 12 6.836c.85.004 1.705.114 2.504.336 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C19.138 20.167 22 16.418 22 12c0-5.523-4.477-10-10-10z"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} KuroReader. All rights reserved. | Made with ❤️ for novel readers</p>
        </div>
    </footer>
</div>
<style>
/* ===== HOMEPAGE VARIABLES ===== */
.homepage {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1.5rem 2rem;
}


/* ===== HERO SECTION ===== */
.hero-wrapper {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}

.hero-main {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

.hero-carousel {
    position: relative;
    height: 480px;
}

.hero-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity 0.6s ease;
    pointer-events: none;
}

.hero-slide.active {
    opacity: 1;
    pointer-events: all;
}

.hero-backdrop {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    /* filter: blur(6px) brightness(0.8); */
    transform: scale(1.05);
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(85deg, rgba(10, 14, 19, 0.95) 30%, rgba(10, 14, 19, 0.7) 50%, transparent 100%);
    
}

.hero-content {
    position: relative;
    z-index: 2;
    padding: 2.5rem;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    max-width: 600px;
}

.hero-badge {
    display: inline-block;
    background: linear-gradient(135deg, #4f46e5, #818cf8);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 1.25rem;
    width: fit-content;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.hero-title {
    font-size: 2.25rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 1rem;
    line-height: 1.2;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.hero-synopsis {
    color: var(--text-secondary);
    margin-bottom: 1.75rem;
    line-height: 1.6;
    font-size: 1rem;
    max-width: 90%;
}

.hero-meta {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    align-items: center;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.meta-item svg {
    color: var(--warning);
}

.hero-actions {
    display: flex;
    gap: 1rem;
    margin-top: 0.5rem;
}

.btn-hero {
    padding: 0.875rem 1.75rem;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s;
    font-size: 0.95rem;
    border: none;
    cursor: pointer;
}

.btn-hero.primary {
    background: linear-gradient(135deg, #4f46e5, #818cf8);
    color: white;
    box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
}

.btn-hero.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(79, 70, 229, 0.5);
}

.btn-hero.secondary {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
    backdrop-filter: blur(10px);
}

.btn-hero.secondary:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: var(--accent-primary);
    transform: translateY(-2px);
}

/* Carousel Controls */
.carousel-controls {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    transform: translateY(-50%);
    display: flex;
    justify-content: space-between;
    padding: 0 1rem;
    z-index: 3;
    pointer-events: none;
}

.carousel-btn {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(10, 14, 19, 0.8);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
    pointer-events: all;
}

.carousel-btn:hover {
    background: var(--accent-primary);
    transform: scale(1.1);
    border-color: var(--accent-primary);
}

.carousel-indicators {
    position: absolute;
    bottom: 1.5rem;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 0.5rem;
    z-index: 3;
}

.indicator {
    width: 28px;
    height: 4px;
    background: rgba(255, 255, 255, 0.3);
    border: none;
    border-radius: 2px;
    cursor: pointer;
    transition: all 0.3s;
}

.indicator.active {
    background: var(--accent-primary);
    width: 40px;
}

.indicator:hover {
    background: rgba(255, 255, 255, 0.5);
}

/* ===== HERO SIDEBAR ===== */
.hero-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-top: 0;
}

.mini-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s;
}

.mini-card:hover {
    transform: translateY(-3px);
}

.mini-card-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-secondary);
    font-weight: 600;
    font-size: 0.875rem;
    background: rgba(255, 255, 255, 0.02);
}

.mini-card-content {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    text-decoration: none;
    transition: all 0.3s;
}

.mini-card-content:hover {
    background: var(--bg-tertiary);
}

.mini-card-content img {
    width: 60px;
    height: 85px;
    object-fit: cover;
    border-radius: 6px;
    border: 2px solid rgba(255, 255, 255, 0.1);
}

.mini-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 0.25rem;
}

.mini-info h4 {
    font-size: 0.9rem;
    color: var(--text-primary);
    margin: 0;
    line-height: 1.3;
}

.mini-metric {
    font-size: 0.875rem;
    color: var(--accent-secondary);
    font-weight: 600;
}

/* ===== SECTIONS ===== */
.section {
    margin-bottom: 2.5rem;
    padding: 0 0.5rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
}

.section-title {
    font-size: 1.375rem;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0;
}

.view-all-link {
    color: var(--accent-secondary);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.view-all-link:hover {
    color: var(--accent-primary);
    transform: translateX(3px);
}

/* ===== HORIZONTAL SCROLL ===== */
.horizontal-scroll {
    display: flex;
    gap: 1.25rem;
    overflow-x: auto;
    padding: 0.5rem 0.5rem 1.5rem;
    scroll-snap-type: x mandatory;
    margin: 0 -0.5rem;
}

.horizontal-scroll::-webkit-scrollbar {
    height: 6px;
}

.horizontal-scroll::-webkit-scrollbar-track {
    background: var(--bg-secondary);
    border-radius: 3px;
}

.horizontal-scroll::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    border-radius: 3px;
}

.featured-card {
    flex: 0 0 170px;
    text-decoration: none;
    scroll-snap-align: start;
    transition: transform 0.3s;
}

.featured-card:hover {
    transform: translateY(-5px);
}

.featured-cover {
    position: relative;
    width: 100%;
    height: 230px;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 0.75rem;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
}

.featured-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.featured-card:hover .featured-cover img {
    transform: scale(1.08);
}

.cover-badge {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: rgba(10, 14, 19, 0.9);
    backdrop-filter: blur(8px);
    color: var(--warning);
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 600;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.featured-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-primary);
    line-height: 1.4;
    margin: 0;
    padding: 0 0.25rem;
}

/* ===== RANKINGS GRID ===== */
.rankings-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
    margin-bottom: 2.5rem;
}

.ranking-column {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.25rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.ranking-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.125rem;
    font-weight: 700;
    margin-bottom: 1.25rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.ranking-header.power {
    color: var(--warning);
}

.ranking-header.collection {
    color: #ef4444;
}

.ranking-header.active {
    color: var(--success);
}

.rank-item {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    padding: 0.75rem;
    border-radius: 8px;
    margin-bottom: 0.75rem;
    text-decoration: none;
    transition: all 0.3s;
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid transparent;
}

.rank-item:hover {
    background: var(--bg-tertiary);
    transform: translateX(5px);
    border-color: rgba(79, 70, 229, 0.2);
}

.rank-number {
    font-size: 1.375rem;
    font-weight: 800;
    color: var(--accent-primary);
    min-width: 36px;
    text-align: center;
}

.rank-item img {
    width: 50px;
    height: 70px;
    object-fit: cover;
    border-radius: 6px;
    border: 2px solid rgba(255, 255, 255, 0.1);
}

.rank-info {
    flex: 1;
}

.rank-info h4 {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 0.25rem 0;
    line-height: 1.3;
}

.rank-metric {
    font-size: 0.75rem;
    color: var(--text-muted);
    font-weight: 500;
}


/* ===== NOVELS GRID ===== */
.novels-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
    gap: 1.25rem;
    padding: 0.5rem 0;
}

.novel-card {
    text-decoration: none;
    display: block;
    transition: transform 0.3s;
}

.novel-card:hover {
    transform: translateY(-5px);
}

.novel-cover {
    position: relative;
    width: 100%;
    height: 230px;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 0.75rem;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
}

.novel-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.novel-card:hover .novel-cover img {
    transform: scale(1.08);
}

.novel-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(10, 14, 19, 0.9) 20%, transparent 60%);
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding: 1rem;
    opacity: 0;
    transition: opacity 0.3s;
}

.novel-card:hover .novel-overlay {
    opacity: 1;
}

.overlay-btn {
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    color: white;
    padding: 0.5rem 1.25rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.875rem;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
}

.trending-badge {
    position: absolute;
    top: 0.5rem;
    left: 0.5rem;
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.9), rgba(239, 68, 68, 0.7));
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 600;
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.novel-info h3 {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 0.5rem 0;
    line-height: 1.4;
    padding: 0 0.25rem;
}

.novel-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.75rem;
    color: var(--text-muted);
    padding: 0 0.25rem;
}

/* ===== FOOTER ===== */
.site-footer {
    background: var(--bg-secondary);
    border-top: 1px solid var(--border-color);
    margin-top: 3rem;
    padding: 2.5rem 0 1rem;
    box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
}

.footer-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1.5rem;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.footer-section h4 {
    color: var(--text-primary);
    font-size: 1.125rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.footer-section p {
    color: var(--text-secondary);
    line-height: 1.6;
    font-size: 0.9rem;
    margin: 0;
}

.footer-section ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-section ul li {
    margin-bottom: 0.5rem;
}

.footer-section ul li a {
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.3s;
    display: inline-block;
}

.footer-section ul li a:hover {
    color: var(--accent-secondary);
    transform: translateX(3px);
}

.social-links {
    display: flex;
    gap: 0.75rem;
}

.social-links a {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: var(--bg-tertiary);
    border: 1px solid var(--border-color);
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.social-links a:hover {
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    color: white;
    border-color: var(--accent-primary);
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(79, 70, 229, 0.3);
}

.footer-bottom {
    text-align: center;
    padding: 1.5rem;
    border-top: 1px solid var(--border-color);
    color: var(--text-muted);
    font-size: 0.875rem;
    margin-top: 1rem;
}
/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .hero-wrapper {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .hero-sidebar {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
    
    .rankings-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 1.875rem;
    }
    
    .hero-content {
        padding: 1.75rem;
    }
    
    .hero-synopsis {
        max-width: 100%;
    }
    
    .hero-sidebar {
        grid-template-columns: 1fr;
    }
    
    .novels-grid {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    }
    
    .rankings-grid {
        gap: 1.25rem;
    }
}

@media (max-width: 480px) {
    .homepage {
        padding: 0 1rem 1.5rem;
    }
    
    .hero-title {
        font-size: 1.5rem;
    }
    
    .hero-actions {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .btn-hero {
        width: 100%;
        justify-content: center;
    }
    
    .section {
        padding: 0;
    }
    
    .section-title {
        font-size: 1.25rem;
    }
    
    .featured-card,
    .novels-grid {
        flex: 0 0 140px;
    }
    
    .featured-cover,
    .novel-cover {
        height: 200px;
    }
}
</style>

<script>
// Hero Carousel
let currentSlide = 0;
const slides = document.querySelectorAll('.hero-slide');
const indicators = document.querySelectorAll('.indicator');
const totalSlides = slides.length;

// Auto-play carousel
let autoPlayInterval = setInterval(() => {
    changeSlide(1);
}, 3000);

function changeSlide(direction) {
    // Remove active class from current slide
    slides[currentSlide].classList.remove('active');
    indicators[currentSlide].classList.remove('active');
    
    // Calculate new slide index
    currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
    
    // Add active class to new slide
    slides[currentSlide].classList.add('active');
    indicators[currentSlide].classList.add('active');
    
    // Reset auto-play timer
    clearInterval(autoPlayInterval);
    autoPlayInterval = setInterval(() => {
        changeSlide(1);
    }, 3000);
}

function goToSlide(index) {
    slides[currentSlide].classList.remove('active');
    indicators[currentSlide].classList.remove('active');
    
    currentSlide = index;
    
    slides[currentSlide].classList.add('active');
    indicators[currentSlide].classList.add('active');
    
    // Reset auto-play timer
    clearInterval(autoPlayInterval);
    autoPlayInterval = setInterval(() => {
        changeSlide(1);
    }, 3000);
}

// Pause on hover
document.getElementById('heroCarousel')?.addEventListener('mouseenter', () => {
    clearInterval(autoPlayInterval);
});

document.getElementById('heroCarousel')?.addEventListener('mouseleave', () => {
    autoPlayInterval = setInterval(() => {
        changeSlide(1);
    }, 3000);
});
</script>
@endsection