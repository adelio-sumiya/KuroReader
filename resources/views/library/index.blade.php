@extends('layouts.app')

@section('title', 'My Library')

@section('content')
<div class="container">
    <h1 style="margin-bottom: 1rem;">📚 My Library</h1>

    <!-- Filter Tabs -->
    <div style="margin-bottom: 2rem;">
        <a href="/library?status=all" class="btn {{ $status == 'all' ? '' : 'btn-secondary' }}">
            All ({{ $counts['all'] }})
        </a>
        <a href="/library?status=want_to_read" class="btn {{ $status == 'want_to_read' ? '' : 'btn-secondary' }}">
            Want to Read ({{ $counts['want_to_read'] }})
        </a>
        <a href="/library?status=reading" class="btn {{ $status == 'reading' ? '' : 'btn-secondary' }}">
            Reading ({{ $counts['reading'] }})
        </a>
        <a href="/library?status=completed" class="btn {{ $status == 'completed' ? '' : 'btn-secondary' }}">
            Completed ({{ $counts['completed'] }})
        </a>
    </div>

    <!-- Library Items -->
    @forelse($enrichedLibraries as $library)
        @if($library->novel_data)
            <div class="card">
                <div style="display: grid; grid-template-columns: 120px 1fr auto; gap: 1rem; align-items: start;">
                    <!-- Cover -->
                    <img src="{{ $library->novel_data['images']['jpg']['image_url'] }}" 
                         style="width: 100%; border-radius: 4px;"
                         alt="{{ $library->novel_data['title'] }}">
                    
                    <!-- Info -->
                    <div>
                        <h3>
                            <a href="/novels/{{ $library->novel_api_id }}" style="color: inherit; text-decoration: none;">
                                {{ $library->novel_data['title'] }}
                            </a>
                        </h3>
                        <p style="color: #666; font-size: 0.9rem; margin: 0.5rem 0;">
                            ⭐ {{ $library->novel_data['score'] ?? 'N/A' }} | 
                            📚 {{ $library->novel_data['chapters'] ?? '?' }} chapters
                        </p>
                        <p style="font-size: 0.9rem;">
                            <strong>Status:</strong> 
                            <span style="text-transform: capitalize;">{{ str_replace('_', ' ', $library->status) }}</span>
                        </p>
                        <small style="color: #999;">Added {{ $library->created_at->diffForHumans() }}</small>
                    </div>

                    <!-- Actions -->
                    <div>
                        <form action="/library/{{ $library->id }}/status" method="POST" style="margin-bottom: 0.5rem;">
                            @csrf
                            @method('PUT')
                            <select name="status" onchange="this.form.submit()" style="width: auto;">
                                <option value="want_to_read" {{ $library->status == 'want_to_read' ? 'selected' : '' }}>
                                    Want to Read
                                </option>
                                <option value="reading" {{ $library->status == 'reading' ? 'selected' : '' }}>
                                    Reading
                                </option>
                                <option value="completed" {{ $library->status == 'completed' ? 'selected' : '' }}>
                                    Completed
                                </option>
                            </select>
                        </form>

                        <form action="/library/{{ $library->id }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="width: 100%;">Remove</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @empty
        <div class="card">
            <p>Your library is empty. Start adding novels from the <a href="/">homepage</a> or <a href="/novels">search page</a>!</p>
        </div>
    @endforelse
</div>
@endsection