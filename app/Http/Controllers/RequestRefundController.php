<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use \Carbon\Carbon;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Models\RefundRequest;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RequestRefundController extends Controller
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

        return view('permintaan_refund.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $shops = Shop::orderBy('name', 'ASC')->get();
        return view('permintaan_refund.create-update', compact('shops'));
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
            'no_pesanan' => $request->id ? 'required|unique:refund_requests,no_pesanan,'.$request->id : 'required|unique:refund_requests,no_pesanan',
            'nominal_refund' => 'required',
            'customer' => 'required',
            'no_rekening' => 'required',
            'nama_bank' => 'required',
            'nama_pemilik_rekening' => 'required',
            'alasan_refund' => 'required',
        ]);

        $input = $request->all();

        try {
            DB::transaction(function() use ($input, $id, $request){
                if(!$request->id)
                    $input['no_trx'] = RefundRequest::generateNomorTransaksi();
                $input['nominal_refund'] = str_replace('.', '', $input['nominal_refund']);
                $input['created_by'] = Auth::user()->id;
                RefundRequest::updateOrCreate(['id' => $id], $input);
            });

            return redirect(route('request-refund.index'))->with('success', 'Data berhasil disimpan!');

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
        $data = RefundRequest::findOrFail($id);
        return view('permintaan_refund.show', compact( 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = RefundRequest::findOrFail($id);
        $shops = Shop::orderBy('name', 'ASC')->get();
        return view('permintaan_refund.create-update', compact('shops', 'data'));
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
        $data = RefundRequest::find($id);
        if(!$data)
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan!'
            ]);

        if($request->lampiran_refund) {
            $validator = Validator::make(request()->all(), [
                'lampiran_refund' => 'required|mimes:jpeg,jpg,png|max:4048',
             ]);

            $bukti_refund = '';
            if($request->hasFile('lampiran_refund')) {
                $file = $request->file('lampiran_refund');
                $filename = 'lampiran_refund' . time(). rand(1,9999) .'.' . $file->getClientOriginalExtension();
                $destinationPath = 'uploads/file/' . 'lampiran_refund';
                $file->move($destinationPath, $filename);
                $bukti_refund = $destinationPath . '/' . $filename;
            }

            $data->update([
                'lampiran_refund' => $bukti_refund
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate!'
            ]);

        }

        if($request->status) {

            $data->update([
                'status' => 3,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate!'
            ]);
        }

        $validator = Validator::make(request()->all(), [
           'date_acc_refund' => 'required',
           'bukti_refund' => 'required|mimes:jpeg,jpg,png|max:4048',
        ]);
        if($validator->fails())
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);

        $bukti_refund = '';
        if($request->hasFile('bukti_refund')) {
            $file = $request->file('bukti_refund');
            $filename = 'bukti_refund' . time(). rand(1,9999) .'.' . $file->getClientOriginalExtension();
            $destinationPath = 'uploads/file/' . 'bukti_refund';
            $file->move($destinationPath, $filename);
            $bukti_refund = $destinationPath . '/' . $filename;
        }

        $data->update([
            'date_acc_refund' => $request->date_acc_refund . ' ' . date('H:i:s'),
            'acc_refund_by' => Auth::user()->id,
            'bukti_refund' => $bukti_refund,
            'status' => $data->status == 1 ? 2 : 1,
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
        $retur = RefundRequest::find($id);
        if(!$retur)
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan!'
            ]);

        DB::transaction(function() use ($retur){
            DB::table('log_activitis')->insert([
                'users_id' => Auth::user()->id,
                'no_ref' => $retur->no_trx,
                // 'no_resi' => $retur->no_resi,
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
        $data = RefundRequest::select(
                            'refund_requests.id',
                            'no_trx',
                            'date',
                            'shop_id',
                            'no_pesanan',
                            'customer',
                            'alasan_refund',
                            'nominal_refund',
                            'created_by',
                            'date_acc_refund',
                            'no_rekening',
                            'nama_bank',
                            'nama_pemilik_rekening',
                            'bukti_refund',
                            'acc_refund_by',
                            'status',
                            'refund_requests.created_at',
                            'lampiran_refund',
                            'user.name',
                        )
                        ->with(['shop:id,name,color', 'user:id,name', 'accRefundBy:id,name'])
                        ->join('users as user', 'user.id', '=', 'refund_requests.created_by');
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
                            ->addColumn('nominal_refund', function($data) {
                                return "<span style='font-weight: bold; color: red'>
                                ".number_format($data->nominal_refund, 0, ',', '.')."
                                </span>";
                            })
                            ->addColumn('action', function($data) {
                                $btnApprove = '';
                                $bukti_refund = '';
                                $actionProcess = '';
                                $actionBuy = '';
                                if($data->status == 1) {
                                    $actionProcess = '';
                                    $actionBuy = '';
                                    if(Auth::user()->role == 'admin' || canPermission('Daftar Request Refund.Full_Akses', true)) {
                                        $actionBuy = '<a href="javascript:void(0)" data-no_trx="'.$data->no_trx.'" data-id="'.$data->id.'" class="actionBuy btn btn-'.($data->status == 1 ? 'primary' : 'success').'" style="white-space: nowrap; font-size:13px; border-radius: 9px">
                                                        '.($data->status == 1 ? 'Bayar' : 'Tandai Belum Selesai').'
                                                    </a>';
                                    }
                                } else {
                                    $bukti_refund = '<div>
                                    <div class="text-success" style="white-space: nowrap">
                                    Diacc oleh '. ($data->accRefundBy->name ?? '') . '<br/> pada <span style="font-weight: bold">' . Carbon::parse($data->date_acc_refund)->format('d-m-Y H:i:s') . '</span>
                                    </div>
                                    <div class="text-primary mt-2 refundDetail" data-img="'.asset($data->bukti_refund).'" style="font-weight: bold; white-space: nowrap; cursor: pointer">
                                        Lihat Bukti Refund
                                    </div>
                                    </div>
                                    ';
                                }

                                if($data->status == 2) {
                                    $actionProcess = '<a href="javascript:void(0)" data-id="'.$data->id.'" class="actionDone btn btn-primary" style="white-space: nowrap; font-size:13px; border-radius: 9px">
                                                    Tandai Selesai
                                                </a>';
                                }

                                if(((Auth::user()->role == 'user' || !canPermission('Daftar Request Refund.Full_Akses', true)) && $data->status == 1) || (Auth::user()->role == 'admin' || canPermission('Daftar Request Refund.Full_Akses', true))) {
                                    $btnApprove = '
                                    <div class="dropdown">
                                    <button
                                        class="js-bs-tooltip-enabled btn btn-sm btn-alt-secondary "
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                    <div class="dropdown-item detailData" data-id="'.$data->id.'" style="white-space: nowrap; cursor: pointer">
                                        <i class="fa fa-eye me-1"></i>
                                        Detail
                                    </div>
                                    <a class="dropdown-item" href="'.route('request-refund.edit', $data->id).'" class="dropdown-item">
                                        <i class="fa fa-fw fa-edit"></i>
                                        Edit
                                    </a>
                                    <a href="javascript:void(0)" data-id="'.$data->id.'"
                                        class="dropdown-item js-bs-tooltip-enabled btn-delete"
                                        data-bs-toggle="tooltip" title="Delete"><i
                                            class="fa fa-fw fa-trash-alt"></i>
                                        Hapus
                                    </a>
                                    </ul>
                                    </div>
                                    '.$actionBuy.'
                                    ';
                                }
                                return '<div class="d-flex align-items-center gap-2">
                                           '.$bukti_refund.'
                                           '.$btnApprove.'
                                           '.$actionProcess.'
                                        </div>';
                            })->rawColumns(['action', 'no_trx', 'nominal_refund', 'no_pesanan'])
                           ->smart(true)
                           ->make(true);
    }

    public function export(Request $request)
    {
        $data = RefundRequest::select(
                                        'id',
                                        'no_trx',
                                        'date',
                                        'shop_id',
                                        'no_pesanan',
                                        'customer',
                                        'alasan_refund',
                                        'nominal_refund',
                                        'created_by',
                                        'date_acc_refund',
                                        'no_rekening',
                                        'nama_bank',
                                        'nama_pemilik_rekening',
                                        'bukti_refund',
                                        'acc_refund_by',
                                        'status',
                                        'created_at'
                                    )
                                    ->with(['shop:id,name,color', 'user:id,name', 'accRefundBy:id,name'])
                                    ->orderBY('status', 'asc')
                                    ->orderBy('id', 'desc')
                                    ->filter($request->all())
                                    ->get();

        // return view('permintaan_refund.exportExcel', compact('data'));
        return response(view('permintaan_refund.exportExcel', compact('data')))
        ->header('Content-Type', 'application/vnd-ms-excel')
        ->header('Content-Disposition', 'attachment; filename="' . 'Laporan Request Refund ('.date('d F Y').').xls"');
    }
}
