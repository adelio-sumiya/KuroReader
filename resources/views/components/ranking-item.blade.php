@props([
  'item' => null,
  'rank' => null,
  'cover' => null,
  'title' => null,
  'genre' => null,
  'rating' => null,
])

@php
  $cover = data_get($item, 'cover', $cover ?? 'https://via.placeholder.com/60');
  $title = data_get($item, 'title', $title ?? 'Judul');
  $genre = data_get($item, 'genre', $genre ?? 'Genre');
  $rating = data_get($item, 'rating', default: $rating ?? 0);
@endphp

<div class="flex items-center gap-3 bg-gray-800 rounded-lg p-3 shadow">
    <span class="font-bold text-blue-400 text-lg">{{ $rank }}</span>
    <img src="{{ $cover }}" class="w-12 h-12 rounded" />
    <div class="flex-1">
        <h3 class="font-semibold text-gray-100">{{ $title }}</h3>
        <p class="text-xs text-gray-400">{{ $genre }}</p>
    </div>
    <span class="text-sm font-semibold text-yellow-400">{{ $rating }}</span>
</div>
