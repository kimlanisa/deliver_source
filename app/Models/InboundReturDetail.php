<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InboundReturDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function ekspedisi()
    {
        return $this->belongsTo(Expedisi::class, 'expedisi_id');
    }

    public function retur()
    {
        return $this->hasOne(Retur::class, 'inbound_retur_id', 'id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function manualComplaint()
    {
        return $this->hasOne(ComplaintsManual::class, 'inbound_retur_id', 'id');
    }

    public function scopeFilter($query, $filter)
    {
        return $query->when($filter['type'] ?? false, function ($query) use ($filter) {
            switch($filter['type']) {
                case 'now':
                    return $query->whereDate('inbound_retur_details.created_at', today());
                    break;
                case 'yesterday':
                    return $query->whereDate('inbound_retur_details.created_at', today()->subDays(1));
                    break;
                case 'lastWeek':
                    return $query->whereBetween('inbound_retur_details.created_at', [today()->subWeek(), today()]);
                    break;
                case '30day':
                    $tanggalAwal = Carbon::now()->subMonth();
                    $tanggalAkhir = Carbon::now();
                    return $query->whereDate('inbound_retur_details.created_at', '>=', $tanggalAwal)
                                ->whereDate('inbound_retur_details.created_at', '<=', $tanggalAkhir);
                    break;
                case 'range':
                    $date_range = explode(' to ', $filter['range_date']);
                    return $query->whereDate('inbound_retur_details.created_at', '>=', $date_range[0])->whereDate('inbound_retur_details.created_at', '<=', $date_range[1] ?? $date_range[0]);
                    break;
            }
        })->when($filter['keyword'] ?? false, function ($query) use ($filter) {
            return $query->where('inbound_retur_details.no_resi', 'like', '%'.$filter['keyword'].'%')
                        ->orWhereHas('ekspedisi', function ($query) use ($filter) {
                            $query->where('expedisi', 'like', '%'.$filter['keyword'].'%');
                        });
        })->when($filter['status'] ?? false, function($query) use ($filter) {
            switch($filter['status']) {
                case 'need_process':
                    return $query->whereDoesntHave('retur');
                    break;
                case 'done_process':
                    return $query->whereHas('retur');
                    break;
            }
        });
    }
}
