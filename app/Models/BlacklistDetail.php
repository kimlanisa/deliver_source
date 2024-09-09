<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlacklistDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function blacklist()
    {
        return $this->belongsTo(Blacklist::class);
    }

    public function ekspedisi()
    {
        return $this->belongsTo(Expedisi::class, 'expedisi_id', 'id');
    }

    public function scopeFilter($query, $filter)
    {
        return $query->when($filter['type'] ?? false, function ($query) use ($filter) {
            switch($filter['type']) {
                case 'now':
                    return $query->whereDate('created_at', today());
                    break;
                case 'yesterday':
                    return $query->whereDate('created_at', today()->subDays(1));
                    break;
                case 'lastWeek':
                    return $query->whereBetween('created_at', [today()->subWeek(), today()]);
                    break;
                case '30day':
                    $tanggalAwal = Carbon::now()->subMonth();
                    $tanggalAkhir = Carbon::now();
                    return $query->whereDate('created_at', '>=', $tanggalAwal)
                                ->whereDate('created_at', '<=', $tanggalAkhir);
                    break;
                case 'range':
                    $date_range = explode(' to ', $filter['range_date']);
                    return $query->whereDate('created_at', '>=', $date_range[0])->whereDate('created_at', '<=', $date_range[1] ?? $date_range[0]);
                    break;
            }
        })->when($filter['keyword'] ?? false, function ($query) use ($filter) {
            return $query->where('no_resi', 'like', '%'.$filter['keyword'].'%')
                        ->orWhereHas('ekspedisi', function ($query) use ($filter) {
                            $query->where('expedisi', 'like', '%'.$filter['keyword'].'%');
                        });
        });
    }
}
