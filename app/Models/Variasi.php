<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variasi extends Model
{
    use HasFactory;

    protected $table = 'variasi';
    protected $fillable = ['photo_id', 'name'];

    public function photo()
    {
        return $this->belongsTo(PhotoKatalog::class, 'photo_id');
    }
    
}