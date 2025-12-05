show.blade

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
                {{-- <div class="poster-rating">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    <span class="rating-value">{{ number_format($novel['score'] ?? 0, 1) }}</span>
                    <span class="rating-count">/ 10</span>
                </div> --}}
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
                    {{-- @if(isset($novel['status']))
                        <span class="meta-badge status">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            {{ $novel['status'] }}
                        </span>
                    @endif --}}
                    @if(isset($novel['chapters']))
                        <span class="meta-badge">
                            {{-- <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                            {{ $novel['chapters'] }} Chapters --}}
                        </span>
                    @endif
                    {{-- @if(isset($novel['published']['prop']['from']['year']))
                        <span class="meta-badge">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            {{ $novel['published']['prop']['from']['year'] }}
                        </span>
                    @endif --}}
                </div>

                @if(isset($novel['genres']) && count($novel['genres']) > 0)
                    <div class="genre-tags">
                        @foreach($novel['genres'] as $genre)
                            <span class="genre-tag">{{ $genre['name'] }}</span>
                        @endforeach
                        
                    </div>
                @endif

                <div class="action-buttons">
                    @php
                        $firstChapter = \App\Models\Chapter::where('novel_api_id', $novel['mal_id'])
                            ->orderBy('chapter_number')
                            ->first();
                        $hasChapters = \App\Models\Chapter::where('novel_api_id', $novel['mal_id'])->exists();
                    @endphp
    
                @if($hasChapters && $firstChapter)
                    <a href="{{ route('chapters.show', $firstChapter) }}" class="btn-action primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                        </svg>
                        Start Reading
                    </a>
                @else
                    <button class="btn-action primary disabled" disabled>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18 6L6 18M6 6l12 12"></path>
                        </svg>
                        No Chapters Available
                    </button>
                @endif
                

                    @auth
                        <button onclick="addToLibrary()" class="btn-action secondary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Add to Library
                        </button>
                        
                    @endauth

                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.chapters.index', $novel['mal_id']) }}" class="btn-action admin">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 20h9"></path>
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                            </svg>
                            Manage Chapters
                        </a>
                    @endif
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
                        <div class="stat-label">Score MAL</div>
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

@auth
<div class="review-form-card">
    <div class="review-form-header">
        <h2 class="review-form-title">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
            {{ $userReview ? 'Edit Your Review' : 'Write a Review' }}
        </h2>
    </div>

    <form action="{{ $userReview ? route('reviews.update', $userReview) : route('reviews.store') }}" method="POST" class="review-form">
        @csrf
        @if($userReview)
            @method('PUT')
        @else
            <input type="hidden" name="novel_api_id" value="{{ $novel['mal_id'] }}">
        @endif
        
        <div class="rating-input-group">
            <label class="rating-label">Your Rating</label>
<div class="star-rating-input">
    @for($i = 10; $i >= 1; $i--)
    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" 
           {{ (old('rating', optional($userReview)->rating) == $i) ? 'checked' : '' }} required>
    <label for="star{{ $i }}" class="star-label">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
        </svg>
    </label>
    @endfor
</div>
            {{-- <span class="rating-value-display">Rating: <strong id="ratingValue">{{ old('rating', optional($userReview)->rating) ?? 5 }}</strong>/10</span> --}}
        </div>
        
        <div class="comment-input-group">
            <label class="comment-label">Your Thoughts</label>
            <textarea name="comment" 
                      rows="4" 
                      class="comment-textarea"
                      placeholder="Share your thoughts about this novel... What did you like or dislike?">{{ old('comment', optional($userReview)->comment) }}</textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-submit-review">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 11 12 14 22 4"></polyline>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                </svg>
                {{ $userReview ? 'Update Review' : 'Submit Review' }}
            </button>

            @if($userReview)
            <form action="{{ route('reviews.destroy', $userReview) }}" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete your review?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete-review">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    Delete
                </button>
            </form>
            @endif
        </div>
    </form>
</div>
@endauth

