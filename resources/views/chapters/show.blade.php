@extends('layouts.app')

@section('title', ($novel['title'] ?? 'Novel') . ' - Chapter ' . $chapter->chapter_number)

@section('content')
<div class="container">
    <div style="margin-bottom: 1rem;">
        <a href="{{ route('novels.show', $chapter->novel_api_id) }}" class="btn">
            ← Back to Novel
        </a>
    </div>

    <div class="card">
        <h1 style="margin-bottom: 0.25rem;">
            {{ $novel['title'] ?? 'Unknown Title' }}
        </h1>
        <p style="color: #666; margin-bottom: 1rem;">
            Chapter {{ $chapter->chapter_number }} &mdash; {{ $chapter->title }}
        </p>

        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
            <div>
                @if($previousChapter)
                    <a href="{{ route('chapters.show', $previousChapter) }}" class="btn">
                        ← Previous
                    </a>
                @endif
            </div>
            <div>
                @if($nextChapter)
                    <a href="{{ route('chapters.show', $nextChapter) }}" class="btn">
                        Next →
                    </a>
                @endif
            </div>
        </div>

        @if($chapter->pdf_path)
            <div style="margin-bottom: 1rem;">
                <h3 style="margin-bottom: 0.5rem;">PDF Viewer</h3>
                <div style="border: 1px solid #ddd; border-radius: 4px; overflow: hidden; height: 600px;">
                    <iframe
                        src="{{ asset('storage/' . $chapter->pdf_path) }}"
                        style="width: 100%; height: 100%; border: none;"
                    ></iframe>
                </div>
            </div>
        @endif

        @if($chapter->epub_path)
            <div style="margin-bottom: 1rem;">
                <h3 style="margin-bottom: 0.5rem;">EPUB File</h3>
                <a href="{{ asset('storage/' . $chapter->epub_path) }}" class="btn" target="_blank">
                    Open / Download EPUB
                </a>
            </div>
        @endif

        @if($chapter->content)
            <div id="chapter-content" style="line-height: 1.8; font-size: 1.02rem; margin-top: 1rem;">
                {!! $chapter->content !!}
            </div>
        @endif
    </div>
</div>
@endsection


