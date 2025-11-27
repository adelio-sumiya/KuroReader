@props([
  'novel' => null,
  // backward compatibility: if someone passes title/cover directly
  'title' => null,
  'cover' => null,
  'description' => null,
  'url' => null,
])

@php
  // prefer novel object, fallback ke individual props
  $title = data_get($novel, 'title', $title ?? 'Judul Novel');
  $cover = data_get($novel, 'cover', $cover ?? 'https://via.placeholder.com/300x400');
  $description = data_get($novel, 'description', $description ?? 'Deskripsi novel belum tersedia.');
  $url = data_get($novel, 'url', $url ?? '#');
@endphp

<div class="bg-gray-900 rounded-xl overflow-hidden shadow-lg hover:scale-[1.03] transition-all duration-300 border border-gray-700">
    <a href="{{ $url }}">
        <img src="{{ $cover }}" alt="{{ $title }}" class="w-full h-56 object-cover">
    </a>

    <div class="p-4">
        <a href="{{ $url }}">
            <h3 class="text-lg font-bold text-black line-clamp-2">{{ $title }}</h3>
        </a>

        <p class="text-gray-400 text-sm mt-2 line-clamp-3">{{ $description }}</p>

        <a href="{{ $url }}">
            <button class="w-full mt-4 bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg transition-all">Baca Sekarang</button>
        </a>
    </div>
</div>
