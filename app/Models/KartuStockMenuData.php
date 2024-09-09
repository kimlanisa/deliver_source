<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KartuStockMenuData extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['type_name'];

    public function getTypeNameAttribute()
    {
        return intval($this->type) == 1 ? 'Masuk' : 'Keluar';
    }

    public function scopeFilter()
    {

    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
