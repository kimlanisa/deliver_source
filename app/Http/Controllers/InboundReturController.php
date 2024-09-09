<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use \Carbon\Carbon;
use App\Models\Shop;
use App\Models\User;
use App\Models\Expedisi;
// use Dompdf\Dompdf;
// use Dompdf\Options;
use App\Models\Blacklist;
use App\Models\InboundRetur;
use Illuminate\Http\Request;
use App\Models\BlacklistDetail;
use Yajra\DataTables\DataTables;
use App\Models\InboundReturDetail;
use Illuminate\Support\Facades\DB;


class InboundReturController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('inbound-retur.index');
    }



    public function getData(Request $request)
    {
        $data = InboundReturDetail::select('inbound_retur_details.id', 'expedisi_id', 'inbound_retur_details.no_resi',
                                    'inbound_retur_details.created_at', 'returs.id as retur_id', 'returs.no_trx as no_retur',
                                    'created_by.name as created_by',)
                                    ->leftjoin('returs', 'returs.inbound_retur_id', '=', 'inbound_retur_details.id')
                                    ->leftjoin('users as created_by', 'created_by.id', '=', 'returs.created_by')
                                    ->with(['ekspedisi:id,expedisi,color', 'shop:id,name,color',])
                                    ->filter($request->filter);
        if(empty($request->order[0]['column'])) {
            $data = $data->latest();
        }

        return DataTables::of($data)
                            ->addindexColumn()
                            ->addColumn('no_retur', function($data) {
                                return '<a href="javascript:void(0)" style="cursor: pointer" class="detailData" data-id="'.$data->retur_id.'" style="white-space: nowrap">'.$data->no_retur.'</a>';
                            })
                            ->addColumn('action', function($data) {
                                $actionButton = '';
                                if((Auth::user()->role == 'admin')
                                || (Auth::user()->role !== 'admin' && date('Y-m-d', strtotime($data->created_at)) === date('Y-m-d'))) {
                                    $actionButton = '<a href="javascript:void(0)" data-id="'.$data->id.'"
                                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-delete"
                                                            data-bs-toggle="tooltip" title="Delete"><i
                                                                class="fa fa-fw fa-trash-alt"></i>
                                                        </a>';
                                }

                                return '<div class="d-flex align-items-center gap-2">
                                           '.$actionButton.'
                                        </div>';
                            })->rawColumns(['action', 'no_retur'])
                           ->smart(true)
                           ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $expedisi = Expedisi::all();
        $shop = Shop::all();
        return view('inbound-retur.create-update', compact('expedisi', 'shop'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $date = Carbon::now();

        $data = $request->all();
        $items = $data['items'];
        $expedisi_id = $request->expedisi_id;


        DB::beginTransaction();

        try {
            // menyimpan data seraterima jika serah terima id null, jik ada gausah insert krena tmbh data detail
            // $blacklist = new Blacklist;
            // $blacklist->no_blacklist = $noTD;
            // $blacklist->expedisi_id = $expedisi_id;
            // $blacklist->catatan = $request->catatan;
            // $blacklist->user_id = $user->id;
            // $blacklist->deleted_at = 0;
            // $blacklist->save();

            foreach ($items as $item) {
               InboundReturDetail::create([
                    'no_resi' => $item['no_resi'],
                    'expedisi_id' => $expedisi_id,
                    'created_by_id' => $user->id,
                    'waktu_scan' => $item['waktu_scan'] ?? $date,
                ]);
            }



            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data Inbound Retur berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data.'
            ]);

        }
    }


    public function scanBarcode(Request $request)
    {
        $user = Auth::user();
        $no_resi = $request->no_resi;

        $cek_no_db = BlacklistDetail::where('no_resi', $no_resi)->exists();
        if ($cek_no_db > 0) {
            return response()->json([
                'success' => false,
                'type' => 'blacklist',
                'message' => 'No resi sudah terdaftar ke blacklist!'
            ]);
        }

        $inbound_retur = InboundReturDetail::where('no_resi', $no_resi)->first();
        if ($inbound_retur) {
            return response()->json([
                'success' => false,
                'type' => 'inbound_retur',
                'message' => 'No resi Inbound Retur sudah ada!'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan!'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // public function show($id)
    // {
    //     $blacklist = DB::table('blacklists as st')
    //         ->where('st.id', $id)
    //         ->first();
    //     $detail = DB::table('blacklist_details as std')
    //         ->leftjoin('blacklists as st', 'st.id', '=', 'std.blacklist_id')
    //         ->leftjoin('expedisis as ex', 'ex.id', '=', 'st.expedisi_id')
    //         ->where('std.blacklist_id', $id)
    //         ->where('std.deleted_at', 0)
    //         ->select('std.*', 'ex.expedisi', 'ex.color')
    //         ->get();
    //     $count = DB::table('blacklist_details')->where('blacklist_id', $id)->where('deleted_at', 0)->count();
    //     return view('blacklist.detail', compact('blacklist', 'detail', 'count'));
    // }

    // public function getDetailSerahTerimaById($id){
    //     $detail = DB::table('serah_terima_details as std')
    //         ->leftjoin('serah_terimas as st', 'st.id', '=', 'std.serah_terima_id')
    //         ->leftjoin('expedisis as ex', 'ex.id', '=', 'st.expedisi_id')
    //         ->where('std.serah_terima_id', $id)
    //         ->where('std.deleted_at', 0)
    //         ->select('std.*', 'ex.expedisi', 'ex.color')
    //         ->get();
    //     echo json_encode($detail);
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $inbound_retur_detail = InboundReturDetail::find($id);
        DB::transaction(function () use ($inbound_retur_detail) {
            DB::table('log_activitis')->insert([
                'users_id' => Auth::user()->id,
                'no_ref' => $inbound_retur_detail->no_trx,
                'no_resi' => $inbound_retur_detail->no_resi,
                'expedisi_id' => $inbound_retur_detail->expedisi_id,
                'keterangan' => "delete data inbound retur no resi " . $inbound_retur_detail->no_resi,
            ]);
            $inbound_retur_detail->delete();
        });

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus.'
        ]);
    }
}
