@extends('layouts.app')

@section('title', 'Reading History')

@section('content')
<div class="container" style="margin-top: 2rem;">
    <div class="card" style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 1.8rem; margin-bottom: 0.5rem;">Reading History</h1>
        <p style="color: #9aa3ad;">
            Track progres bacaan terakhir kamu. Klik judul untuk membuka halaman novel.
        </p>
    </div>

    @if($histories->isEmpty())
        <div class="card" style="text-align: center;">
            <p style="color:#9aa3ad;">Belum ada data histori. Mulai baca novel untuk melihat progres di sini.</p>
        </div>
    @else
        <div class="card" style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="text-align:left; border-bottom:1px solid rgba(255,255,255,0.08);">
                        <th style="padding:0.75rem;">Novel</th>
                        <th style="padding:0.75rem;">Last Chapter</th>
                        <th style="padding:0.75rem;">Last Read</th>
                        <th style="padding:0.75rem;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($histories as $history)
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.04);">
                            <td style="padding:0.75rem;">
                                <a href="{{ route('novels.show', $history->novel_api_id) }}" style="color:#4ea1ff; text-decoration:none;">
                                    Novel #{{ $history->novel_api_id }}
                                </a>
                            </td>
                            <td style="padding:0.75rem;">Chapter {{ $history->last_chapter_read }}</td>
                            <td style="padding:0.75rem;">
                                {{ optional($history->last_read_at)->diffForHumans() ?? 'Unknown' }}
                            </td>
                            <td style="padding:0.75rem;">
                                <a class="btn" href="{{ route('novels.show', $history->novel_api_id) }}">Continue Reading</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

