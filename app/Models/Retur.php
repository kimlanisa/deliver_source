<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Retur extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function inboundReturDetail()
    {
        return $this->belongsTo(InboundReturDetail::class, 'inbound_retur_id', 'id');
    }

    public function complaint()
    {
        return $this->belongsTo(ComplaintsManual::class, 'no_pesanan', 'no_pesanan');
    }

    public static function generateNomorTransaksi()
    {
        $lastTransaction = self::latest('id')->first();

        if (!$lastTransaction) {
            $nomorTransaksi = 'RT-0001';
        } else {
            $lastNumber = (int)substr($lastTransaction->no_trx, -3);
            $nextNumber = $lastNumber + 1;
            $nomorTransaksi = 'RT-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        }

        return $nomorTransaksi;
    }

    public function scopeFilter($query, $filter)
    {
        return $query->when($filter['type'] ?? false, function ($query) use ($filter) {
            switch($filter['type']) {
                case 'now':
                    return $query->whereDate('returs.created_at', today());
                    break;
                case 'yesterday':
                    return $query->whereDate('returs.created_at', today()->subDays(1));
                    break;
                case 'lastWeek':
                    return $query->whereBetween('returs.created_at', [today()->subWeek(), today()]);
                    break;
                case '30day':
                    $tanggalAwal = Carbon::now()->subMonth();
                    $tanggalAkhir = Carbon::now();
                    return $query->whereDate('returs.created_at', '>=', $tanggalAwal)
                                ->whereDate('returs.created_at', '<=', $tanggalAkhir);
                    break;
                case 'range':
                    $date_range = explode(' to ', $filter['range_date']);
                    return $query->whereDate('returs.created_at', '>=', $date_range[0])->whereDate('returs.created_at', '<=', $date_range[1] ?? $date_range[0]);
                    break;
            }
        })->when($filter['keyword'] ?? false, function ($query) use ($filter) {
            return $query->where('no_trx', 'like', '%'.$filter['keyword'].'%')
                        ->orWhere('date', 'like', '%'.$filter['keyword'].'%')
                        ->orWhere('no_pesanan', 'like', '%'.$filter['keyword'].'%')
                        ->orWhere('customer', 'like', '%'.$filter['keyword'].'%')
                        ->orWhere('no_whatsapp', 'like', '%'.$filter['keyword'].'%')
                        ->orWhere('no_resi', 'like', '%'.$filter['keyword'].'%')
                        ->orWhere('sku_jumlah', 'like', '%'.$filter['keyword'].'%')
                        ->orWhere('alasan_retur', 'like', '%'.$filter['keyword'].'%')
                        ->orWhere('status', 'like', '%'.$filter['keyword'].'%')
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
