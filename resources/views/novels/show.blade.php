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
