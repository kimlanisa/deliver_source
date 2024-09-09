<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SerahTerimaDetail extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'serah_terima_details';

    public function serahTerima()
    {
        return $this->belongsTo(SerahTerima::class, 'serah_terima_id', 'id');
    }
}
