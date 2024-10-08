<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoKatalog extends Model
{
    use HasFactory;

    protected $table = 'photo_katalog';
    protected $fillable = ['name', 'description', 'parents_id', 'childs_id', 'grand_childs_id', 'link_url'];

    public function parents()
    {
        return $this->belongsTo(ParentsCategoriesKatalog::class, 'parents_id');
    }

    public function childs()
    {
        return $this->belongsTo(ChildsCategoriesKatalog::class, 'childs_id');
    }

    public function grand_childs()
    {
        return $this->belongsTo(GrandChildsCategoriesKatalog::class, 'grand_childs_id');
    }

    public function getPhotoAttribute($value)
    {
        return asset($value);
    }

    public function files()
    {
        return $this->hasMany(FilePhoto::class, 'photo_id');
    }

    public function variasi()
    {
        return $this->hasMany(Variasi::class, 'photo_id');
    }

}