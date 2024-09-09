<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildsCategoriesKatalog extends Model
{
    use HasFactory;

    protected $table = 'childs_categories_katalog';
    protected $fillable = ['name', 'description', 'thumbnail', 'parents_id'];

    public function child()
    {
        return $this->belongsTo(ChildsCategoriesKatalog::class, 'childs_id');
    }

    public function grand_childs()
    {
        return $this->hasMany(GrandChildsCategoriesKatalog::class, 'childs_id');
    }

    public function parent()
    {
        return $this->belongsTo(ParentsCategoriesKatalog::class, 'parents_id');
    }

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