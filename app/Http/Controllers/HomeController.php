<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\User;
use App\Http\Models\Provinsi;
use App\Http\Models\Kota;
use App\Http\Models\Kecamatan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;

class HomeController extends Controller
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
    public function index()
    {
        $user = Auth::user();

        // $jne = DB::table('serah_terimas as st')
        //     ->leftjoin('serah_terima_details as std', 'std.serah_terima_id', '=', 'st.id')
        //     ->leftjoin('expedisis as x', 'x.id', '=', 'st.expedisi_id')
        //     ->select(DB::raw('count(std.serah_terima_id) as total'))
        //     ->groupBy('x.expedisi')
        //     ->where('st.expedisi_id', '2')
        //     ->count();
        // $shopee = DB::table('serah_terimas as st')
        //     ->leftjoin('serah_terima_details as std', 'std.serah_terima_id', '=', 'st.id')
        //     ->leftjoin('expedisis as x', 'x.id', '=', 'st.expedisi_id')
        //     ->select(DB::raw('count(std.serah_terima_id) as total'))
        //     ->groupBy('x.expedisi')
        //     ->where('st.expedisi_id', '1')
        //     ->count();

        // $jnt = DB::table('serah_terimas as st')
        //     ->leftjoin('serah_terima_details as std', 'std.serah_terima_id', '=', 'st.id')
        //     ->leftjoin('expedisis as x', 'x.id', '=', 'st.expedisi_id')
        //     ->select(DB::raw('count(std.serah_terima_id) as total'))
        //     ->groupBy('x.expedisi')
        //     ->where('st.expedisi_id', '3')
        //     ->count();

        // $antaraja = DB::table('serah_terimas as st')
        //     ->leftjoin('serah_terima_details as std', 'std.serah_terima_id', '=', 'st.id')
        //     ->leftjoin('expedisis as x', 'x.id', '=', 'st.expedisi_id')
        //     ->select(DB::raw('count(std.serah_terima_id) as total'))
        //     ->groupBy('x.expedisi')
        //     ->where('st.expedisi_id', '4')
        //     ->count();


        return view('dashboard');
    }

    public function getData(Request $request)
    {
        $user = Auth::user();

        $type = $request->type;
        $dateStr = $request->dateStr;

        // $jne = DB::table('serah_terimas as st')
        //     ->leftjoin('serah_terima_details as std', 'std.serah_terima_id', '=', 'st.id')
        //     ->leftjoin('expedisis as x', 'x.id', '=', 'st.expedisi_id')
        //     ->select(DB::raw('count(std.serah_terima_id) as total'))
        //     ->when($type == 'now', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d'));
        //     })
        //     ->when($type == 'yesterday', function ($query) use ($type) {
        //         $query->whereBetween(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), [date('Y-m-d', strtotime('-1 days')), date('Y-m-d')]);
        //     })
        //     ->when($type == '7', function ($query) use ($type) {
        //         $query->whereBetween(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), [date('Y-m-d', strtotime('-7 days')), date('Y-m-d')]);
        //     })
        //     ->when($type == '30', function ($query) use ($type) {
        //         $query->whereBetween(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), [date('Y-m-d', strtotime('-30 days')), date('Y-m-d')]);
        //     })
        //     ->when($type == 'range', function ($query) use ($type, $dateStr) {
        //         $date = explode(' to ', $dateStr);
        //         $query->whereBetween(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), $date);
        //     })
        //     ->groupBy('x.expedisi')
        //     ->where('st.expedisi_id', '2')
        //     ->count();
        // $shopee = DB::table('serah_terimas as st')
        //     ->leftjoin('serah_terima_details as std', 'std.serah_terima_id', '=', 'st.id')
        //     ->leftjoin('expedisis as x', 'x.id', '=', 'st.expedisi_id')
        //     ->select(DB::raw('count(std.serah_terima_id) as total'))
        //     ->when($type == 'now', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d'));
        //     })
        //     ->when($type == 'yesterday', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-1 days')));
        //     })
        //     ->when($type == '7', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-7 days')));
        //     })
        //     ->when($type == '30', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-30 days')));
        //     })
        //     ->when($type == 'range', function ($query) use ($type, $dateStr) {
        //         $date = explode(' to ', $dateStr);
        //         $query->whereBetween(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), $date);
        //     })
        //     ->groupBy('x.expedisi')
        //     ->where('st.expedisi_id', '1')
        //     ->count();

        // $jnt = DB::table('serah_terimas as st')
        //     ->leftjoin('serah_terima_details as std', 'std.serah_terima_id', '=', 'st.id')
        //     ->leftjoin('expedisis as x', 'x.id', '=', 'st.expedisi_id')
        //     ->select(DB::raw('count(std.serah_terima_id) as total'))
        //     ->when($type == 'now', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d'));
        //     })
        //     ->when($type == 'yesterday', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-1 days')));
        //     })
        //     ->when($type == '7', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-7 days')));
        //     })
        //     ->when($type == '30', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-30 days')));
        //     })
        //     ->when($type == 'range', function ($query) use ($type, $dateStr) {
        //         $date = explode(' to ', $dateStr);
        //         $query->whereBetween(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), $date);
        //     })
        //     ->groupBy('x.expedisi')
        //     ->where('st.expedisi_id', '3')
        //     ->count();

        // $antaraja = DB::table('serah_terimas as st')
        //     ->leftjoin('serah_terima_details as std', 'std.serah_terima_id', '=', 'st.id')
        //     ->leftjoin('expedisis as x', 'x.id', '=', 'st.expedisi_id')
        //     ->select(DB::raw('count(std.serah_terima_id) as total'))
        //     ->when($type == 'now', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d'));
        //     })
        //     ->when($type == 'yesterday', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-1 days')));
        //     })
        //     ->when($type == '7', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-7 days')));
        //     })
        //     ->when($type == '30', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-30 days')));
        //     })
        //     ->when($type == 'range', function ($query) use ($type, $dateStr) {
        //         $date = explode(' to ', $dateStr);
        //         $query->whereBetween(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), $date);
        //     })
        //     ->groupBy('x.expedisi')
        //     ->where('st.expedisi_id', '4')
        //     ->count();

        // $sicepat = DB::table('serah_terimas as st')
        //     ->leftjoin('serah_terima_details as std', 'std.serah_terima_id', '=', 'st.id')
        //     ->leftjoin('expedisis as x', 'x.id', '=', 'st.expedisi_id')
        //     ->select(DB::raw('count(std.serah_terima_id) as total'))
        //     ->when($type == 'now', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d'));
        //     })
        //     ->when($type == 'yesterday', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-1 days')));
        //     })
        //     ->when($type == '7', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-7 days')));
        //     })
        //     ->when($type == '30', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-30 days')));
        //     })
        //     ->when($type == 'range', function ($query) use ($type, $dateStr) {
        //         $date = explode(' to ', $dateStr);
        //         $query->whereBetween(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), $date);
        //     })
        //     ->groupBy('x.expedisi')
        //     ->where('st.expedisi_id', '7')
        //     ->count();

        // $idexpress = DB::table('serah_terimas as st')
        //     ->leftjoin('serah_terima_details as std', 'std.serah_terima_id', '=', 'st.id')
        //     ->leftjoin('expedisis as x', 'x.id', '=', 'st.expedisi_id')
        //     ->select(DB::raw('count(std.serah_terima_id) as total'))
        //     ->when($type == 'now', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d'));
        //     })
        //     ->when($type == 'yesterday', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-1 days')));
        //     })
        //     ->when($type == '7', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-7 days')));
        //     })
        //     ->when($type == '30', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-30 days')));
        //     })
        //     ->when($type == 'range', function ($query) use ($type, $dateStr) {
        //         $date = explode(' to ', $dateStr);
        //         $query->whereBetween(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), $date);
        //     })
        //     ->groupBy('x.expedisi')
        //     ->where('st.expedisi_id', '5')
        //     ->count();
        // $gojekInstan = DB::table('serah_terimas as st')
        //     ->leftjoin('serah_terima_details as std', 'std.serah_terima_id', '=', 'st.id')
        //     ->leftjoin('expedisis as x', 'x.id', '=', 'st.expedisi_id')
        //     ->select(DB::raw('count(std.serah_terima_id) as total'))
        //     ->when($type == 'now', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d'));
        //     })
        //     ->when($type == 'yesterday', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-1 days')));
        //     })
        //     ->when($type == '7', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-7 days')));
        //     })
        //     ->when($type == '30', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-30 days')));
        //     })
        //     ->when($type == 'range', function ($query) use ($type, $dateStr) {
        //         $date = explode(' to ', $dateStr);
        //         $query->whereBetween(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), $date);
        //     })
        //     ->groupBy('x.expedisi')
        //     ->where('st.expedisi_id', '8')
        //     ->count();
        // $grabInstan = DB::table('serah_terimas as st')
        //     ->leftjoin('serah_terima_details as std', 'std.serah_terima_id', '=', 'st.id')
        //     ->leftjoin('expedisis as x', 'x.id', '=', 'st.expedisi_id')
        //     ->select(DB::raw('count(std.serah_terima_id) as total'))
        //     ->when($type == 'now', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d'));
        //     })
        //     ->when($type == 'yesterday', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-1 days')));
        //     })
        //     ->when($type == '7', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-7 days')));
        //     })
        //     ->when($type == '30', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-30 days')));
        //     })
        //     ->when($type == 'range', function ($query) use ($type, $dateStr) {
        //         $date = explode(' to ', $dateStr);
        //         $query->whereBetween(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), $date);
        //     })
        //     ->groupBy('x.expedisi')
        //     ->where('st.expedisi_id', '9')
        //     ->count();
        // $tiki = DB::table('serah_terimas as st')
        //     ->leftjoin('serah_terima_details as std', 'std.serah_terima_id', '=', 'st.id')
        //     ->leftjoin('expedisis as x', 'x.id', '=', 'st.expedisi_id')
        //     ->select(DB::raw('count(std.serah_terima_id) as total'))
        //     ->when($type == 'now', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d'));
        //     })
        //     ->when($type == 'yesterday', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-1 days')));
        //     })
        //     ->when($type == '7', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-7 days')));
        //     })
        //     ->when($type == '30', function ($query) use ($type) {
        //         $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d', strtotime('-30 days')));
        //     })
        //     ->when($type == 'range', function ($query) use ($type, $dateStr) {
        //         $date = explode(' to ', $dateStr);
        //         $query->whereBetween(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), $date);
        //     })
        //     ->groupBy('x.expedisi')
        //     ->where('st.expedisi_id', '6')
        //     ->count();

        $query = DB::table('expedisis as x')
            ->leftjoin('serah_terimas as st', 'x.id', '=', 'st.expedisi_id')
            ->leftjoin('serah_terima_details as std', 'std.serah_terima_id', '=', 'st.id')
            ->select('x.expedisi', 'x.color', DB::raw('count(std.serah_terima_id) as total'))
            ->where('st.deleted_at', 0)
            ->where('std.deleted_at', 0)
            ->when($type == 'now', function ($query) use ($type) {
                $query->where(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), date('Y-m-d'));
            })
            ->when($type == 'yesterday', function ($query) use ($type) {
                $query->whereBetween(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), [date('Y-m-d', strtotime('-1 days')), date('Y-m-d')]);
            })
            ->when($type == '7', function ($query) use ($type) {
                $query->whereBetween(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), [date('Y-m-d', strtotime('-7 days')), date('Y-m-d')]);
            })
            ->when($type == '30', function ($query) use ($type) {
                $query->whereBetween(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), [date('Y-m-d', strtotime('-30 days')), date('Y-m-d')]);
            })
            ->when($type == 'range', function ($query) use ($type, $dateStr) {
                $date = explode(' to ', $dateStr);
                $query->whereBetween(DB::raw("DATE_FORMAT(std.created_at, '%Y-%m-%d')"), $date);
            })
            ->groupBy('x.expedisi', 'x.color')
            ->having(DB::raw('count(std.serah_terima_id)'), '>', 0)
            ->get();

        return response()->json([
            'data' => $query,
        ]);
    }
}
