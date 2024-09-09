<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildsCategoriesKatalog extends Model
{
    use HasFactory;

    protected $table = 'childs_categories_katalog';
    protected $fillable = ['name', 'description', 'thumbnail', 'childs_id'];

    public function child()
    {
        return $this->belongsTo(ChildsCategoriesKatalog::class, 'childs_id');
    }

    public function grand_childs()
    {
        return $this->hasMany(GrandChildsCategoriesKatalog::class, 'childs_id');
    }
    

    // public function photo()
    // {
    //     return $this->hasManyThrough(PhotoKatalog::class, GrandChildsCategoriesKatalog::class, 'parents_id', 'grand_childs_id', 'id', 'id');
    // }

    public function photos()
    {
        return $this->hasMany(PhotoKatalog::class, 'childs_id');
    }

    public function getThumbnailAttribute($value)
    {
        // return url('storage/' . $value);
        return asset($value);
    }
}