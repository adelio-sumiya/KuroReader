@extends('layouts.app')

@section('title', 'Admin - Manage Chapters')

@section('content')
<div class="container">
    <h1 style="margin-bottom: 1rem;">Admin: Manage Chapters</h1>

    <div class="card" style="margin-bottom: 1.5rem;">
        <div style="display: flex; gap: 1rem;">
            <div style="width: 200px;">
                <img src="{{ $novel['images']['jpg']['large_image_url'] ?? '' }}"
                     alt="{{ $novel['title'] ?? 'Novel' }}"
                     style="width: 100%; border-radius: 8px;">
            </div>
            <div>
                <h2 style="margin-top: 0;">{{ $novel['title'] ?? 'Unknown Title' }}</h2>
                <p style="color: #666;">
                    MAL ID: {{ $apiId }}
                </p>
            </div>
        </div>
    </div>

    @if (session('status'))
        <div class="card" style="border-left: 4px solid #16a34a; color: #166534; background: #f0fdf4;">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid" style="grid-template-columns: 1.2fr 1fr; gap: 1.5rem; margin-top: 1.5rem;">
        <!-- Existing chapters -->
        <div class="card">
            <h2 style="margin-bottom: 0.75rem;">Existing Chapters</h2>

            @if($chapters->isEmpty())
                <p style="color: #666;">No chapters uploaded yet.</p>
            @else
                <div style="max-height: 400px; overflow-y: auto; border: 1px solid #eee; border-radius: 4px;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                        <thead>
                            <tr style="background: #f9fafb;">
                                <th style="padding: 0.5rem; text-align: left;">#</th>
                                <th style="padding: 0.5rem; text-align: left;">Title</th>
                                <th style="padding: 0.5rem; text-align: left;">Source</th>
                                <th style="padding: 0.5rem; text-align: right;">Open</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($chapters as $chapter)
                                <tr style="border-top: 1px solid #eee;">
                                    <td style="padding: 0.5rem;">{{ $chapter->chapter_number }}</td>
                                    <td style="padding: 0.5rem;">{{ $chapter->title }}</td>
                                    <td style="padding: 0.5rem; color: #666;">
                                        @php
                                            $sources = [];
                                            if ($chapter->pdf_path) {
                                                $sources[] = 'PDF';
                                            }
                                            if ($chapter->epub_path) {
                                                $sources[] = 'EPUB';
                                            }
                                            if (! $sources) {
                                                $sources[] = 'Text';
                                            }
                                        @endphp
                                        {{ implode(' + ', $sources) }}
                                    </td>
                                    <td style="padding: 0.5rem; text-align: right;">
                                        <a href="{{ route('chapters.show', $chapter) }}" class="btn" style="padding: 0.25rem 0.75rem; font-size: 0.85rem;">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- New chapter form -->
        <div class="card">
            <h2 style="margin-bottom: 0.75rem;">Add / Update Chapter</h2>

            <form action="{{ route('admin.chapters.store', $apiId) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group" style="margin-bottom: 0.75rem;">
                    <label>Chapter Number (leave empty for next)</label>
                    <input type="number"
                           name="chapter_number"
                           min="1"
                           class="form-control"
                           value="{{ old('chapter_number') }}">
                    @error('chapter_number')
                        <div style="color: #b91c1c; font-size: 0.85rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 0.75rem;">
                    <label>Title</label>
                    <input type="text"
                           name="title"
                           class="form-control"
                           value="{{ old('title') }}"
                           required>
                    @error('title')
                        <div style="color: #b91c1c; font-size: 0.85rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 0.75rem;">
                    <label>Text Content (optional)</label>
                    <textarea name="content"
                              rows="6"
                              class="form-control"
                              placeholder="Paste or write chapter content here...">{{ old('content') }}</textarea>
                    @error('content')
                        <div style="color: #b91c1c; font-size: 0.85rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 0.75rem;">
                    <label>Or Upload PDF / EPUB (optional)</label>
                    <input type="file"
                           name="chapter_pdf"
                           accept=".pdf,.epub,application/pdf,application/epub+zip">
                    <p style="font-size: 0.85rem; color: #666; margin-top: 0.25rem;">
                        If you upload a PDF, it will be viewable inline (with images) and optionally converted to text. EPUB files are stored and linked for reading or download.
                    </p>
                    @error('chapter_pdf')
                        <div style="color: #b91c1c; font-size: 0.85rem;">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn">
                    Save Chapter
                </button>
            </form>
        </div>
    </div>
</div>
@endsection


