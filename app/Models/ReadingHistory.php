<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingHistory extends Model
{
    protected $fillable = [
        'user_id',
        'novel_api_id',
        'last_chapter_read',
        'last_read_at'
    ];
    
    protected $casts = [
        'last_read_at' => 'datetime'
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}