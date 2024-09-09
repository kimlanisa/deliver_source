<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentsCategoriesKatalog extends Model
{
    use HasFactory;

    protected $table = 'parents_categories_katalog';
    protected $fillable = ['name', 'description', 'thumbnail'];

    public function childs()
    {
        return $this->hasMany(ChildsCategoriesKatalog::class, 'parents_id', 'id');
    }

    // public function menu()
    // {
    //     return $this->belongsTo(MenuKatalog::class, 'menu_id');
    // }

    public function grandChilds()
    {
        return $this->hasManyThrough(GrandChildsCategoriesKatalog::class, ChildsCategoriesKatalog::class, 'parents_id', 'childs_id', 'id', 'id');
    }

    public function photos()
    {
        return $this->hasMany(PhotoKatalog::class, 'parents_id');
    }

    public function getThumbnailAttribute($value)
    {
        // return url('storage/' . $value);
        return asset($value);
    }
}