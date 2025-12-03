@extends('layouts.app')

@section('title', 'Search Novels - KuroReader')

@section('content')
<div class="search-page">
    <!-- Search Header -->
    <div class="search-header">
        <h1 class="page-title">
            @if(!empty($query))
                Search Results for "{{ $query }}"
            @elseif(!empty($selectedGenre))
                {{ $selectedGenre }} Novels
            @else
                Browse All Novels
            @endif
        </h1>
        
        <!-- Search Box -->
        <form action="{{ route('novels.search') }}" method="GET" class="search-form">
            <div class="search-input-wrapper">
                <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <input type="text" 
                       name="q" 
                       class="search-input"
                       placeholder="Search for novels, authors, or tags..." 
                       value="{{ $query ?? '' }}">
                <button type="submit" class="search-submit-btn">
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Genre Filter Chips -->
    <div class="filters-section">
        <div class="filter-label">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
            </svg>
            Filter by Genre:
        </div>
        <div class="genre-filter-chips">
            <a href="{{ route('novels.search') }}" 
               class="genre-filter-chip {{ empty($selectedGenre) && empty($query) ? 'active' : '' }}">
                All Genres
            </a>
            @foreach($allGenres as $genre)
            <a href="{{ route('novels.search') }}?genre={{ $genre['mal_id'] }}&genre_name={{ urlencode($genre['name']) }}" 
               class="genre-filter-chip {{ $selectedGenreId == $genre['mal_id'] ? 'active' : '' }}">
                {{ $genre['name'] }}
            </a>
            @endforeach
        </div>
    </div>

    <!-- Results Info -->
    <div class="results-info">
        <span class="results-count">
            @if(!empty($novels))
                Showing {{ count($novels) }} novels
            @else
                No results found
            @endif
        </span>
        
        <!-- Sort Options -->
        <div class="sort-options">
            <label for="sort">Sort by:</label>
            <select id="sort" onchange="sortNovels(this.value)">
                <option value="score">Highest Rated</option>
                <option value="members">Most Popular</option>
                <option value="favorites">Most Favorited</option>
                <option value="title">Title (A-Z)</option>
            </select>
        </div>
    </div>

    @if(isset($error))
        <div class="error-banner">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            {{ $error }}
        </div>
    @endif

    <!-- Novels Grid -->
    @if(empty($novels))
        <div class="empty-state">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="m21 21-4.35-4.35"></path>
            </svg>
            <h3>No Novels Found</h3>
            <p>Try searching with different keywords or browse all novels</p>
            <a href="{{ route('novels.search') }}" class="btn-primary">Browse All Novels</a>
        </div>
    @else
        <div class="search-results-grid" id="novelsGrid">
            @foreach($novels as $novel)
            <div class="search-novel-card" 
                 data-score="{{ $novel['score'] ?? 0 }}"
                 data-members="{{ $novel['members'] ?? 0 }}"
                 data-favorites="{{ $novel['favorites'] ?? 0 }}"
                 data-title="{{ $novel['title'] ?? '' }}">
                <a href="{{ route('novels.show', $novel['mal_id']) }}" class="card-link">
                    <div class="card-image-wrapper">
                        <img src="{{ $novel['images']['jpg']['large_image_url'] ?? 'https://via.placeholder.com/200x280' }}" 
                             alt="{{ $novel['title'] }}"
                             loading="lazy">
                        
                        <!-- Rating Badge -->
                        @if(isset($novel['score']) && $novel['score'])
                        <div class="card-rating-badge">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            {{ number_format($novel['score'], 1) }}
                        </div>
                        @endif

                        <!-- Overlay on Hover -->
                        <div class="card-hover-overlay">
                            <div class="overlay-content">
                                <span class="read-now-btn">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                    Read Now
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="card-content">
                        <h3 class="card-title">{{ Str::limit($novel['title'], 45) }}</h3>
                        
                        <div class="card-meta">
                            @if(isset($novel['genres']) && count($novel['genres']) > 0)
                            <span class="meta-genre">{{ $novel['genres'][0]['name'] }}</span>
                            @endif
                            
                            @if(isset($novel['chapters']))
                            <span class="meta-chapters">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                                {{ $novel['chapters'] }} Ch
                            </span>
                            @endif
                        </div>

                        <!-- Status Badge -->
                        @if(isset($novel['status']))
                        <div class="card-status {{ strtolower($novel['status']) }}">
                            {{ $novel['status'] }}
                        </div>
                        @endif
                    </div>
                </a>

                <!-- Quick Actions -->
                <div class="card-actions">
                    <button class="action-btn" onclick="addToLibrary({{ $novel['mal_id'] }})" title="Add to Library">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m19 21-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/>
                        </svg>
                    </button>
                    <button class="action-btn" onclick="toggleFavorite({{ $novel['mal_id'] }})" title="Favorite">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if(!empty($novels) && count($novels) >= 20)
        <div class="pagination-section">
            <button class="pagination-btn" onclick="loadMore()">
                Load More Novels
            </button>
        </div>
        @endif
    @endif
