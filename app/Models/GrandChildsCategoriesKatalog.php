<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrandChildsCategoriesKatalog extends Model
{
    use HasFactory;

    protected $table = 'grand_childs_categories_katalog';
    protected $fillable = ['name', 'description', 'thumbnail', 'childs_id'];

    public function parent()
    {
        return $this->belongsTo(ParentsCategoriesKatalog::class, 'parents_id');
    }

    public function child()
    {
        return $this->belongsTo(ChildsCategoriesKatalog::class, 'childs_id');
    }

    // public function photo()
    // {
    //     return $this->hasMany(PhotoKatalog::class, 'grand_childs_id');
    // }
}