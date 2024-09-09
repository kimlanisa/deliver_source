<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ComplaintsManual extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public static function generateNomorTransaksi()
    {
        $lastTransaction = self::latest('id')->first();

        if (!$lastTransaction) {
            $nomorTransaksi = 'KP-0001';
        } else {
            $lastNumber = (int)substr($lastTransaction->no_trx, -3);
            $nextNumber = $lastNumber + 1;
            $nomorTransaksi = 'KP-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        }

        return $nomorTransaksi;
    }

    public function retur()
    {
        return $this->belongsTo(Retur::class, 'no_pesanan', 'no_pesanan');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function processBy()
    {
        return $this->belongsTo(User::class, 'process_by', 'id');
    }

    public function scopeFilter($query, $filter)
    {
        return $query->when($filter['type'] ?? false, function ($query) use ($filter) {
            switch($filter['type']) {
                case 'now':
                    return $query->whereDate('complaints_manuals.created_at', today());
                    break;
                case 'yesterday':
                    return $query->whereDate('complaints_manuals.created_at', today()->subDays(1));
                    break;
                case 'lastWeek':
                    return $query->whereBetween('complaints_manuals.created_at', [today()->subWeek(), today()]);
                    break;
                case '30day':
                    $tanggalAwal = Carbon::now()->subMonth();
                    $tanggalAkhir = Carbon::now();
                    return $query->whereDate('complaints_manuals.created_at', '>=', $tanggalAwal)
                                ->whereDate('complaints_manuals.created_at', '<=', $tanggalAkhir);
                    break;
                case 'range':
                    $date_range = explode(' to ', $filter['range_date']);
                    return $query->whereDate('complaints_manuals.created_at', '>=', $date_range[0])->whereDate('complaints_manuals.created_at', '<=', $date_range[1] ?? $date_range[0]);
                    break;
            }
        })->when($filter['status'] ?? false, function ($query) use ($filter) {
            return $query->where('status', $filter['status'] == 'on_process' ? 1 : 2);
        })->when($filter['keyword'] ?? false, function ($query) use ($filter) {
            return $query->where('no_trx', 'like', '%'.$filter['keyword'].'%')
                        ->orWhere('date_time', 'like', '%'.$filter['keyword'].'%')
                        ->orWhere('no_pesanan', 'like', '%'.$filter['keyword'].'%')
                        ->orWhere('customer', 'like', '%'.$filter['keyword'].'%')
                        ->orWhere('no_whatsapp', 'like', '%'.$filter['keyword'].'%')
                        ->orWhere('no_resi', 'like', '%'.$filter['keyword'].'%')
                        ->orWhere('keterangan', 'like', '%'.$filter['keyword'].'%')
                        ->orWhere('solution', 'like', '%'.$filter['keyword'].'%')
                        ->orWhereHas('shop', function ($query) use ($filter) {
                            $query->where('name', 'like', '%'.$filter['keyword'].'%');
                        })->orWhereHas('createdBy', function ($query) use ($filter) {
                            $query->where('name', 'like', '%'.$filter['keyword'].'%');
                        });
        })->when($filter['shop_id'] ?? false, function ($query) use ($filter) {
            return $query->where('shop_id', $filter['shop_id']);
        });
    }
}