</div>

<style>
/* ===== SEARCH PAGE STYLES ===== */
.search-page {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1.5rem 3rem;
}

/* ===== SEARCH HEADER ===== */
.search-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 1.5rem;
}

.search-form {
    max-width: 800px;
}

.search-input-wrapper {
    display: flex;
    align-items: center;
    background: var(--bg-secondary);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    transition: all 0.3s;
}

.search-input-wrapper:focus-within {
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
}

.search-icon {
    color: var(--text-muted);
    margin-right: 0.75rem;
    flex-shrink: 0;
}

.search-input {
    flex: 1;
    background: transparent;
    border: none;
    color: var(--text-primary);
    font-size: 1rem;
    outline: none;
}

.search-input::placeholder {
    color: var(--text-muted);
}

.search-submit-btn {
    background: var(--accent-primary);
    color: white;
    border: none;
    padding: 0.625rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    margin-left: 0.75rem;
}

.search-submit-btn:hover {
    background: var(--accent-secondary);
    transform: translateY(-1px);
}

/* ===== FILTERS SECTION ===== */
.filters-section {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.25rem;
    margin-bottom: 2rem;
}

.filter-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.genre-filter-chips {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.genre-filter-chip {
    background: var(--bg-tertiary);
    color: var(--text-secondary);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    border: 1px solid var(--border-color);
    transition: all 0.3s;
}

.genre-filter-chip:hover {
    background: var(--bg-hover);
    color: var(--text-primary);
    border-color: var(--accent-primary);
}

.genre-filter-chip.active {
    background: var(--accent-primary);
    color: white;
    border-color: var(--accent-primary);
}

/* ===== RESULTS INFO ===== */
.results-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 8px;
}

.results-count {
    color: var(--text-secondary);
    font-weight: 500;
}

.sort-options {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.sort-options label {
    color: var(--text-secondary);
    font-size: 0.875rem;
    margin: 0;
}

.sort-options select {
    background: var(--bg-tertiary);
    border: 1px solid var(--border-color);
    color: var(--text-primary);
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
    cursor: pointer;
    outline: none;
    transition: all 0.3s;
}

.sort-options select:focus {
    border-color: var(--accent-primary);
}

/* ===== SEARCH RESULTS GRID ===== */
.search-results-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
}

.search-novel-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s;
    position: relative;
}

.search-novel-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.4);
    border-color: var(--accent-primary);
}

.card-link {
    text-decoration: none;
    display: block;
}

/* ===== CARD IMAGE ===== */
.card-image-wrapper {
    position: relative;
    width: 100%;
    padding-top: 140%;
    overflow: hidden;
    background: var(--bg-tertiary);
}

.card-image-wrapper img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.search-novel-card:hover .card-image-wrapper img {
    transform: scale(1.05);
}

.card-rating-badge {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    background: rgba(10, 14, 19, 0.95);
    backdrop-filter: blur(8px);
    color: var(--warning);
    padding: 0.375rem 0.75rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.375rem;
    border: 1px solid rgba(245, 158, 11, 0.3);
    z-index: 2;
}

/* ===== HOVER OVERLAY ===== */
.card-hover-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(10, 14, 19, 0.95) 0%, transparent 60%);
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding: 1.5rem;
    opacity: 0;
    transition: opacity 0.3s;
}

.search-novel-card:hover .card-hover-overlay {
    opacity: 1;
}

.read-now-btn {
    background: var(--accent-primary);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s;
}

.read-now-btn:hover {
    background: var(--accent-secondary);
    transform: translateY(-2px);
}

/* ===== CARD CONTENT ===== */
.card-content {
    padding: 1rem;
}

.card-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.75rem;
    line-height: 1.4;
    min-height: 2.8rem;
}

.card-meta {
    display: flex;
    gap: 0.75rem;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 0.5rem;
}

