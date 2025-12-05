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

        @if($chapter->content)
            <div id="chapter-content" style="line-height: 1.8; font-size: 1.02rem; margin-top: 1rem; max-width: 800px; margin-left: auto; margin-right: auto; padding: 2rem;">
                {!! $chapter->content !!}
            </div>
        @else
            <div style="padding: 2rem; text-align: center; color: #666;">
                <p>No content available for this chapter.</p>
            </div>
        @endif
    </div>
</div>

<style>
#chapter-content {
    text-align: center;
}

#chapter-content p,
#chapter-content div {
    text-align: center;
}

#chapter-content .pdf-text,
#chapter-content .epub-content,
#chapter-content .pdf-page {
    text-align: center;
}

#chapter-content img {
    display: block;
    margin: 1rem auto;
    max-width: 100%;
    height: auto;
}

#chapter-content .pdf-image {
    text-align: center;
    margin: 1.5rem 0;
}
</style>
@endsection


