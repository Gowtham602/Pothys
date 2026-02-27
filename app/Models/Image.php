<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
     protected $fillable = [
        'user_id',
        'image_name',
        'file_path',
        'short_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
