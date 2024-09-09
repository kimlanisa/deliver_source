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
use App\Models\KartuStockMenu;
use App\Models\BlacklistDetail;
use Yajra\DataTables\DataTables;
use App\Models\InboundReturDetail;
use App\Models\KartuStockMenuData;
use Illuminate\Support\Facades\DB;

class KartuStockMenuDetailController extends Controller
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
    public function index($id)
    {
        $data = KartuStockMenu::findOrFail($id);
        return view('kartu_stock_menu.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $id = $request->id;
        $request->validate([
            'qty_stock' => 'required',
            'type' => 'required',
        ]);
        $qty_stock = intval($request->qty_stock);

        $lastData = KartuStockMenuData::where('kartu_stock_menu_id', $id)->latest()->first();
        $lastStock = $lastData->qty_saldo;
        if(intval($request->type) == 2) {
            if ($qty_stock > $lastStock) {
                return response()->json([
                    'status' => false,
                    'message' => 'Stock tidak mencukupi!'
                ]);
            }
        }

        if($lastData) {
            $lastStock = intval($request->type) == 1 ? $lastStock + $lastData->qty_stock : $lastStock - $lastData->qty_stock;
        } else {
            $lastStock = 0;
        }

        try {
            DB::transaction(function() use ($id, $request, $lastStock){
                $input = $request->all();
                $input['qty_saldo'] = $lastStock;
                $input['kartu_stock_menu_id'] = $id;
                $input['created_by'] = Auth::user()->id;
                KartuStockMenuData::updateOrCreate(['id' => $id], $input);
            });

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan!'
            ]);

        } catch (\Throwable $th) {
           return response()->json([
               'status' => false,
               'message' => 'Data gagal disimpan!'
           ]);
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
        $retur = KartuStockMenuData::find($id);
        if(!$retur)
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan!'
            ]);

        $retur->delete();
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus!'
        ]);
    }

    public function dataTable(Request $request)
    {

        $data = KartuStockMenuData::select('kartu_stock_menu_data.*','created_by.name')
                                ->join('users as created_by', 'created_by.id', '=', 'kartu_stock_menu_data.created_by')
                                ->with(['createdBy:id,name']);
                                if(empty($request->order[0]['column'])) {
                                    $data = $data->latest();
                                }
                                $data = $data->filter($request->filter);

        return DataTables::of($data)
                            ->addindexColumn()
                            ->addColumn('action', function($data) {
                                $actionButton = '';
                                // if((Auth::user()->role == 'admin')
                                //     || ((Auth::user()->role == 'user' || !canPermission('Daftar Returan.Full_Akses', true)) && date('Y-m-d', strtotime($data->created_at)) === date('Y-m-d'))
                                //     || (canPermission('Daftar Returan.Full_Akses', true))) {
                                    $actionButton = '
                                                        <a href="javascript:void(0)" data-id="' . $data->id . '" class="btn btn-sm btn-alt-secondary btn-delete">
                                                            <i class="fa fa-fw fa-trash-alt"></i>
                                                            </span>
                                                        </a>';
                                // }
                                 // <a class="dropdown-item" href="'.route('retur.show', $data->id).'" style="white-space: nowrap">
                                                //     <i class="fa fa-eye me-1"></i>
                                                //     Detail
                                                // </a>
                                return '<div class="d-flex align-items-center " style="gap: 5px">
                                        '.$actionButton.'
                                        </div>';
                            })->rawColumns(['action',])
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
