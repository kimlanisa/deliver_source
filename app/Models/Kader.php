<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kader extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'negara',
        'jenis_kelamin',
        'tempat_lahir',
        'tgl_lahir',
        'golongan_darah',
        'agama',
        'no_ktp',
        'no_telp',
        'pendidikan_terakhir',
        'provinsi_id',
        'kota_id',
        'kecamatan_id',
        'desa',
        'rt',
        'rw',
        'alamat',
        'foto_profile',
        'foto_ktp',
        'referal_code',

    ];
}
