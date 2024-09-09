<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoKatalog extends Model
{
    use HasFactory;

    protected $table = 'photo_katalog';
    protected $fillable = ['parents_id', 'photo'];

    public function parent()
    {
        return $this->belongsTo(ParentsCategoriesKatalog::class, 'parents_id');
    }

    public function childs()
    {
        return $this->belongsTo(ChildsCategoriesKatalog::class, 'childs_id');
    }

    public function getPhotoAttribute($value)
    {
        return asset($value); 
    }

}