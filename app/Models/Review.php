<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'novel_api_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
        'novel_api_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}