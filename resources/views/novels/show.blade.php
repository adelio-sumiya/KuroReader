@extends('layouts.app')

@section('title', $novel['title'] ?? 'Novel Detail')

@section('content')
<div class="novel-detail-page">
    <!-- Hero Section -->
    <div class="novel-hero">
        <div class="hero-backdrop" style="background-image: url('{{ $novel['images']['jpg']['large_image_url'] ?? '' }}')"></div>
        <div class="hero-overlay"></div>
        
        <div class="hero-content-wrapper">
            <div class="hero-poster">
                <img src="{{ $novel['images']['jpg']['large_image_url'] ?? 'https://via.placeholder.com/300x420' }}" 
                     alt="{{ $novel['title'] }}"
                     class="poster-image">
                <div class="poster-rating">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    <span class="rating-value">{{ number_format($novel['score'] ?? 0, 1) }}</span>
                    <span class="rating-count">/ 10</span>
                </div>
            </div>

            <div class="hero-info">
                <div class="breadcrumb">
                    <a href="/">Home</a>
                    <span>/</span>
                    <a href="/novels">Browse</a>
                    <span>/</span>
                    <span>{{ Str::limit($novel['title'], 30) }}</span>
                </div>

                <h1 class="novel-title">{{ $novel['title'] }}</h1>
                
                @if(isset($novel['title_english']) && $novel['title_english'])
                    <p class="novel-alt-title">{{ $novel['title_english'] }}</p>
                @endif

                <div class="novel-meta-tags">
                    @if(isset($novel['status']))
                        <span class="meta-badge status">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            {{ $novel['status'] }}
                        </span>
                    @endif
                    @if(isset($novel['chapters']))
                        <span class="meta-badge">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                            {{ $novel['chapters'] }} Chapters
                        </span>
                    @endif
                    @if(isset($novel['published']['prop']['from']['year']))
                        <span class="meta-badge">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            {{ $novel['published']['prop']['from']['year'] }}
                        </span>
                    @endif
                </div>

                @if(isset($novel['genres']) && count($novel['genres']) > 0)
                    <div class="genre-tags">
                        @foreach($novel['genres'] as $genre)
                            <span class="genre-tag">{{ $genre['name'] }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="action-buttons">
                    <a href="{{ $novel['url'] ?? '#' }}" class="btn-action primary" target="_blank">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                        </svg>
                        Start Reading
                    </a>
                    
                    @auth
                        <button onclick="addToLibrary()" class="btn-action secondary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Add to Library
                        </button>
                        
                        <button onclick="toggleFavorite()" class="btn-action icon-only">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="detail-container">
        <div class="detail-main">
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon score">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Score</div>
                        <div class="stat-value">{{ number_format($novel['score'] ?? 0, 1) }}/10</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon ranked">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Ranked</div>
                        <div class="stat-value">#{{ $novel['rank'] ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon popularity">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Popularity</div>
                        <div class="stat-value">#{{ $novel['popularity'] ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon members">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Members</div>
                        <div class="stat-value">{{ number_format($novel['members'] ?? 0) }}</div>
                    </div>
                </div>
            </div>

            <!-- Synopsis Section -->
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Synopsis</h2>
                </div>
                <div class="synopsis-content">
                    <p>{{ $novel['synopsis'] ?? 'No synopsis available for this novel.' }}</p>
                </div>
            </div>

            <!-- Additional Info -->
            @if(isset($novel['authors']) && count($novel['authors']) > 0)
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Authors</h2>
                    </div>
                    <div class="info-list">
                        @foreach($novel['authors'] as $author)
                            <span class="info-tag">{{ $author['name'] }}</span>
                        @endforeach
                    </div>
                </div>
            @endif


            
            @if(isset($novel['serializations']) && count($novel['serializations']) > 0)
                <div class="content-section">
                    <div class="section-header">
                        <h2 class="section-title">Serialization</h2>
                    </div>
                    <div class="info-list">
                        @foreach($novel['serializations'] as $serial)
                            <span class="info-tag">{{ $serial['name'] }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

            <!-- ===== CHAPTERS SECTION - BARU ===== -->
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        Chapters
                    </h2>
                </div>
                
                @if($chapters->isEmpty())
                    <div class="empty-message">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        <p>Chapter belum tersedia untuk novel ini.</p>
                        @if(auth()->check() && auth()->user()->is_admin)
                            <a href="{{ route('admin.chapters.index', $novel['mal_id']) }}" class="btn-action secondary" style="margin-top: 1rem;">
                                Tambah Chapter
                            </a>
                        @endif
                    </div>
                @else
                    <div class="chapters-list">
                        @foreach($chapters as $chapter)
                            <a href="{{ route('chapters.show', $chapter) }}" class="chapter-item">
                                <div class="chapter-number">
                                    <span class="chapter-label">Ch</span>
                                    <span class="chapter-num">{{ $chapter->chapter_number }}</span>
                                </div>
                                <div class="chapter-info">
                                    <h4 class="chapter-title">{{ $chapter->title }}</h4>
                                    <div class="chapter-meta">
                                        @if($chapter->pdf_path)
                                            <span class="chapter-badge pdf">PDF</span>
                                        @endif
                                        @if($chapter->epub_path)
                                            <span class="chapter-badge epub">EPUB</span>
                                        @endif
                                        @if($chapter->content)
                                            <span class="chapter-badge text">Text</span>
                                        @endif
                                        <span class="chapter-date">{{ $chapter->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="chapter-arrow">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>



            <!-- ===== REVIEWS SECTION - BARU ===== -->
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        Reviews
                        @if($reviews->isNotEmpty())
                            <span class="review-count">({{ $reviews->count() }})</span>
                        @endif
                    </h2>
                    @if($reviews->isNotEmpty())
                        <div class="average-rating">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            {{ number_format($averageRating, 1) }}/10
                        </div>
                    @endif
                </div>

                @auth
                    <!-- Write Review Form -->
                    <div class="write-review-box">
                        <h4>Write Your Review</h4>
                        <form action="{{ route('reviews.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="novel_api_id" value="{{ $novel['mal_id'] }}">
                            
                            <div class="form-group">
                                <label>Rating (1-10)</label>
                                <div class="rating-input">
                                    <input type="number" name="rating" min="1" max="10" value="{{ old('rating', $userReview->rating ?? 8) }}" required>
                                    <span class="rating-suffix">/ 10</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Your Review</label>
                                <textarea name="comment" rows="4" placeholder="Share your thoughts about this novel...">{{ old('comment', $userReview->comment ?? '') }}</textarea>
                            </div>

                            <button type="submit" class="btn-action primary">
                                @if($userReview)
                                    Update Review
                                @else
                                    Submit Review
                                @endif
                            </button>
                        </form>
                    </div>
                @else
                    <div class="empty-message">
                        <p><a href="{{ route('login') }}">Login</a> to write a review</p>
                    </div>
                @endauth

                <!-- Reviews List -->
                @if($reviews->isEmpty())
                    <div class="empty-message">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <p>Belum ada review untuk novel ini.</p>
                    </div>
                @else
                    <div class="reviews-list">
                        @foreach($reviews as $review)
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <div class="reviewer-avatar">
                                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="reviewer-name">{{ $review->user->name }}</div>
                                            <div class="review-date">{{ $review->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    <div class="review-rating">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                        </svg>
                                        {{ $review->rating }}/10
                                    </div>
                                </div>
                                
                                @if($review->comment)
                                    <div class="review-content">
                                        {{ $review->comment }}
                                    </div>
                                @endif

                                @if(auth()->check() && auth()->id() === $review->user_id)
                                    <div class="review-actions">
                                        <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete this review?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete">Delete</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
        <!-- Sidebar -->
        <div class="detail-sidebar">
            <div class="sidebar-card">
                <h3 class="sidebar-title">Information</h3>
                <div class="info-grid">
                    @if(isset($novel['type']))
                        <div class="info-item">
                            <span class="info-label">Type</span>
                            <span class="info-value">{{ $novel['type'] }}</span>
                        </div>
                    @endif
                    
                    @if(isset($novel['chapters']))
                        <div class="info-item">
                            <span class="info-label">Chapters</span>
                            <span class="info-value">{{ $novel['chapters'] }}</span>
                        </div>
                    @endif

                    @if(isset($novel['status']))
                        <div class="info-item">
                            <span class="info-label">Status</span>
                            <span class="info-value">{{ $novel['status'] }}</span>
                        </div>
                    @endif

                    @if(isset($novel['published']['string']))
                        <div class="info-item">
                            <span class="info-label">Published</span>
                            <span class="info-value">{{ $novel['published']['string'] }}</span>
                        </div>
                    @endif
                </div>
            </div>

            @if(isset($novel['themes']) && count($novel['themes']) > 0)
                <div class="sidebar-card">
                    <h3 class="sidebar-title">Themes</h3>
                    <div class="theme-tags">
                        @foreach($novel['themes'] as $theme)
                            <span class="theme-tag">{{ $theme['name'] }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>


<style>
/* ===== NOVEL DETAIL PAGE ===== */
.novel-detail-page {
    min-height: 100vh;
    padding-bottom: 3rem;
}

/* ===== HERO SECTION ===== */
.novel-hero {
    position: relative;
    min-height: 500px;
    display: flex;
    align-items: flex-end;
    padding: 3rem 0 2rem;
    margin-bottom: 2rem;
}

.hero-backdrop {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    filter: blur(20px);
    opacity: 0.15;
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, var(--bg-primary) 0%, transparent 50%, var(--bg-primary) 100%);
}

.hero-content-wrapper {
    position: relative;
    z-index: 1;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1.5rem;
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 2.5rem;
    width: 100%;
}

/* ===== POSTER ===== */
.hero-poster {
    position: relative;
}

.poster-image {
    width: 100%;
    aspect-ratio: 2/3;
    object-fit: cover;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
    border: 1px solid var(--border-color);
}

.poster-rating {
    position: absolute;
    bottom: -15px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--bg-secondary);
    border: 2px solid var(--accent-primary);
    border-radius: 12px;
    padding: 0.75rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
}

.poster-rating svg {
    color: var(--warning);
}

.rating-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
}

.rating-count {
    font-size: 0.875rem;
    color: var(--text-muted);
}

/* ===== HERO INFO ===== */
.hero-info {
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    font-size: 0.875rem;
    color: var(--text-muted);
}

.breadcrumb a {
    color: var(--text-secondary);
    text-decoration: none;
    transition: color 0.3s;
}

.breadcrumb a:hover {
    color: var(--accent-secondary);
}

.novel-title {
    font-size: 3rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    line-height: 1.2;
}

.novel-alt-title {
    font-size: 1.125rem;
    color: var(--text-secondary);
    margin-bottom: 1rem;
}

.novel-meta-tags {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

.meta-badge {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    color: var(--text-secondary);
    padding: 0.375rem 0.875rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
}

.meta-badge.status {
    background: rgba(79, 70, 229, 0.1);
    border-color: rgba(79, 70, 229, 0.3);
    color: var(--accent-secondary);
}

.genre-tags {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 1.5rem;
}

.genre-tag {
    background: var(--bg-tertiary);
    color: var(--accent-secondary);
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    border: 1px solid rgba(129, 140, 248, 0.2);
    transition: all 0.3s;
    cursor: pointer;
}

.genre-tag:hover {
    background: rgba(79, 70, 229, 0.15);
    border-color: var(--accent-primary);
}

/* ===== ACTION BUTTONS ===== */
.action-buttons {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.btn-action {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1.75rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    border: none;
}

.btn-action.primary {
    background: var(--accent-primary);
    color: white;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.btn-action.primary:hover {
    background: var(--accent-secondary);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.4);
}

.btn-action.secondary {
    background: var(--bg-secondary);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
}

.btn-action.secondary:hover {
    background: var(--bg-tertiary);
    border-color: var(--accent-primary);
}

.btn-action.icon-only {
    padding: 0.875rem;
    background: var(--bg-secondary);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
}

.btn-action.icon-only:hover {
    color: #ef4444;
    border-color: #ef4444;
}

/* ===== DETAIL CONTAINER ===== */
.detail-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1.5rem;
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 2rem;
}

/* ===== STATS GRID ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s;
}

.stat-card:hover {
    border-color: var(--accent-primary);
    transform: translateY(-2px);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon.score {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
}

.stat-icon.ranked {
    background: rgba(79, 70, 229, 0.1);
    color: var(--accent-secondary);
}

.stat-icon.popularity {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
}

.stat-icon.members {
    background: rgba(129, 140, 248, 0.1);
    color: var(--accent-tertiary);
}

.stat-info {
    flex: 1;
}

.stat-label {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin-bottom: 0.25rem;
}

.stat-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
}

/* ===== CONTENT SECTIONS ===== */
.content-section {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.section-header {
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--border-color);
}

.section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
}

.synopsis-content p {
    font-size: 1rem;
    line-height: 1.8;
    color: var(--text-secondary);
}

.info-list {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.info-tag {
    background: var(--bg-tertiary);
    color: var(--text-secondary);
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.875rem;
}

/* ===== SIDEBAR ===== */
.sidebar-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.sidebar-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--border-color);
}

.info-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.info-label {
    font-size: 0.875rem;
    color: var(--text-muted);
}

.info-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
}

.theme-tags {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.theme-tag {
    background: var(--bg-tertiary);
    color: var(--text-secondary);
    padding: 0.5rem 0.875rem;
    border-radius: 8px;
    font-size: 0.875rem;
    text-align: center;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .detail-container {
        grid-template-columns: 1fr;
    }

    .detail-sidebar {
        order: -1;
    }
}

@media (max-width: 768px) {
    .hero-content-wrapper {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .hero-poster {
        max-width: 280px;
        margin: 0 auto;
    }

    .novel-title {
        font-size: 2rem;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn-action {
        width: 100%;
        justify-content: center;
    }
}

/* ===== NEW: CHAPTERS LIST STYLES ===== */
.chapters-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.chapter-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-tertiary);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.3s;
}

.chapter-item:hover {
    background: var(--bg-hover);
    border-color: var(--accent-primary);
    transform: translateX(5px);
}

.chapter-number {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    border-radius: 10px;
    flex-shrink: 0;
}

.chapter-label {
    font-size: 0.625rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.8);
    text-transform: uppercase;
}

.chapter-num {
    font-size: 1.25rem;
    font-weight: 800;
    color: white;
}

.chapter-info {
    flex: 1;
}

.chapter-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.chapter-meta {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    flex-wrap: wrap;
}

.chapter-badge {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-weight: 600;
}

.chapter-badge.pdf {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.chapter-badge.epub {
    background: rgba(139, 92, 246, 0.1);
    color: #8b5cf6;
}

.chapter-badge.text {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.chapter-date {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.chapter-arrow {
    color: var(--text-muted);
    transition: all 0.3s;
}

.chapter-item:hover .chapter-arrow {
    color: var(--accent-secondary);
    transform: translateX(5px);
}

/* ===== NEW: REVIEWS SECTION STYLES ===== */
.review-count {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-muted);
    margin-left: 0.5rem;
}

.average-rating {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 700;
}

.write-review-box {
    background: var(--bg-tertiary);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.write-review-box h4 {
    color: var(--text-primary);
    margin-bottom: 1rem;
    font-size: 1.125rem;
}

.write-review-box .form-group {
    margin-bottom: 1rem;
}

.write-review-box label {
    display: block;
    color: var(--text-secondary);
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.rating-input {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.rating-input input {
    width: 80px;
    padding: 0.625rem;
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    color: var(--text-primary);
    font-size: 1rem;
    font-weight: 600;
}

.rating-suffix {
    color: var(--text-muted);
    font-size: 0.875rem;
}

.write-review-box textarea {
    width: 100%;
    padding: 0.75rem;
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    color: var(--text-primary);
    font-size: 0.95rem;
    resize: vertical;
    min-height: 100px;
}

.write-review-box textarea:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.reviews-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.review-item {
    background: var(--bg-tertiary);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 1.25rem;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.reviewer-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.reviewer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1rem;
}

.reviewer-name {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.95rem;
}

.review-date {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.review-rating {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-weight: 700;
    font-size: 0.875rem;
}

.review-content {
    color: var(--text-secondary);
    line-height: 1.6;
    font-size: 0.95rem;
}

@media (max-width: 480px) {
    .novel-title {
        font-size: 1.5rem;
    }


    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function addToLibrary() {
    @auth
        fetch('/library/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                novel_api_id: {{ $novel['mal_id'] ?? 0 }},
                status: 'want_to_read'
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Added to your library!');
            } else {
                alert(data.message || 'Failed to add to library');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    @else
        window.location.href = '/login';
    @endauth
}

function toggleFavorite() {
    alert('Favorite feature coming soon!');
}
</script>
@endsection