.meta-genre {
    background: rgba(79, 70, 229, 0.1);
    color: var(--accent-secondary);
    padding: 0.25rem 0.625rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
}

.meta-chapters {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    color: var(--text-muted);
    font-size: 0.75rem;
}

.card-status {
    display: inline-block;
    padding: 0.25rem 0.625rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

.card-status.publishing {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
}

.card-status.finished {
    background: rgba(79, 70, 229, 0.1);
    color: var(--accent-secondary);
}

/* ===== CARD ACTIONS ===== */
.card-actions {
    display: flex;
    gap: 0.5rem;
    padding: 0 1rem 1rem;
}

.action-btn {
    flex: 1;
    background: var(--bg-tertiary);
    border: 1px solid var(--border-color);
    color: var(--text-secondary);
    padding: 0.5rem;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.action-btn:hover {
    background: var(--bg-hover);
    color: var(--accent-secondary);
    border-color: var(--accent-primary);
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 12px;
}

.empty-state svg {
    color: var(--text-muted);
    opacity: 0.5;
    margin-bottom: 1.5rem;
}

.empty-state h3 {
    font-size: 1.5rem;
    color: var(--text-primary);
    margin-bottom: 0.75rem;
}

.empty-state p {
    color: var(--text-secondary);
    margin-bottom: 1.5rem;
}

.btn-primary {
    display: inline-block;
    background: var(--accent-primary);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-primary:hover {
    background: var(--accent-secondary);
    transform: translateY(-2px);
}

/* ===== ERROR BANNER ===== */
.error-banner {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #fca5a5;
    padding: 1rem 1.25rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

/* ===== PAGINATION ===== */
.pagination-section {
    text-align: center;
    margin-top: 3rem;
}

.pagination-btn {
    background: var(--bg-secondary);
    border: 2px solid var(--accent-primary);
    color: var(--accent-primary);
    padding: 0.875rem 2rem;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.pagination-btn:hover {
    background: var(--accent-primary);
    color: white;
    transform: translateY(-2px);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .search-page {
        padding: 1.5rem 1rem 2rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .search-input-wrapper {
        flex-direction: column;
        align-items: stretch;
        gap: 0.75rem;
    }
    
    .search-icon {
        margin-right: 0;
    }
    
    .search-submit-btn {
        margin-left: 0;
        width: 100%;
    }
    
    .results-info {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .search-results-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 1rem;
    }
}

@media (max-width: 480px) {
    .search-results-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .genre-filter-chips {
        gap: 0.5rem;
    }
    
    .genre-filter-chip {
        font-size: 0.8rem;
        padding: 0.375rem 0.75rem;
    }
}
</style>

<script>
// Sort novels function
function sortNovels(sortBy) {
    const grid = document.getElementById('novelsGrid');
    const cards = Array.from(grid.children);
    
    cards.sort((a, b) => {
        let valueA, valueB;
        
        switch(sortBy) {
            case 'score':
                valueA = parseFloat(a.dataset.score) || 0;
                valueB = parseFloat(b.dataset.score) || 0;
                return valueB - valueA;
            case 'members':
                valueA = parseInt(a.dataset.members) || 0;
                valueB = parseInt(b.dataset.members) || 0;
                return valueB - valueA;
            case 'favorites':
                valueA = parseInt(a.dataset.favorites) || 0;
                valueB = parseInt(b.dataset.favorites) || 0;
                return valueB - valueA;
            case 'title':
                valueA = a.dataset.title.toLowerCase();
                valueB = b.dataset.title.toLowerCase();
                return valueA.localeCompare(valueB);
            default:
                return 0;
        }
    });
    
    // Re-append sorted cards
    cards.forEach(card => grid.appendChild(card));
}

// Add to library
function addToLibrary(novelId) {
    @auth
        fetch('/library/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                novel_api_id: novelId,
                status: 'want_to_read'
            })
        })
        .then(response => response.json())
        .then(data => {
            alert('Added to your library!');
        })
        .catch(error => {
            console.error('Error:', error);
        });
    @else
        window.location.href = '/login';
    @endauth
}

// Toggle favorite
function toggleFavorite(novelId) {
    @auth
        alert('Favorite feature coming soon!');
    @else
        window.location.href = '/login';
    @endauth
}

// Load more function (for pagination)
function loadMore() {
    alert('Load more functionality coming soon!');
}
</script>
@endsection