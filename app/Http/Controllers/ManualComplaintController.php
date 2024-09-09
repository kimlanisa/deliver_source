<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use \Carbon\Carbon;
use App\Models\Shop;
use App\Models\User;
use App\Models\Retur;
use App\Models\Expedisi;
use App\Models\Blacklist;
use Illuminate\Http\Request;
use App\Models\ComplaintsManual;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;



class ManualComplaintController extends Controller
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

        return view('manual_complaint.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $shops = Shop::orderBy('name', 'ASC')->get();
        return view('manual_complaint.create-update', compact('shops'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'date' => 'required',
            'shop_id' => 'required',
            'no_pesanan' => $request->id ? 'required|unique:complaints_manuals,no_pesanan,'.$request->id : 'required|unique:complaints_manuals,no_pesanan',
            'customer' => 'required',
            // 'no_whatsapp' => 'required',
            'alasan' => 'required',
            'no_resi' => $request->no_resi ? ($request->id ? 'unique:complaints_manuals,no_resi,'.$request->id : 'unique:complaints_manuals,no_resi') : '',
            'keterangan' => 'required',
            'solution' => 'required',
        ]);

        $jumlah = $request->jumlah;
        $skuAndJumlah = [];
        foreach(($request->sku ?? []) as $key => $item) {
            $skuAndJumlah[] = [
                'sku' => $item,
                'jumlah' => $jumlah[$key] ?? ''
            ];
        }
        $skuAndJumlah = json_encode($skuAndJumlah);
        $input = $request->all();
        $input['sku_jumlah'] = $skuAndJumlah;
        unset($input['sku']);
        unset($input['jumlah']);

        try {
            DB::transaction(function() use ($input, $id, $request){
                if(!$request->id)
                    $input['no_trx'] = ComplaintsManual::generateNomorTransaksi();
                $input['created_by'] = Auth::user()->id;
                ComplaintsManual::updateOrCreate(['id' => $id], $input);
            });

            return redirect(route('manual-complaint.index'))->with('success', 'Data berhasil disimpan!');

        } catch (\Throwable $th) {
           return back()->with('error', $th->getMessage())->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $data = ComplaintsManual::findOrFail($id);
        return view('manual_complaint.show', compact( 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = ComplaintsManual::findOrFail($id);
        $shops = Shop::orderBy('name', 'ASC')->get();
        return view('manual_complaint.create-update', compact('shops', 'data'));
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
        $data = ComplaintsManual::find($id);
        if(!$data)
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan!'
            ]);


        if($request->no_resi) {
            $data->update([
                'no_resi' => $request->no_resi
            ]);
        } else {
            $data->update([
                'status' => $data->status == 1 ? 2 : 1,
                'process_by' => Auth::user()->id,
                'process_at' => Carbon::now()
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diupdate!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $retur = ComplaintsManual::find($id);
        if(!$retur)
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan!'
            ]);

        DB::transaction(function() use ($retur){
            DB::table('log_activitis')->insert([
                'users_id' => Auth::user()->id,
                'no_ref' => $retur->no_trx,
                'no_resi' => $retur->no_resi,
                // 'expedisi_id' => $retur->expedisi_id,
                'keterangan' => "delete data manual complaint no resi inbound " . $retur->no_resi,
            ]);

            $retur->delete();
        });
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus!'
        ]);
    }

    public function dataTable(Request $request)
    {
        $data = ComplaintsManual::select('complaints_manuals.id', 'no_trx', 'date_time', 'shop_id', 'no_pesanan', 'customer', 'no_whatsapp', 'no_resi',  'keterangan', 'created_by',
                                    'status', 'complaints_manuals.created_at', 'solution', 'process_by', 'process_at', 'alasan')
                                ->with(['shop:id,name,color', 'createdBy:id,name', 'processBy:id,name', 'retur:id,no_trx,no_pesanan'])
                                ->join('users as created_by', 'created_by.id', '=', 'complaints_manuals.created_by');
                                if(empty($request->order[0]['column'])) {
                                    $data = $data->orderBY('status', 'asc')
                                                 ->orderBy('id', 'desc');
                                }
                                $data = $data->filter($request->filter);

        return DataTables::of($data)
                            ->addindexColumn()
                            ->addColumn('no_trx', function($data) {
                                return '<a href="javascript:void(0)" style="cursor: pointer" class="detailData" data-id="'.$data->id.'" style="white-space: nowrap">'.$data->no_trx.'</a>';
                            })->addColumn('no_pesanan', function($data) {
                                return '<a href="javascript:void(0)" style="cursor: pointer" class="detailData" data-id="'.$data->id.'" style="white-space: nowrap">'.$data->no_pesanan.'</a>';
                            })
                            ->addColumn('no_retur', function($data) {
                                return '<a href="javascript:void(0)" style="cursor: pointer" class="detailDataRetur" data-id="'.($data->retur->id ?? '').'" style="white-space: nowrap">'.($data->retur->no_trx ?? '').'</a>';
                            })
                            ->addColumn('action', function($data) {
                                $actionButton = '';
                                $statusButton = '';
                                if($data->status == 1) {
                                    $statusButton = '<a href="javascript:void(0)"  data-id="'.$data->id.'" class="actionProcess btn btn-'.($data->status == 1 ? 'primary' : 'success').'" style="white-space: nowrap; font-size:13px; border-radius: 9px">
                                                        '.($data->status == 1 ? 'Tandai Selesai' : 'Tandai Belum Selesai').'
                                                    </a>';
                                }
                                if((Auth::user()->role == 'admin' || canPermission('Daftar Komplain.Full_Akses')) || (canPermission('Daftar Komplain.Perlu_Diproses') && $data->status == 1) || ((Auth::user()->role == 'user' || !canPermission('Daftar Komplain.Full_Akses')) && $data->created_at >= now()->subHours(24) && $data->status == 1)) {
                                    $actionButton = ' <a class="dropdown-item" href="'.route('manual-complaint.edit', $data->id).'">
                                                            <i class="fa fa-fw fa-edit"></i>
                                                            Edit
                                                        </a>
                                                        <a href="javascript:void(0)" data-id="'.$data->id.'"
                                                            class="dropdown-item js-bs-tooltip-enabled btn-delete"
                                                            data-bs-toggle="tooltip" title="Delete"><i
                                                                class="fa fa-fw fa-trash-alt"></i>
                                                            Hapus
                                                        </a>';
                                }

                                return '<div class="d-flex align-items-center gap-2">
                                            '.$statusButton.'
                                            <div class="dropdown">
                                            <button
                                                class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-print"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                            <div class="dropdown-item detailData" data-id="'.$data->id.'" style="white-space: nowrap; cursor: pointer">
                                                <i class="fa fa-eye me-1"></i>
                                                Detail
                                            </div>
                                           '.$actionButton.'
                                            </ul>
                                            </button>
                                        </div>';
                            })->rawColumns(['action', 'no_trx', 'no_pesanan', 'no_retur'])
                           ->smart(true)
                           ->make(true);
    }

    public function export(Request $request)
    {
        $data = ComplaintsManual::select('id', 'no_trx', 'date_time', 'shop_id', 'no_pesanan', 'customer', 'no_whatsapp', 'no_resi',  'keterangan', 'created_by', 'status', 'created_at', 'solution')
                        ->with(['shop:id,name,color', 'createdBy:id,name'])
                        ->orderBY('status', 'asc')
                        ->orderBy('id', 'desc')
                        ->filter($request->all())
                        ->get();
        // return view('manual_complaint.exportExcel', compact('data'));
        return response(view('manual_complaint.exportExcel', compact('data')))
        ->header('Content-Type', 'application/vnd-ms-excel')
        ->header('Content-Disposition', 'attachment; filename="' . 'Laporan Pusat Komplain Manual ('.date('d F Y').').xls"');
    }
}
