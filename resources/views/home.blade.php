@extends('layouts.app')

@section('content')

{{-- SLIDER --}}
<x-slider :items="$weeklySlider" />

{{-- FEATURED --}}
<div class="max-w-7xl mx-auto px-4 mt-10">
    <h2 class="text-xl font-bold mb-4">Weekly Featured</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
        @foreach ($featuredNovels as $novel)
            <x-novel-card :novel="$novel" />
        @endforeach

    </div>
</div>

{{-- RANKINGS --}}
<div class="max-w-7xl mx-auto px-4 mt-10 mb-10">
   {{-- @foreach($rankings['Power Ranking'] as $i => $item)
    <x-ranking-item 
        :rank="$i + 1"
        :cover="$item['cover']"
        :title="$item['title']"
        :genre="$item['genre']"
        :rating="$item['rating']"
    />
@endforeach --}}
    @foreach($rankings['Power Ranking'] as $i => $item)
        <x-ranking-item :rank="$i + 1" :item="$item" />
    @endforeach

</div>

@endsection
