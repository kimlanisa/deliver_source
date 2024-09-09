<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $fillable = [
        'desa_id',
        'nama',
        'r_n',
        'kelas',
        'tgl_lahir',
        'tgl_masuk',
        'status_pribadi_id',
        'status_kondisi',
        'alamat',
        'no_hp',
        'keterangan',
        'user_id',
        'no_index',
        'pj',
    ];
}
