<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class LogActivitasController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->filter;
        if ($request->ajax()) {
            $data = DB::table('log_activitis as la')
                ->select('la.*', 'users.name as user', 'st.no_tanda_terima', 'ex.expedisi', 'ex.color', 'ex_new.expedisi as expedisi_new', 'ex_new.color as color_new')
                ->leftJoin('users', 'users.id', '=', 'la.users_id')
                ->leftJoin('serah_terimas as st', 'st.id', '=', 'la.serah_terima_id')
                ->leftjoin('expedisis as ex', 'st.expedisi_id', '=', 'ex.id')
                ->leftJoin('expedisis as ex_new', 'ex_new.id', '=', 'la.expedisi_id')
                //->where('users.role', 'user')
                ->orderBy('la.created_at', 'DESC')
                ->when($filter['type'] ?? false, function ($query) use ($filter) {
                    switch($filter['type']) {
                        case 'now':
                            return $query->whereDate('la.created_at', today());
                            break;
                        case 'yesterday':
                            return $query->whereDate('la.created_at', today()->subDays(1));
                            break;
                        case 'lastWeek':
                            return $query->whereBetween('la.created_at', [today()->subWeek(), today()]);
                            break;
                        case '30day':
                            $tanggalAwal = Carbon::now()->subMonth();
                            $tanggalAkhir = Carbon::now();
                            return $query->whereDate('la.created_at', '>=', $tanggalAwal)
                                        ->whereDate('la.created_at', '<=', $tanggalAkhir);
                            break;
                        case 'range':
                            $date_range = explode(' to ', $filter['range_date']);
                            return $query->whereDate('la.created_at', '>=', $date_range[0])->whereDate('la.created_at', '<=', $date_range[1] ?? $date_range[0]);
                            break;
                    }
                });
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('expedisi', function ($row) {
                    // $color = $row->color == null ? '#2B4C99' : $row->color;
                    $color = '';
                    if($row->color) {
                        $color = $row->color ? $row->color : '#2B4C99';
                    } else if($row->color_new) {
                        $color = ($row->color_new ?? '') ? $row->color_new : '#2B4C99';
                    }
                    $btn = '<span class="badge" style="background: ' . $color . ';color:white">' . ($row->expedisi ?? $row->expedisi_new ?? '') . '</span>';
                    return $btn;
                })
                ->addColumn('no_resi', function ($row) {
                    $btn = '';
                    if ($row->serah_terima_detail_resi != "" || $row->serah_terima_detail_resi != null) {
                        $btn .= '<span class="badge" style="background: #7dd3fc;color:white">' . $row->serah_terima_detail_resi . '</span>';
                    } else if($row->serah_terima_id) {
                        $btn .= '<button type="button" class="btn btn-outline-primary btn-sm" onclick="handlerViewResi(' . $row->serah_terima_id . ')">View Resi</button>';
                    } else {
                        $btn .= '<span class="badge" style="background: #7dd3fc;color:white">' . $row->no_resi . '</span>';
                    }
                    return $btn;
                })
                ->escapeColumns([])
                ->make(true);
        }
        return view('logactivitas.list', compact('user'));
    }

    public function detail(Request $request)
    {
        $data = DB::table('serah_terima_details')
            ->select("no_resi")
            ->where("serah_terima_id", $request->serahTerimaId)
            ->get();
        echo json_encode($data);
    }
}
