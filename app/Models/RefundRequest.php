<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RefundRequest extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function accRefundBy()
    {
        return $this->belongsTo(User::class, 'acc_refund_by', 'id');
    }

    public static function generateNomorTransaksi()
    {
        $lastTransaction = self::latest('id')->first();

        if (!$lastTransaction) {
            $nomorTransaksi = 'RRF-0001';
        } else {
            $lastNumber = (int)substr($lastTransaction->no_trx, -3);
            $nextNumber = $lastNumber + 1;
            $nomorTransaksi = 'RRF-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        }

        return $nomorTransaksi;
    }

    public function scopeFilter($query, $filter)
    {
        return $query->when($filter['type'] ?? false, function ($query) use ($filter) {
            switch($filter['type']) {
                case 'now':
                    return $query->whereDate('refund_requests.created_at', today());
                    break;
                case 'yesterday':
                    return $query->whereDate('refund_requests.created_at', today()->subDays(1));
                    break;
                case 'lastWeek':
                    return $query->whereBetween('refund_requests.created_at', [today()->subWeek(), today()]);
                    break;
                case '30day':
                    $tanggalAwal = Carbon::now()->subMonth();
                    $tanggalAkhir = Carbon::now();
                    return $query->whereDate('refund_requests.created_at', '>=', $tanggalAwal)
                                ->whereDate('refund_requests.created_at', '<=', $tanggalAkhir);
                    break;
                case 'range':
                    $date_range = explode(' to ', $filter['range_date']);
                    return $query->whereDate('refund_requests.created_at', '>=', $date_range[0])->whereDate('refund_requests.created_at', '<=', $date_range[1] ?? $date_range[0]);
                    break;
            }
        })->when($filter['keyword'] ?? false, function ($query) use ($filter) {
            return $query->where('no_trx', 'like', "%{$filter['keyword']}%")
                        ->orWhere('date', 'like', "%{$filter['keyword']}%")
                        ->orWhere('no_pesanan', 'like', "%{$filter['keyword']}%")
                        ->orWhere('customer', 'like', "%{$filter['keyword']}%")
                        ->orWhere('alasan_refund', 'like', "%{$filter['keyword']}%")
                        ->orWhere('nominal_refund', 'like', "%{$filter['keyword']}%")
                        ->orWhere('date_acc_refund', 'like', "%{$filter['keyword']}%")
                        ->orWhere('no_rekening', 'like', "%{$filter['keyword']}%")
                        ->orWhere('nama_bank', 'like', "%{$filter['keyword']}%")
                        ->orWhere('nama_pemilik_rekening', 'like', "%{$filter['keyword']}%")
                        ->orWhere('bukti_refund', 'like', "%{$filter['keyword']}%");
        })->when($filter['status'] ?? false, function ($query) use ($filter) {
            $status = 1;
            switch($filter['status']) {
                case 'on_process':
                    $status = 1;
                    break;
                case 'tandai_selesai':
                    $status = 2;
                    break;
                case 'done_process':
                    $status = 3;
                    break;
            }
            return $query->where('status', $status);
        })->when($filter['shop_id'] ?? false, function ($query) use ($filter) {
            return $query->where('shop_id', $filter['shop_id']);
        });
    }
}
