<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLibrary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'novel_api_id',
        'status',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'novel_api_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}