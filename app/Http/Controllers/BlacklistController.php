<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use \Carbon\Carbon;
use App\Models\User;
use App\Models\Expedisi;
use App\Models\Blacklist;
// use Dompdf\Dompdf;
// use Dompdf\Options;
use Illuminate\Http\Request;
use App\Models\BlacklistDetail;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
// use Maatwebsite\Excel\Facades\Excel;
// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\Exportable;
// use Maatwebsite\Excel\Concerns\WithHeadings;
// use Maatwebsite\Excel\Concerns\WithColumnWidths;
// use Maatwebsite\Excel\Concerns\WithStyles;
// use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;



class BlacklistController extends Controller
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

        return view('blacklist.list');
    }



    public function getDataBlacklist(Request $request)
    {
        $data = BlacklistDetail::select('blacklist_details.id', 'no_resi', 'blacklist_id', 'expedisi_id', 'blacklist_details.created_at')
                        ->with(['ekspedisi:id,expedisi,color', 'blacklist'])
                        ->filter($request->filter);
        if(empty($request->order[0]['column'])) {
            $data = $data->latest();
        }


        if($request->page && $request->page_size !== 'All') {
            $blacklist = $data->filter($request->all())->paginate($request->page_size, ['*'], 'page', $request->page);
            $blacklists['data'] = $blacklist->map(function ($item) {
                return [
                    'id' => $item->id,
                    'no_resi' => $item->no_resi,
                    'expedisi' => $item->ekspedisi,
                    'created_at' => date('Y-m-d H:i:s', strtotime($item->created_at)),
                    'catatan' => $item->blacklist->catatan ?? '-'
                ];
            });
            $blacklists['date_now'] = date('Y-m-d');
            $blacklists['full_akses'] = canPermission('Daftar Blacklist.Full_Akses', true);
            $blacklists['role'] = auth()->user()->role;
            $blacklists['meta'] = [
                'current_page' => $blacklist->currentPage(),
                'per_page' => $blacklist->perPage(),
                'total' => $blacklist->total(),
                'last_page' => $blacklist->lastPage(),
            ];
            return response()->json($blacklists);
        }

        return DataTables::of($data)
                            ->addindexColumn()
                            ->addColumn('action', function($data) {
                                $actionButton = '';
                                if((Auth::user()->role == 'admin')
                                || ((Auth::user()->role == 'user' || !canPermission('Daftar Blacklist.Full_Akses', true)) && $data->created_at >= now()->subHours(24))
                                || (canPermission('Daftar Blacklist.Full_Akses', true))) {
                                    $actionButton = '<a href="javascript:void(0)" data-id="'.$data->id.'"
                                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-delete"
                                                            data-bs-toggle="tooltip" title="Delete"><i
                                                                class="fa fa-fw fa-trash-alt"></i>
                                                        </a>';
                                }

                                return '<div class="d-flex align-items-center gap-2">
                                           '.$actionButton.'
                                        </div>';
                            })->rawColumns(['action'])
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
        // $id = $request->get('id');
        // $dataBlacklist = "";
        // if($id){
        //     $dataBlacklist = Blacklist::where('id', $id)->first();
        // }

        $expedisi = Expedisi::all();
        return view('blacklist.add', compact('expedisi'));
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

        //GENERATE NO TANDA TERIMA
        $last = Blacklist::orderBy('id', 'desc')->first();
        if (!$last) {
            $noTD = 'BL-' . '001';
        }

        if ($last) {
            $lastTD = substr($last->no_blacklist, -3);
            $nextTD = (intval($lastTD)) + 1;

            if ($nextTD < 10) {
                $nextTD = '00' . $nextTD;
            } elseif ($nextTD < 100) {
                $nextTD = '0' . $nextTD;
            }
            $noTD = 'BL-' . $nextTD;
        }

        //END GENERATE


        $data = $request->all();
        $items = $data['items'];
        $expedisi_id = $request->expedisi_id;


        DB::beginTransaction();

        try {
            // menyimpan data seraterima jika serah terima id null, jik ada gausah insert krena tmbh data detail
            $blacklist = new Blacklist;
            $blacklist->no_blacklist = $noTD;
            $blacklist->expedisi_id = $expedisi_id;
            $blacklist->catatan = $request->catatan;
            $blacklist->user_id = $user->id;
            $blacklist->deleted_at = 0;
            $blacklist->save();

            foreach ($items as $item) {
                DB::table('blacklist_details')->insert([
                    'blacklist_id' => $blacklist->id,
                    'no_resi' => $item['no_resi'],
                    'expedisi_id' => $item['expedisi_id'],
                    'created_at' => $date,
                    'deleted_at' => 0
                ]);
            }



            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data Blacklist berhasil disimpan.'
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
                'message' => 'No resi sudah terdaftar ke blacklist!'
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

    public function show($id)
    {
        $blacklist = DB::table('blacklists as st')
            ->where('st.id', $id)
            ->first();
        $detail = DB::table('blacklist_details as std')
            ->leftjoin('blacklists as st', 'st.id', '=', 'std.blacklist_id')
            ->leftjoin('expedisis as ex', 'ex.id', '=', 'st.expedisi_id')
            ->where('std.blacklist_id', $id)
            ->where('std.deleted_at', 0)
            ->select('std.*', 'ex.expedisi', 'ex.color')
            ->get();
        $count = DB::table('blacklist_details')->where('blacklist_id', $id)->where('deleted_at', 0)->count();
        return view('blacklist.detail', compact('blacklist', 'detail', 'count'));
    }

    public function getDetailSerahTerimaById($id){
        $detail = DB::table('serah_terima_details as std')
            ->leftjoin('serah_terimas as st', 'st.id', '=', 'std.serah_terima_id')
            ->leftjoin('expedisis as ex', 'ex.id', '=', 'st.expedisi_id')
            ->where('std.serah_terima_id', $id)
            ->where('std.deleted_at', 0)
            ->select('std.*', 'ex.expedisi', 'ex.color')
            ->get();
        echo json_encode($detail);
    }

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
        // DB::table('serah_terimas')
        //     ->where('id', $id)
        //     ->update([
        //         'deleted_at' => 1
        //     ]);

        // DB::table('serah_terima_details')
        //     ->where('serah_terima_id', $id)
        //     ->update([
        //         'deleted_at' => 1
        //     ]);

        // DB::table('log_activitis')->insert([
        //     'users_id' => Auth::user()->id,
        //     'serah_terima_id' => $id,
        //     'keterangan' => "delete data serah terima berdasarkan no tanda terima",
        // ]);

        //Blacklist::find($id)->delete();
        $blacklist_details = BlacklistDetail::find($id);

        DB::transaction(function() use ($blacklist_details){
            DB::table('log_activitis')->insert([
                'users_id' => Auth::user()->id,
                'no_ref' => $blacklist_details->no_resi,
                'no_resi' => $blacklist_details->no_resi,
                'keterangan' => "delete blacklist berdasarkan no resi " . $blacklist_details->no_resi,
                'expedisi_id' => $blacklist_details->expedisi_id,
            ]);
            $blacklist_details->delete();
        });

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus.'
        ]);
    }

    public function destroyResi($id)
    {

        // $serah_terima_id = DB::table('serah_terima_details')->select('serah_terima_id')->where('no_resi', $id)->first()->serah_terima_id;

        // DB::table('serah_terima_details')
        //     ->where('no_resi', $id)
        //     ->update([
        //         'deleted_at' => 1
        //     ]);

        // DB::table('log_activitis')->insert([
        //     'users_id' => Auth::user()->id,
        //     'serah_terima_id' => $serah_terima_id,
        //     'serah_terima_detail_resi' => $id,
        //     'keterangan' => "delete data serah terima berdasarkan no resi"
        // ]);
        DB::table('blacklist_details')->where('no_resi', $id)->delete();
        // DB::table('serah_terima_detail_temps')->where('no_resi', $id)->delete();
    }
}
