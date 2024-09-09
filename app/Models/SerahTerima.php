<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class SerahTerima extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_serah_terima',
        'jml_paket',
        'expedisi_id',
        'user_id',
    ];

    public function SerahTerimaDetails()
    {
        return $this->hasMany(SerahTerimaDetail::class, 'serah_terima_id', 'id');
    }

    public function Expedisi()
    {
        return $this->belongsTo(Expedisi::class, 'expedisi_id', 'id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function scopeFilter($query, $request)
    {
        Log::info($request->all());
        $filter = $request->filter;
        $query->where('deleted_at', 0)
        ->whereHas('SerahTerimaDetails', function ($query) use ($filter, $request) {
            $query->where('deleted_at', 0);
            switch($filter['type'] ?? $request->type ?? false) {
                case 'now':
                    return $query->whereDate('created_at', today());
                    break;
                case 'yesterday':
                    return $query->whereDate('created_at', today()->subDays(1));
                    break;
                case 'lastWeek':
                case 'week':
                case '7':
                    return $query->whereBetween('created_at', [today()->subWeek(), today()]);
                    break;
                case '30day':
                case '30':
                    $tanggalAwal = Carbon::now()->subMonth();
                    $tanggalAkhir = Carbon::now();
                    return $query->whereDate('created_at', '>=', $tanggalAwal)
                                ->whereDate('created_at', '<=', $tanggalAkhir);
                    break;
                case 'range':
                    $date_range = explode(' to ', $filter['range_date'] ?? $request->dateStr ?? '');
                    return $query->whereDate('created_at', '>=', $date_range[0])->whereDate('created_at', '<=', $date_range[1] ?? $date_range[0]);
                    break;
            }

            $keywordSearch = $request->keyword;
            $searchBy = $request->searchBy;
            if ($searchBy == "3") $query->where("no_resi", "like", "%" . $keywordSearch . "%");
        });

        $keywordSearch = $request->keyword;
        $searchBy = $request->searchBy;
        if($keywordSearch) {
            if ($searchBy == "1") $query->where("no_tanda_terima", "like", "%" . $keywordSearch . "%");
            if ($searchBy == "2") $query->whereHas('Expedisi', function ($query) use ($keywordSearch) {
                $query->where("expedisi", "like", "%" . $keywordSearch . "%");
            });

            if(!$searchBy) {
                $query->where(DB::raw("LOWER(no_tanda_terima)"), "like", "%" . strtolower($keywordSearch) . "%")
                        ->orWhereHas('Expedisi', function ($query) use ($keywordSearch) {
                            $query->where(DB::raw("LOWER(expedisi)"), "like", "%" . strtolower($keywordSearch) . "%");
                        })
                        ->orWhere(DB::raw("LOWER(no_resi)"), "like", "%" . strtolower($keywordSearch) . "%");

            }
        }
    }
}