<!-- All Reviews Section -->
<div class="reviews-section">
    <div class="reviews-header">
        <div class="reviews-title-wrapper">
            <h2 class="reviews-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                User Reviews
                <span class="review-count">{{ $reviews->count() }}</span>
            </h2>
        </div>
        
        @if($averageRating)
        <div class="average-rating-box">
            <div class="avg-rating-score">{{ number_format($averageRating, 1) }}</div>
            <div class="avg-rating-stars">
                @for($i = 1; $i <= 10; $i++)
                <svg width="16" height="16" viewBox="0 0 24 24" fill="{{ $i <= round($averageRating) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                @endfor
            </div>
            <div class="avg-rating-text">Average Rating</div>
        </div>
        @endif
    </div>

    <div class="reviews-list">
        @forelse($reviews as $review)
        <div class="review-card">
            <div class="review-card-header">
                <div class="reviewer-info">
                    <div class="reviewer-avatar">
                        @if($review->user->avatar)
                            <img src="{{ $review->user->avatar_url }}" alt="{{ $review->user->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        @else
                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="reviewer-details">
                        <h4 class="reviewer-name">{{ $review->user->name }}</h4>
                        <div class="review-meta">
                            <span class="review-date">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                {{ $review->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="review-rating">
                    <div class="rating-stars">
                        @for($i = 1; $i <= 10; $i++)
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="{{ $i <= $review->rating ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                        @endfor
                    </div>
                    <span class="rating-number">{{ $review->rating }}/10</span>
                </div>
            </div>
            
            @if($review->comment)
            <div class="review-comment">
                <p>{{ $review->comment }}</p>
            </div>
            @endif
        </div>
        @empty
        <div class="empty-reviews">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <h3>No Reviews Yet</h3>
            <p>Be the first to share your thoughts about this novel!</p>
        </div>
        @endforelse
    </div>
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
    align-items: flex-start; /* Ubah dari flex-end ke flex-start */
}

.hero-info {
    display: flex;
    flex-direction: column;
    margin-top: 1.5rem; /* Tambahkan margin atas */
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1.5rem; /* Tambahkan margin bawah breadcrumb */
    font-size: 0.875rem;
    color: var(--text-muted);
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
    margin-bottom: 0.75rem;
}

.genre-tags {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 1.25rem;
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

/* .meta-badge {
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
} */

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
.btn-action.primary.disabled {
    background: var(--bg-tertiary);
    color: var(--text-muted);
    border: 1px solid var(--border-color);
    cursor: not-allowed;
    opacity: 0.7;
    box-shadow: none;
}

.btn-action.primary.disabled:hover {
    transform: none;
    background: var(--bg-tertiary);
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
/* ===== ADMIN BUTTON ===== */
.btn-action.admin {
    background: linear-gradient(135deg, var(--accent-tertiary) 0%, #8b5cf6 100%);
    color: white;
    border: none;
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
}

.btn-action.admin:hover {
    background: linear-gradient(135deg, #8b5cf6 0%, var(--accent-tertiary) 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(139, 92, 246, 0.4);
}

.btn-action.admin svg {
    stroke: white;
    stroke-width: 2;
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

@media (max-width: 480px) {
    .novel-title {
        font-size: 1.5rem;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }
}



/* ===== REVIEW FORM CARD ===== */
.review-form-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 2rem;
}

.review-form-header {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(129, 140, 248, 0.1));
    border-bottom: 1px solid var(--border-color);
    padding: 1.5rem;
}

.review-form-title {
    font-size: 1.375rem;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0;
}

.review-form-title svg {
    color: var(--accent-primary);
}

.review-form {
    padding: 1.5rem;
}

/* ===== RATING INPUT ===== */
.rating-input-group {
    margin-bottom: 1.5rem;
}

.rating-label {
    display: block;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 1rem;
    font-size: 0.95rem;
}

.star-rating-input {
    display: flex;
    gap: 0.5rem;
    flex-direction: row-reverse;
    justify-content: flex-end;
    margin-bottom: 0.75rem;
}

.star-rating-input input[type="radio"] {
    display: none;
}

.star-label {
    cursor: pointer;
    transition: all 0.2s;
    color: var(--text-muted);
}

.star-label:hover,
.star-label:hover ~ .star-label {
    color: var(--warning);
    transform: scale(1.1);
}

.star-rating-input input[type="radio"]:checked ~ .star-label {
    color: var(--warning);
}

.rating-value-display {
    display: block;
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.rating-value-display strong {
    color: var(--warning);
    font-size: 1.125rem;
}

/* ===== COMMENT INPUT ===== */
.comment-input-group {
    margin-bottom: 1.5rem;
}

.comment-label {
    display: block;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
}

.comment-textarea {
    width: 100%;
    padding: 1rem;
    background: var(--bg-tertiary);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    color: var(--text-primary);
    font-size: 0.95rem;
    line-height: 1.6;
    resize: vertical;
    transition: all 0.3s;
    font-family: inherit;
}

.comment-textarea:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.comment-textarea::placeholder {
    color: var(--text-muted);
}

/* ===== FORM ACTIONS ===== */
.form-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.delete-form {
    display: inline-block;
}

.btn-submit-review {
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    color: white;
    border: none;
    padding: 0.875rem 1.75rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-submit-review:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(79, 70, 229, 0.3);
}

.btn-delete-review {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger);
    border: 1px solid rgba(239, 68, 68, 0.3);
    padding: 0.875rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-delete-review:hover {
    background: rgba(239, 68, 68, 0.2);
    transform: translateY(-2px);
}

/* ===== REVIEWS SECTION ===== */
.reviews-section {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    overflow: hidden;
}

.reviews-header {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.05), rgba(129, 140, 248, 0.05));
    border-bottom: 1px solid var(--border-color);
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.reviews-title-wrapper {
    flex: 1;
}

.reviews-title {
    font-size: 1.375rem;
    font-weight: 700;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0;
}

.reviews-title svg {
    color: var(--accent-primary);
}

.review-count {
    background: var(--accent-primary);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
}

/* ===== AVERAGE RATING BOX ===== */
.average-rating-box {
    background: var(--bg-tertiary);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1rem 1.5rem;
    text-align: center;
    min-width: 140px;
}

.avg-rating-score {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--warning);
    line-height: 1;
    margin-bottom: 0.5rem;
}

.avg-rating-stars {
    display: flex;
    gap: 0.25rem;
    justify-content: center;
    margin-bottom: 0.5rem;
    color: var(--warning);
}

.avg-rating-text {
    font-size: 0.75rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

/* ===== REVIEWS LIST ===== */
.reviews-list {
    padding: 1.5rem;
}

.review-card {
    background: var(--bg-tertiary);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.3s;
}

.review-card:hover {
    border-color: var(--accent-primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.review-card:last-child {
    margin-bottom: 0;
}

.review-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.reviewer-info {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.reviewer-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.reviewer-details {
    flex: 1;
}

.reviewer-name {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 0.375rem;
}

.review-meta {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.review-date {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    color: var(--text-muted);
    font-size: 0.875rem;
}

.review-rating {
    text-align: right;
}

.rating-stars {
    display: flex;
    gap: 0.25rem;
    color: var(--warning);
    margin-bottom: 0.375rem;
}

.rating-number {
    display: block;
    font-weight: 700;
    color: var(--warning);
    font-size: 1.125rem;
}

.review-comment {
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
}

.review-comment p {
    color: var(--text-secondary);
    line-height: 1.7;
    margin: 0;
}

/* ===== EMPTY STATE ===== */
.empty-reviews {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-reviews svg {
    color: var(--text-muted);
    opacity: 0.4;
    margin-bottom: 1.5rem;
}

.empty-reviews h3 {
    font-size: 1.5rem;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.empty-reviews p {
    color: var(--text-secondary);
    font-size: 0.95rem;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .review-form-header,
    .reviews-header {
        padding: 1.25rem;
    }

    .reviews-header {
        flex-direction: column;
        align-items: stretch;
    }

    .average-rating-box {
        width: 100%;
    }

    .star-rating-input {
        gap: 0.375rem;
    }

    .star-label svg {
        width: 20px;
        height: 20px;
    }

    .review-card-header {
        flex-direction: column;
    }

    .review-rating {
        text-align: left;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn-submit-review,
    .btn-delete-review {
        width: 100%;
        justify-content: center;
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
            alert('Berhasil dimasukkan ke perpustakaan Anda!');
        });
    @else
        window.location.href = '/login';
    @endauth
}


</script>
@endsection
