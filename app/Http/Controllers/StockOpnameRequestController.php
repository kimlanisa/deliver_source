<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use \Carbon\Carbon;
use App\Models\Shop;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\StockOpnameRequest;
use Illuminate\Support\Facades\DB;



class StockOpnameRequestController extends Controller
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

        return view('stock_opname_request.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $shops = Shop::orderBy('name', 'ASC')->get();
        return view('stock_opname_request.create-update', compact('shops'));
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
            'sku' => 'required',
        ]);

        if($request->minus == 0 && $request->plus == 0)
            return back()->with('error', 'Minus atau Plus harus diisi!')->withInput();

        $input = $request->all();

        try {
            DB::transaction(function() use ($input, $id, $request){
                if(!$request->id)
                    $input['no_trx'] = StockOpnameRequest::generateNomorTransaksi();
                $input['user_id'] = Auth::user()->id;
                StockOpnameRequest::updateOrCreate(['id' => $id], $input);
            });

            return redirect(route('stock-opname-request.index'))->with('success', 'Data berhasil disimpan!');

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
        $data = StockOpnameRequest::findOrFail($id);
        return view('stock_opname_request.detail', compact( 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = StockOpnameRequest::findOrFail($id);
        $shops = Shop::orderBy('name', 'ASC')->get();
        return view('stock_opname_request.create-update', compact('shops', 'data'));
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
        $data = StockOpnameRequest::find($id);
        if(!$data)
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan!'
            ]);


        $data->update([
            'status' => $data->status == 1 ? 2 : 1,
            'process_by' => Auth::user()->id,
            'process_at' => Carbon::now()
        ]);

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
        $retur = StockOpnameRequest::find($id);
        if(!$retur)
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan!'
            ]);

        DB::transaction(function() use ($retur){
            $retur->delete();
            DB::table('log_activitis')->insert([
                'users_id' => Auth::user()->id,
                'no_ref' => $retur->no_trx,
                'keterangan' => "delete data stock opname request",
            ]);
        });

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus!'
        ]);
    }

    public function dataTable(Request $request)
    {
        $data = StockOpnameRequest::select('stock_opname_requests.id', 'no_trx', 'date', 'sku','minus','plus','user_id', 'status', 'stock_opname_requests.created_at', 'process_by',
                         'process_at',  'user.name')
                        ->with(['shop:id,name,color', 'user:id,name', 'processBy:id,name'])
                        ->join('users as user', 'user.id', '=', 'stock_opname_requests.user_id');
                        if(empty($request->order[0]['column'])) {
                            $data = $data->orderBY('status', 'asc')
                                         ->orderBy('id', 'desc');
                        }
                        $data = $data->filter($request->filter);

        return DataTables::of($data)
                            ->addindexColumn()
                            ->addColumn('action', function($data) {
                                $actionButton = '';
                                $actionProcess = '';
                                if((Auth::user()->role == 'admin' || canPermission('Stock Opname Request.Full_Akses', true)) && $data->status == 1) {
                                    $actionProcess = '<a href="javascript:void(0)"  data-id="'.$data->id.'" class="actionProcess btn btn-'.($data->status == 1 ? 'primary' : 'success').'" style="white-space: nowrap; font-size:13px; border-radius: 9px">
                                                        '.($data->status == 1 ? 'Tandai Selesai' : 'Tandai Belum Selesai').'
                                                    </a>';
                                }
                                if((Auth::user()->role == 'admin')
                                || ((Auth::user()->role == 'user' || !canPermission('Stock Opname Request.Full_Akses', true)) && $data->created_at >= now()->subHours(24) && $data->status == 1)
                                || (canPermission('Stock Opname Request.Full_Akses', true))) {
                                    $actionButton = '<a class="dropdown-item" href="'.route('stock-opname-request.edit', $data->id).'" class="">
                                                            <i class="fa fa-fw fa-edit"></i>
                                                            Edit
                                                        </a>
                                                        <a href="javascript:void(0)" data-id="'.$data->id.'"
                                                            class="js-bs-tooltip-enabled btn-delete dropdown-item"
                                                            data-bs-toggle="tooltip" title="Delete"><i
                                                                class="fa fa-fw fa-trash-alt"></i>
                                                                Hapus
                                                        </a>';
                                }

                                return '
                                <div class="d-flex align-items-center gap-2">
                                '.$actionProcess.'
                                <div class="dropdown">
                                <button
                                    class="js-bs-tooltip-enabled btn btn-sm btn-alt-secondary "
                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    '.$actionButton.'
                                </ul>
                            </div>
                            </div>';
                            })->rawColumns(['action'])
                           ->smart(true)
                           ->make(true);
    }

    public function export(Request $request)
    {
        $data = StockOpnameRequest::select('id', 'no_trx', 'date', 'sku','minus','plus','user_id', 'status', 'created_at')
                                    ->with(['shop:id,name,color', 'user:id,name'])
                                    ->orderBY('status', 'asc')
                                    ->orderBy('id', 'desc')
                                    ->filter($request->all())
                                    ->get();
        // return view('stock_opname_request.exportExcel', compact('data'));
        return response(view('stock_opname_request.exportExcel', compact('data')))
        ->header('Content-Type', 'application/vnd-ms-excel')
        ->header('Content-Disposition', 'attachment; filename="' . 'Laporan Stock Opname Request ('.date('d F Y').').xls"');
    }
}
