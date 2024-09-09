<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomMenu extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $appends = ['permission_name'];

    public function getPermissionNameAttribute()
    {
        return preg_replace('/[^a-zA-Z0-9]/', ' ', $this->name);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function customMenuLabels()
    {
        return $this->hasMany(CustomMenuLabel::class);
    }

    public function scopeFilter($query, array $filters)
    {
        // $query->when($filters['search'] ?? null, function ($query, $search) {
        //     $query->where('name', 'like', '%'.$search.'%');
        // });
    }
}
