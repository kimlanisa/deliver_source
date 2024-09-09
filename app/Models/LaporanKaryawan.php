<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaporanKaryawan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function laporanKaryawanDetail()
    {
        return $this->hasMany(LaporanKaryawanDetail::class);
    }

    public static function generateNomorTransaksi()
    {
        $lastTransaction = self::latest('id')->first();

        if (!$lastTransaction) {
            $nomorTransaksi = 'REMP-0001';
        } else {
            $lastNumber = (int)substr($lastTransaction->no_laporan, -3);
            $nextNumber = $lastNumber + 1;
            $nomorTransaksi = 'REMP-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        }

        return $nomorTransaksi;
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function picReport()
    {
        return $this->belongsTo(PicReport::class, 'pic_id');
    }

    public function scopeFilter($query, $filters)
    {
        $query->when($filters['keyword'] ?? null, function ($query, $search) {
            return $query
                        ->where('no_laporan', 'like', '%' . $search . '%')
                        ->orWhere('created_by.name', 'like', '%' . $search . '%')
                        ->orWhere('date', 'like', '%' . $search . '%');
        })->when($filters['type'] ?? false, function ($query) use ($filters) {
            switch($filters['type']) {
                case 'now':
                    return $query->whereDate('laporan_karyawans.created_at', today());
                    break;
                case 'yesterday':
                    return $query->whereDate('laporan_karyawans.created_at', today()->subDays(1));
                    break;
                case 'lastWeek':
                    return $query->whereBetween('laporan_karyawans.created_at', [today()->subWeek(), today()]);
                    break;
                case '30day':
                    $tanggalAwal = Carbon::now()->subMonth();
                    $tanggalAkhir = Carbon::now();
                    return $query->whereDate('laporan_karyawans.created_at', '>=', $tanggalAwal)
                                ->whereDate('laporan_karyawans.created_at', '<=', $tanggalAkhir);
                    break;
                case 'range':
                    $date_range = explode(' to ', $filters['range_date']);
                    return $query->whereDate('laporan_karyawans.created_at', '>=', $date_range[0])->whereDate('laporan_karyawans.created_at', '<=', $date_range[1] ?? $date_range[0]);
                    break;
            }
        })->when($filters['created_by_id'] ?? false, function ($query) use ($filters) {
            return $query->where('laporan_karyawans.created_by_id', $filters['created_by_id']);
        });
    }
}
