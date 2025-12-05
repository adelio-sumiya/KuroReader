@extends('layouts.app')

@section('title', $novel['title'] ?? 'Novel Detail')

@section('content')
<div class="container">
    <div style="display: grid; grid-template-columns: 250px 1fr; gap: 2rem;">
        <!-- Novel Cover -->
        <div>
            <img src="{{ $novel['images']['jpg']['large_image_url'] }}" 
                 style="width: 100%; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);"
                 alt="{{ $novel['title'] }}">
        </div>

        <!-- Novel Info -->
        <div>
            <h1>{{ $novel['title'] }}</h1>
            <p style="color: #666; margin: 0.5rem 0;">{{ $novel['title_english'] ?? '' }}</p>
            
            @auth
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.chapters.index', $novel['mal_id']) }}">Manage Chapters</a>
                @endif
            @endauth
            
            <div style="margin: 1rem 0;">
                <span style="background: #3498db; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.9rem;">
                    {{ $novel['status'] ?? 'Unknown' }}
                </span>
                <span style="margin-left: 1rem;">⭐ {{ $novel['score'] ?? 'N/A' }}/10</span>
                <span style="margin-left: 1rem;">📚 {{ $novel['chapters'] ?? '?' }} Chapters</span>
            </div>

            <!-- Add to Library (Auth only) -->
            @auth
                <div class="card" style="margin: 1rem 0;">
                    <form action="/library/add" method="POST">
                        @csrf
                        <input type="hidden" name="novel_api_id" value="{{ $novel['mal_id'] }}">
                        
                        <div class="form-group">
                            <label>Add to Library:</label>
                            <select name="status" required>
                                <option value="want_to_read" {{ optional($libraryStatus)->status == 'want_to_read' ? 'selected' : '' }}>
                                    Want to Read
                                </option>
                                <option value="reading" {{ optional($libraryStatus)->status == 'reading' ? 'selected' : '' }}>
                                    Reading
                                </option>
                                <option value="completed" {{ optional($libraryStatus)->status == 'completed' ? 'selected' : '' }}>
                                    Completed
                                </option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn">
                            {{ $libraryStatus ? 'Update Status' : 'Add to Library' }}
                        </button>

                        @if($libraryStatus)
                            <form action="/library/{{ $libraryStatus->id }}" method="POST" style="display: inline; margin-left: 0.5rem;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Remove</button>
                            </form>
                        @endif
                    </form>
                </div>

                <!-- Reading Progress -->
                <div class="card">
                    <form action="/history" method="POST">
                        @csrf
                        <input type="hidden" name="novel_api_id" value="{{ $novel['mal_id'] }}">
                        
                        <div class="form-group">
                            <label>Last Chapter Read:</label>
                            <input type="number" 
                                   name="last_chapter_read" 
                                   min="1" 
                                   value="{{ optional($readingHistory)->last_chapter_read ?? 1 }}"
                                   required>
                        </div>
                        
                        <button type="submit" class="btn">Update Progress</button>
                        
                        @if($readingHistory)
                            <p style="margin-top: 0.5rem; font-size: 0.9rem; color: #666;">
                                Last read: {{ $readingHistory->last_read_at->diffForHumans() }}
                            </p>
                        @endif
                    </form>
                </div>
            @else
                <div class="alert" style="background: #fff3cd; color: #856404; border: 1px solid #ffeeba;">
                    Please <a href="/login">login</a> to add to library and track progress.
                </div>
            @endauth

            <!-- Synopsis -->
            <div class="card">
                <h2>📖 Synopsis</h2>
                <p style="line-height: 1.6;">{{ $novel['synopsis'] ?? 'No synopsis available.' }}</p>
            </div>

             <!-- Uploaded Chapters (Admin-managed) -->
             <div class="card">
                 <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                     <h2>📚 Uploaded Chapters</h2>
                     @auth
                         @if(auth()->user()->is_admin)
                             <a href="{{ route('admin.chapters.index', $novel['mal_id']) }}" class="btn">
                                 Manage Chapters
                             </a>
                         @endif
                     @endauth
                 </div>

                 @if($chapters->isEmpty())
                     <p style="color: #666;">No chapters uploaded yet.</p>
                 @else
                     <div style="max-height: 400px; overflow-y: auto; border: 1px solid #eee; border-radius: 4px;">
                         <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                             <thead>
                                 <tr style="background: #f9fafb;">
                                     <th style="padding: 0.5rem; text-align: left;">#</th>
                                     <th style="padding: 0.5rem; text-align: left;">Title</th>
                                     <th style="padding: 0.5rem; text-align: right;">Read</th>
                                 </tr>
                             </thead>
                             <tbody>
                                 @foreach($chapters as $chapter)
                                     <tr style="border-top: 1px solid #eee;">
                                         <td style="padding: 0.5rem;">{{ $chapter->chapter_number }}</td>
                                         <td style="padding: 0.5rem;">{{ $chapter->title }}</td>
                                         <td style="padding: 0.5rem; text-align: right;">
                                             <a href="{{ route('chapters.show', $chapter) }}" class="btn" style="padding: 0.25rem 0.75rem; font-size: 0.85rem;">
                                                 Read
                                             </a>
                                         </td>
                                     </tr>
                                 @endforeach
                             </tbody>
                         </table>
                     </div>
                 @endif
            </div>

            <!-- Review Section -->
            @auth
                <div class="card">
                    <h2>✍️ Your Review</h2>
                    <form action="{{ $userReview ? '/reviews/'.$userReview->id : '/reviews' }}" method="POST">
                        @csrf
                        @if($userReview)
                            @method('PUT')
                        @else
                            <input type="hidden" name="novel_api_id" value="{{ $novel['mal_id'] }}">
                        @endif
                        
                        <div class="form-group">
                            <label>Rating (1-10):</label>
                            <input type="number" 
                                   name="rating" 
                                   min="1" 
                                   max="10" 
                                   value="{{ optional($userReview)->rating ?? 5 }}"
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label>Comment:</label>
                            <textarea name="comment" rows="4" placeholder="Write your review...">{{ optional($userReview)->comment }}</textarea>
                        </div>
                        
                        <button type="submit" class="btn">
                            {{ $userReview ? 'Update Review' : 'Submit Review' }}
                        </button>

                        @if($userReview)
                            <form action="/reviews/{{ $userReview->id }}" method="POST" style="display: inline; margin-left: 0.5rem;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete Review</button>
                            </form>
                        @endif
                    </form>
                </div>
            @endauth

            <!-- All Reviews -->
            <div class="card">
                <h2>💬 Reviews ({{ $reviews->count() }})</h2>
                @if($averageRating)
                    <p style="font-size: 1.2rem; margin-bottom: 1rem;">
                        Average: ⭐ {{ number_format($averageRating, 1) }}/10
                    </p>
                @endif

                @forelse($reviews as $review)
                    <div style="border-bottom: 1px solid #eee; padding: 1rem 0;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <strong>{{ $review->user->name }}</strong>
                            <span>⭐ {{ $review->rating }}/10</span>
                        </div>
                        @if($review->comment)
                            <p style="color: #666;">{{ $review->comment }}</p>
                        @endif
                        <small style="color: #999;">{{ $review->created_at->diffForHumans() }}</small>
                    </div>
                @empty
                    <p style="color: #666;">No reviews yet. Be the first to review!</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection