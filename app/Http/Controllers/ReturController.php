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
use App\Models\BlacklistDetail;
use Yajra\DataTables\DataTables;
use App\Models\InboundReturDetail;
use Illuminate\Support\Facades\DB;



class ReturController extends Controller
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

        return view('retur.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $shops = Shop::orderBy('name', 'ASC')->get();
        $inbound_retur = InboundReturDetail::with(['shop:id,name', 'ekspedisi:id,expedisi', 'retur'])
                                            ->whereDoesntHave('retur')
                                            ->get();
        return view('retur.create-update', compact('shops', 'inbound_retur'));
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
            'no_pesanan' => $request->id ? 'required|unique:returs,no_pesanan,'.$request->id : 'required|unique:returs,no_pesanan',
            'customer' => 'required',
            'status2' => 'required',
            // 'inbound_retur_id' => $request->id ? 'unique:returs,inbound_retur_id,'.$request->id : 'required|unique:returs,inbound_retur_id',
            'alasan_retur' => 'required',
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
                    $input['no_trx'] = Retur::generateNomorTransaksi();
                $input['created_by'] = Auth::user()->id;
                Retur::updateOrCreate(['id' => $id], $input);
            });

            return redirect(route('retur.index'))->with('success', 'Data berhasil disimpan!');

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
        $data = Retur::findOrFail($id);
        return view('retur.show', compact( 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Retur::findOrFail($id);
        $shops = Shop::orderBy('name', 'ASC')->get();
        $inbound_retur = InboundReturDetail::with(['shop:id,name', 'ekspedisi:id,expedisi', 'retur'])
                                            ->whereDoesntHave('retur', function($query) use ($id) {
                                                $query->where('id', '!=', $id);
                                            })
                                            ->get();
        return view('retur.create-update', compact('shops', 'data', 'inbound_retur'));
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
        $data = Retur::find($id);
        if(!$data)
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditermukan'
            ]);

        $data->update([
            'status' => $request->status ?? $data->status,
            'no_whatsapp' => $request->no_whatsapp ?? $data->no_whatsapp,
            'status2' => $request->status2 ?? $data->status2,
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
        $retur = Retur::find($id);
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
                'keterangan' => "delete data request refund",
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

        $data = Retur::select('returs.id', 'no_trx', 'date', 'shop_id', 'no_pesanan', 'customer', 'no_whatsapp', 'no_resi', 'sku_jumlah', 'alasan_retur', 'created_by', 'inbound_retur_id',
                            'returs.created_at', 'status', 'status2', 'created_by.name')
                        ->join('users as created_by', 'created_by.id', '=', 'returs.created_by')
                        ->with(['shop:id,name,color', 'createdBy:id,name', 'complaint:id,no_pesanan,no_trx']);
                        if(empty($request->order[0]['column'])) {
                           $data = $data->latest();
                        }
                        $data = $data->filter($request->filter);

        return DataTables::of($data)
                            ->addindexColumn()
                            ->addColumn('sku_jumlah', function($data) {
                                $skuAndJumlah = json_decode($data->sku_jumlah);
                                return json_decode($data->sku_jumlah);
                            })
                            ->addColumn('no_trx', function($data) {
                                return '<a href="javascript:void(0)" style="cursor: pointer" class="detailData" data-id="'.$data->id.'" style="white-space: nowrap">'.$data->no_trx.'</a>';
                            })->addColumn('no_pesanan', function($data) {
                                return '<a href="javascript:void(0)" style="cursor: pointer" class="detailData" data-id="'.$data->id.'" style="white-space: nowrap">'.$data->no_pesanan.'</a>';
                            })
                            ->addColumn('no_complaint', function($data) {
                                return '<a href="javascript:void(0)" style="cursor: pointer" class="detailDataComplaint" data-id="'.($data->complaint->id ?? '').'" style="white-space: nowrap">'.($data->complaint->no_trx ?? '').'</a>';
                            })
                            ->addColumn('action', function($data) {
                                $actionButton = '';
                                if((Auth::user()->role == 'admin')
                                    || ((Auth::user()->role == 'user' || !canPermission('Daftar Returan.Full_Akses', true)) && date('Y-m-d', strtotime($data->created_at)) === date('Y-m-d'))
                                    || (canPermission('Daftar Returan.Full_Akses', true))) {
                                    $actionButton = ' <a class="dropdown-item" href="'.route('retur.edit', $data->id).'">
                                                            <i class="fa fa-fw fa-edit"></i>
                                                            Edit
                                                        </a>
                                                        <a href="javascript:void(0)" data-id="'.$data->id.'"
                                                            class=" js-bs-tooltip-enabled btn-delete dropdown-item"
                                                            data-bs-toggle="tooltip" title="Delete"><i
                                                                class="fa fa-fw fa-trash-alt"></i>
                                                            Hapus
                                                        </a>';
                                }
                                 // <a class="dropdown-item" href="'.route('retur.show', $data->id).'" style="white-space: nowrap">
                                                //     <i class="fa fa-eye me-1"></i>
                                                //     Detail
                                                // </a>
                                return '
                                        <div class="dropdown">
                                            <button
                                                class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-print"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <a href="javascript:void(0)" style="cursor: pointer" class="js-bs-tooltip-enabled dropdown-item detailData" data-id="'.$data->id.'" style="white-space: nowrap">
                                                    <i class="fa fa-eye me-1"></i>
                                                    Detail
                                                </a>
                                                '.$actionButton.'
                                            </ul>
                                        </div>
                                        ';
                            })->rawColumns(['action', 'no_trx', 'no_pesanan', 'sku_jumlah', 'no_complaint'])
                           ->smart(true)
                           ->make(true);
    }

    public function export(Request $request)
    {
        $data = Retur::select('id', 'no_trx', 'date', 'shop_id', 'no_pesanan', 'customer', 'no_whatsapp', 'no_resi', 'sku_jumlah', 'alasan_retur', 'created_by', 'created_at', 'status')
                        ->with(['shop:id,name,color', 'createdBy:id,name'])
                        ->latest()
                        ->filter($request->all())
                        ->get();
        // return view('retur.exportExcel', compact('data'));
        return response(view('retur.exportExcel', compact('data')))
        ->header('Content-Type', 'application/vnd-ms-excel')
        ->header('Content-Disposition', 'attachment; filename="' . 'Laporan Returan Paket ('.date('d F Y').').xls"');
    }
}
