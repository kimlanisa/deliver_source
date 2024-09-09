<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockOpnameRequest extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function processBy()
    {
        return $this->belongsTo(User::class, 'process_by', 'id');
    }

    public static function generateNomorTransaksi()
    {
        $lastTransaction = self::latest('id')->first();

        if (!$lastTransaction) {
            $nomorTransaksi = 'OP-0001';
        } else {
            $lastNumber = (int)substr($lastTransaction->no_trx, -3);
            $nextNumber = $lastNumber + 1;
            $nomorTransaksi = 'OP-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        }

        return $nomorTransaksi;
    }

    public function scopeFilter($query, $filter)
    {
        return $query->when($filter['type'] ?? false, function ($query) use ($filter) {
            switch($filter['type']) {
                case 'now':
                    return $query->whereDate('stock_opname_requests.created_at', today());
                    break;
                case 'yesterday':
                    return $query->whereDate('stock_opname_requests.created_at', today()->subDays(1));
                    break;
                case 'lastWeek':
                    return $query->whereBetween('stock_opname_requests.created_at', [today()->subWeek(), today()]);
                    break;
                case '30day':
                    $tanggalAwal = Carbon::now()->subMonth();
                    $tanggalAkhir = Carbon::now();
                    return $query->whereDate('stock_opname_requests.created_at', '>=', $tanggalAwal)
                                ->whereDate('stock_opname_requests.created_at', '<=', $tanggalAkhir);
                    break;
                case 'range':
                    $date_range = explode(' to ', $filter['range_date']);
                    return $query->whereDate('stock_opname_requests.created_at', '>=', $date_range[0])->whereDate('stock_opname_requests.created_at', '<=', $date_range[1] ?? $date_range[0]);
                    break;
            }
        })->when($filter['keyword'] ?? false, function ($query) use ($filter) {
            return $query->where('no_trx', 'like', '%'.$filter['keyword'].'%')
                        ->orWhere('date', 'like', '%'.$filter['keyword'].'%')
                        ->orWhere('sku', 'like', '%'.$filter['keyword'].'%')
                        ->orWhere('minus', 'like', '%'.$filter['keyword'].'%')
                        ->orWhere('plus', 'like', '%'.$filter['keyword'].'%')
                        ->orWhereHas('user', function ($query) use ($filter) {
                            $query->where('name', 'like', '%'.$filter['keyword'].'%');
                        });
        })->when($filter['status'] ?? false, function ($query) use ($filter) {
            return $query->where('status', $filter['status'] == 'on_process' ? 1 : 2);
        });
    }
}
