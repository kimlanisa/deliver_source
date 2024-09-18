<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilePhoto extends Model
{
    use HasFactory;

    protected $table = 'file_photo';
    protected $fillable = ['photo_id', 'file_name', 'file_path'];

    public function photo()
    {
        return $this->belongsTo(PhotoKatalog::class, 'photo_id');
    }

    public function getFileNameAttribute($value)
    {
        return asset('uploads/file/photos/' . $value);
    }

    public function getFilePathAttribute($value)
    {
        return asset('uploads/file/photos/' . $value);
    }
}