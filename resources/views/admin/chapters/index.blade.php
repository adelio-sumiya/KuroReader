@extends('layouts.app')

@section('title', 'Admin - Manage Chapters')

@section('content')
<div class="container">
    <h1 style="margin-bottom: 1rem;">Admin: Manage Chapters</h1>

    <div class="card" style="margin-bottom: 1.5rem;">
        <div style="display: flex; gap: 1rem;">f
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
                                <th style="padding: 0.5rem; text-align: right;">Actions</th>
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
                                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                            <a href="{{ route('chapters.show', $chapter) }}" class="btn" style="padding: 0.25rem 0.75rem; font-size: 0.85rem;">
                                                View
                                            </a>
                                            <a href="{{ route('admin.chapters.edit', [$apiId, $chapter]) }}" class="btn" style="padding: 0.25rem 0.75rem; font-size: 0.85rem; background: #3b82f6;">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.chapters.destroy', [$apiId, $chapter]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this chapter?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn" style="padding: 0.25rem 0.75rem; font-size: 0.85rem; background: #dc2626;">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
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
            <h2 style="margin-bottom: 0.75rem;">
                @if(isset($editingChapter))
                    Edit Chapter #{{ $editingChapter->chapter_number }}
                @else
                    Add / Update Chapter
                @endif
            </h2>

            @if(isset($editingChapter))
                <div style="margin-bottom: 1rem; padding: 0.75rem; background: #eff6ff; border-left: 4px solid #3b82f6; border-radius: 4px;">
                    <p style="margin: 0; color: #1e40af;">
                        Editing: <strong>{{ $editingChapter->title }}</strong>
                    </p>
                </div>
            @endif

            <form action="{{ route('admin.chapters.store', $apiId) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group" style="margin-bottom: 0.75rem;">
                    <label>Chapter Number (leave empty for next)</label>
                    <input type="number"
                           name="chapter_number"
                           min="1"
                           class="form-control"
                           value="{{ old('chapter_number', $editingChapter->chapter_number ?? '') }}"
                           @if(isset($editingChapter)) readonly style="background: #f3f4f6;" @endif>
                    @error('chapter_number')
                        <div style="color: #b91c1c; font-size: 0.85rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 0.75rem;">
                    <label>Title</label>
                    <input type="text"
                           name="title"
                           class="form-control"
                           value="{{ old('title', $editingChapter->title ?? '') }}"
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
                              placeholder="Paste or write chapter content here...">{{ old('content', $editingChapter->content ?? '') }}</textarea>
                    @error('content')
                        <div style="color: #b91c1c; font-size: 0.85rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 0.75rem;">
                    <label>Or Upload PDF / EPUB (optional)</label>
                    @if(isset($editingChapter))
                        <div style="margin-bottom: 0.5rem; padding: 0.5rem; background: #f9fafb; border-radius: 4px; font-size: 0.85rem;">
                            @if($editingChapter->pdf_path)
                                <p style="margin: 0;">Current: PDF file uploaded</p>
                            @elseif($editingChapter->epub_path)
                                <p style="margin: 0;">Current: EPUB file uploaded</p>
                            @else
                                <p style="margin: 0;">No file currently uploaded</p>
                            @endif
                            <p style="margin: 0.25rem 0 0; color: #666;">Upload a new file to replace the existing one.</p>
                        </div>
                    @endif
                    <input type="file"
                           name="chapter_pdf"
                           accept=".pdf,.epub,application/pdf,application/epub+zip">
                    <p style="font-size: 0.85rem; color: #666; margin-top: 0.25rem;">
                        PDF files will be converted to HTML with text and images extracted. EPUB files will be converted to HTML for reading directly on the website. Content will be merged with any text you've entered above.
                    </p>
                    @error('chapter_pdf')
                        <div style="color: #b91c1c; font-size: 0.85rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn">
                        {{ isset($editingChapter) ? 'Update Chapter' : 'Save Chapter' }}
                    </button>
                    @if(isset($editingChapter))
                        <a href="{{ route('admin.chapters.index', $apiId) }}" class="btn" style="background: #6b7280;">
                            Cancel
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


