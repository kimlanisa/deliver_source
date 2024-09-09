<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Dompdf\Dompdf;
use \Carbon\Carbon;
use Dompdf\Options;
use App\Models\User;
use App\Models\Expedisi;
use App\Models\SerahTerima;
use Illuminate\Http\Request;
use App\Models\BlacklistDetail;
use Yajra\DataTables\DataTables;
use App\Models\SerahTerimaDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\SerahTerimaDetailTemp;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;



class SerahTerimaController extends Controller
{

    use Exportable;

    public function export(Request $request)
    {
        $type = $request->type;
        $dateStr = $request->dateStr;
        $searchBy = $request->searchBy;
        $keywordSearch = $request->keyword;
        $searchType = $request->searchType;

        $data = DB::table('serah_terimas as st')
            ->leftjoin('serah_terima_details as std', 'std.serah_terima_id', '=', 'st.id')
            ->leftjoin('expedisis as x', 'x.id', '=', 'st.expedisi_id')
            ->leftjoin('users as u', 'u.id', '=', 'st.user_id')
            ->select('st.no_tanda_terima', 'x.expedisi', 'std.no_resi', 'st.created_at', 'st.catatan')
            ->where('st.deleted_at', 0)
            ->where('std.deleted_at', 0)
            ->when($type ?? false, function ($query) use ($type, $dateStr) {
                switch ($type) {
                    case 'now':
                        return $query->whereDate('st.created_at', today());
                        break;
                    case 'yesterday':
                        return $query->whereDate('st.created_at', today()->subDays(1));
                        break;
                    case '7':
                        return $query->whereBetween('st.created_at', [today()->subWeek(), today()]);
                        break;
                    case '30':
                        $tanggalAwal = Carbon::now()->subMonth();
                        $tanggalAkhir = Carbon::now();
                        return $query->whereDate('st.created_at', '>=', $tanggalAwal)
                            ->whereDate('st.created_at', '<=', $tanggalAkhir);
                        break;
                    case 'range':
                        $date_range = explode(' to ', $dateStr);
                        return $query->whereDate('st.created_at', '>=', $date_range[0])->whereDate('st.created_at', '<=', $date_range[1] ?? $date_range[0]);
                        break;
                }
            })
            ->when($keywordSearch != null, function ($query) use ($keywordSearch, $searchBy, $searchType) {
                if ($searchBy == "1") $query->where("st.no_tanda_terima", "like", "%" . $keywordSearch . "%");
                if ($searchBy == "2") $query->where("x.expedisi", "like", "%" . $keywordSearch . "%");
                if ($searchBy == "3") $query->where("std.no_resi", "like", "%" . $keywordSearch . "%");

                if (!$searchBy) {
                    $query->where("st.no_tanda_terima", "like", "%" . $keywordSearch . "%")
                        ->orWhere("x.expedisi", "like", "%" . $keywordSearch . "%")
                        ->orWhere("std.no_resi", "like", "%" . $keywordSearch . "%");
                }
            })
            // ->groupBy('st.no_tanda_terima', 'st.created_at', 'x.expedisi', 'std.no_resi', 'st.catatan')
            // ->groupBy('st.id', 'st.no_tanda_terima', 'st.created_at', 'x.expedisi', 'st.catatan', 'x.color', 'u.name')
            ->groupBy('st.id', 'st.no_tanda_terima', 'st.created_at', 'x.expedisi', 'st.catatan', 'x.color', 'u.name', 'std.no_resi')
            ->orderBy('st.no_tanda_terima', 'DESC')
            ->get();

        $fileName = 'Data_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new ExportExcel($data), $fileName);
    }


    public function exportById($id)
    {

        $data = DB::table('serah_terimas as st')
            ->leftjoin('serah_terima_details as std', 'std.serah_terima_id', '=', 'st.id')
            ->leftjoin('expedisis as x', 'x.id', '=', 'st.expedisi_id')
            ->select('st.no_tanda_terima', 'x.expedisi', 'std.no_resi', 'st.created_at', 'st.catatan')
            ->where('st.id', $id)
            ->where('st.deleted_at', 0)
            ->where('std.deleted_at', 0)
            ->groupBy('st.no_tanda_terima', 'st.created_at', 'x.expedisi', 'std.no_resi', 'st.catatan')
            ->orderBy('st.no_tanda_terima', 'DESC')
            ->get();

        $fileName = 'Data_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new ExportExcel($data), $fileName);
    }

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
        return view('serahterima.list');
    }

    public function getDataSerahTerima(Request $request)
    {
        // $searchType = $request->searchType;

        $serahterima = SerahTerima::with(['Expedisi', 'User:id,name'])
            ->withCount(['SerahTerimaDetails as no_resi_count' => function ($query) {
                $query->select(DB::raw('count(distinct no_resi)'))
                    ->where('deleted_at', 0);
            }])
            ->join('users as user', 'user.id', '=', 'serah_terimas.user_id')
            ->join('expedisis as expedisi', 'expedisi.id', '=', 'serah_terimas.expedisi_id')
            ->filter($request);
            if(($request->order[0]['column'] ?? false) === 2) {
                $serahterima = $serahterima->orderByRaw('no_resi_count ' . $request->order[0]['dir']);
            }
            if(empty($request->order[0]['column'])) {
                $serahterima = $serahterima->latest();
             }

        if ($request->page && $request->page_size !== 'All') {
            $serahterimaPaginate = $serahterima->paginate($request->page_size, ['*'], 'page', $request->page);
            $totalPaket = $this->getTotalPaket($request, true);
            $totalCounts = $totalPaket;
            $serahterimas['data'] = $serahterimaPaginate->map(function ($item) {
                return [
                    'id' => $item->id,
                    'no_tanda_terima' => $item->no_tanda_terima,
                    'created_at' => $item->created_at,
                    'expedisi' => $item->Expedisi->expedisi ?? '',
                    'catatan' => $item->catatan,
                    'color' => $item->Expedisi->color ?? '',
                    'name' => $item->name,
                    'totalpaket' => $item->no_resi_count,
                    'name' => $item->User->name ?? '',
                    'created_at' => date('Y-m-d H:i:s', strtotime($item->created_at)),
                    'waktu_scan' => date('Y-m-d H:i:s', strtotime($item->created_at)),
                ];
            });
            $serahterimas['meta'] = [
                'current_page' => $serahterimaPaginate->currentPage(),
                'per_page' => $serahterimaPaginate->perPage(),
                'total' => $serahterimaPaginate->total(),
                'last_page' => $serahterimaPaginate->lastPage(),
            ];
            $serahterimas['full_akses'] = canPermission('Daftar Serah Terima.Full_Akses', true);
            $serahterimas['role'] = auth()->user()->role;
            $serahterimas['date_now'] = date('Y-m-d');
            $serahterimas['total_paket'] = $totalCounts;
            return response()->json($serahterimas);
        }


        return DataTables::of($serahterima)
            ->addindexColumn()
            ->addColumn('action', function ($data) {
                $actionButton = '';

                if(canPermission('Daftar Serah Terima.Full_Akses', true) || (Auth::user()->role == 'admin') || (Auth::user()->role == 'user' || !canPermission('Daftar Serah Terima.Full_Akses', true) && date('Y-m-d', strtotime($data->created_at)) >= date('Y-m-d') )) {
                    $actionButton = '
                    <button class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-print dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-fw fa-pen"></i></button>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="'.route('serahterima.create', ['id' =>  $data->id]).'" class="dropdown-item" target="_BLANK">
                                Tambah Detail
                            </a>
                        </li>
                        <li>
                            <a href="#" onclick="handlerUpdateCatatan('.$data->id.', `'.$data->catatan.'`)" class="dropdown-item">
                                Edit Catatan
                            </a>
                        </li>

                    </ul>
                    <a href="'.route('exportById', $data->id).'" id="'.$data->id.'" target="_BLANK" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-show"><i class="fa fa-fw fa-file-excel"></i></a>
                    <a href="javascript:void(0)" data-id="'.$data->id.'"
                        class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-delete"
                        data-bs-toggle="tooltip" title="Delete"><i
                            class="fa fa-fw fa-times"></i>
                    </a>';

                }
                return ' <div class="btn-group">
                            <a href="'.route('serahterima.show', $data->id).'" id="'.$data->id.'"
                                class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-show"><i
                                    class="fa fa-fw fa-eye"></i></a>
                            <div class="dropdown">
                                <button
                                    class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-print dropdown-toggle"
                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-fw fa-print"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item"
                                            href="'.route('printTandaTerimaTerm', $data->id).'"
                                            target="_BLANK">Thermal</a>
                                    </li>
                                    <li><a class="dropdown-item"
                                            href="'.route('printTandaTerima', $data->id).'"
                                            target="_BLANK">A4</a>
                                    </li>
                                </ul>
                            </div>
                            '.$actionButton.'
                        </div>';
            })
            ->editColumn('no_tanda_terima', function ($data) {
                return '<a class="fw-semibold" href="' . route('serahterima.show', $data->id) . '">' . $data->no_tanda_terima . '</a>';
            })
            ->addColumn('no_resi_count', function ($data) {
                return ' <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info fs-sm">'.$data->no_resi_count.'</span>';
            })
            ->rawColumns(['action', 'no_tanda_terima', 'no_resi_count'])
            ->smart(true)
            ->make(true);
    }

    public function getTotalPaket(Request $request, $type = false)
    {
        $totalPaket = SerahTerima::select('id')->with(['SerahTerimaDetails' => function ($query) {
            $query->select('id', 'no_resi')->where('deleted_at', 0);
        }])
            ->withCount(['SerahTerimaDetails as no_resi_count' => function ($query) {
                $query->select(DB::raw('count(distinct no_resi)'))
                    ->where('deleted_at', 0);
            }])->filter($request)->get()->sum('no_resi_count');
        if($type) {
            return $totalPaket;
        }
        return response()->json($totalPaket);
    }

    public function listTemp()
    {
        $user = Auth::user();
        $data = DB::table('serah_terima_detail_temps as std')
            ->leftjoin('expedisis as x', 'x.id', '=', 'std.expedisi_id')
            ->select('std.*', 'x.expedisi')
            ->where('std.user_id', $user->id)
            ->where('std.status', 0)
            ->get();
        return response()->json(['data' => $data]);
    }

    public function destroyTemp($id)
    {
        // $data = SerahTerimaDetailTemp::find($id);
        $data = DB::table('serah_terima_detail_temps')
            ->where('no_resi', $id)
            ->where('status', 0)
            ->delete();

        // if (!$data) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Data not found'
        //     ], 404);
        // }

        // $data->delete();

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Data deleted successfully'
        // ]);
    }

    public function deleteTempAll()
    {
        $user = Auth::user();
        $data = DB::table('serah_terima_detail_temps')
            ->where('user_id', $user->id)
            ->where('status', 0)
            ->delete();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $id = $request->get('id');
        $dataSerahTerima = "";
        if ($id) {
            $dataSerahTerima = SerahTerima::where('id', $id)->first();
        }

        // echo json_encode($dataSerahTerima);
        // die;
        $expedisi = Expedisi::all();
        return view('serahterima.add', compact('expedisi', 'dataSerahTerima'));
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
        $last = SerahTerima::orderBy('id', 'desc')->first();
        if (!$last) {
            $noTD = 'LE-' . '001';
        }

        if ($last) {
            $lastTD = substr($last->no_tanda_terima, -3);
            $nextTD = (intval($lastTD)) + 1;

            if ($nextTD < 10) {
                $nextTD = '00' . $nextTD;
            } elseif ($nextTD < 100) {
                $nextTD = '0' . $nextTD;
            }
            $noTD = 'LE-' . $nextTD;
        }

        //END GENERATE


        $data = $request->all();
        $scanDetails = json_decode($data['scanDetails'], true);
        $expedisi_id = $request->expedisi_id;


        try {
            // menyimpan data seraterima jika serah terima id null, jik ada gausah insert krena tmbh data detail
            DB::transaction(function () use ($request, $user, $date, $noTD, $expedisi_id, $scanDetails) {
                if ($request->serahTerimaId == null) {
                    $serahterima = new SerahTerima;
                    $serahterima->no_tanda_terima = $noTD;
                    $serahterima->expedisi_id = $expedisi_id;
                    $serahterima->catatan = $request->catatan;
                    $serahterima->user_id = $user->id;
                    $serahterima->deleted_at = 0;
                    $serahterima->save();
                }

                foreach ($scanDetails as $item) {
                    $serah_terima_detail = SerahTerimaDetail::where([
                        'no_resi' => $item['no_resi'],
                        'deleted_at' => 0,
                        'serah_terima_id' => $request->serahTerimaId == null ? $serahterima->id : $request->serahTerimaId,
                    ])->first() ?? new SerahTerimaDetail;

                    $serah_terima_detail->serah_terima_id = $request->serahTerimaId == null ? $serahterima->id : $request->serahTerimaId;
                    $serah_terima_detail->no_resi = $item['no_resi'];
                    $serah_terima_detail->expedisi_id = $item['expedisi_id'];
                    $serah_terima_detail->created_at = $date;
                    $serah_terima_detail->deleted_at = 0;
                    $serah_terima_detail->save();
                }
            });



            return response()->json([
                'status' => 'success',
                'message' => 'Data penjualan berhasil disimpan.'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data.'
            ]);
        }
    }




    public function scanBarcode(Request $request)
    {
        $no_resi = $request->no_resi;
        // $expedisi_id = $request->expedisi_id;

        //cek no resi
        // $cek_no = SerahTerimaDetailTemp::where('no_resi', $no_resi)->exists();
        // $cek_no_db = SerahTerimaDetail::where('deleted_at', 0)->where('no_resi', $no_resi)->exists();
        // $cek_blacklist = DB::table('blacklist_details')->where('deleted_at', 0)->where('no_resi',$no_resi)->exists();

        $cek_no_db = SerahTerimaDetail::firstWhere([
            'deleted_at' => 0,
            'no_resi' => $no_resi
        ]);

        $cek_blacklist = BlacklistDetail::firstWhere([
            'deleted_at' => 0,
            'no_resi' => $no_resi
        ]);

        // cek apakah value di select option ada atau tidak

        // if ($expedisi_id == null) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Pilih expedisi terlebih dahulu'
        //     ]);
        // }


        if (isset($cek_no_db)) {
            return response()->json([
                'success' => false,
                'code' => 1,
                'message' => 'No resi sudah ada!'
            ]);
        }

        if (isset($cek_blacklist)) {
            return response()->json([
                'success' => false,
                'code' => 2,
                'message' => 'No resi sudah terdaftar ke blacklist!'
            ]);
        }

        return response()->json([
            'success' => true,
            'code' => 0,
            'message' => 'Data berhasil disimpan!'
        ]);

        // return response()->json([
        //     'status' => 'error',
        //     'message' => 'Data gagal disimpan!'
        // ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $serahterima = SerahTerima::with(['Expedisi'])->firstWhere('id', $id);

        $detail = DB::table('serah_terima_details as std')
            ->leftjoin('serah_terimas as st', 'st.id', '=', 'std.serah_terima_id')
            ->leftjoin('expedisis as ex', 'ex.id', '=', 'st.expedisi_id')
            ->where('std.serah_terima_id', $id)
            ->where('std.deleted_at', 0)
            ->select('std.*', 'ex.expedisi', 'ex.color')
            ->get();
        $count = DB::table('serah_terima_details')
            ->select(DB::raw('count(distinct no_resi)'))
            ->where('serah_terima_id', $id)
            ->where('deleted_at', 0)
            ->count();
        if ($request->from_mobile) {
            return response()->json([
                'serahterima' => $serahterima,
                'detail' => $detail,
                'count' => $count,
                'date_now' => date('Y-m-d')
            ]);
        }
        return view('serahterima.detail', compact('serahterima', 'detail', 'count'));
    }

    public function getDetailSerahTerimaById(Request $request, $id)
    {
        $detail = DB::table('serah_terima_details as std')
            ->leftjoin('serah_terimas as st', 'st.id', '=', 'std.serah_terima_id')
            ->leftjoin('expedisis as ex', 'ex.id', '=', 'st.expedisi_id')
            ->where('std.serah_terima_id', $id)
            ->where('std.deleted_at', 0)
            ->select('std.*', 'ex.expedisi', 'ex.color')
            ->orderBy('std.created_at', 'DESC')
            ->where('std.deleted_at', 0);

        if ($request->page && $request->page_size !== 'All') {
            $detail = $detail->paginate($request->page_size, ['*'], 'page', $request->page);
            $detail = $this->dataWrapper($detail->toArray());
            return response()->json($detail);
        }

        $detail = $detail->get();
        echo json_encode($detail);
    }

    public function updateDetail(Request $request)
    {

        DB::beginTransaction();

        try {
            foreach ($request->data as $key => $data) {
                DB::table('serah_terima_details')
                    ->where('no_resi', $data['resiId'])
                    ->update([
                        'no_resi' => $data['newResi']
                    ]);
            }

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Data berhasil diupdate!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 400,
                'message' => 'Data gagal diupdate!'
            ]);
        }
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
        $serah_terima = SerahTerima::find($id);
        if (!$serah_terima) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $data = $request->all();
        $newScanner = json_decode($data['newScanner'], true);
        $scanDelete = json_decode($data['scannerDelete'] ?? '[]', true);
        $expedisi_id = $request->expedisi_id;
        $date = Carbon::now();


        try {
            // menyimpan data seraterima jika serah terima id null, jik ada gausah insert krena tmbh data detail
            DB::transaction(function () use ($request, $date, $expedisi_id, $newScanner, $serah_terima, $scanDelete) {
                if ($request->serahTerimaId == null) {
                    $serahterima = $serah_terima;
                    $serahterima->expedisi_id = $expedisi_id;
                    $serahterima->catatan = $request->catatan;
                    $serahterima->deleted_at = 0;
                    $serahterima->save();
                }

                $serah_terima_detail_delete = SerahTerimaDetail::where(['serah_terima_id' => $serah_terima->id])->whereIn('no_resi', $scanDelete)->get();
                if (count($serah_terima_detail_delete) > 0) {
                    foreach ($serah_terima_detail_delete as $item) {
                        $item->deleted_at = 1;
                        $item->save();
                    }
                }

                foreach ($newScanner as $item) {
                    // $serah_terima_detail = SerahTerimaDetail::where([
                    //     'no_resi'=> $item['no_resi'],
                    //     'deleted_at' => 0,
                    //     'serah_terima_id' => $serah_terima->id,
                    //     'id' => $item['id'] ?? 0,
                    // ])->first();
                    // if(!$serah_terima_detail) {
                    // }
                    $serah_terima_detail = new SerahTerimaDetail();
                    $serah_terima_detail->serah_terima_id = $serah_terima->id;
                    $serah_terima_detail->no_resi = $item['no_resi'];
                    $serah_terima_detail->expedisi_id = $item['expedisi_id'];
                    $serah_terima_detail->created_at = $date;
                    $serah_terima_detail->deleted_at = 0;
                    $serah_terima_detail->save();
                }
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Data serah terima berhasil disimpan.'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data.'
            ]);
        }
    }

    public function updateCatatan(Request $request)
    {
        $response = DB::table('serah_terimas')
            ->where('id', $request->id)
            ->update([
                'catatan' => $request->catatan == '' ? NULL : $request->catatan
            ]);

        if ($response) {
            return response()->json([
                'status' => 200,
                'message' => 'Data berhasil diupdate!'
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Data gagal diupdate!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('serah_terimas')
            ->where('id', $id)
            ->update([
                'deleted_at' => 1
            ]);

        DB::table('serah_terima_details')
            ->where('serah_terima_id', $id)
            ->update([
                'deleted_at' => 1
            ]);

        DB::table('log_activitis')->insert([
            'users_id' => Auth::user()->id,
            'serah_terima_id' => $id,
            'keterangan' => "delete data serah terima berdasarkan no tanda terima",
        ]);

        return response()->json(['status' => true, 'message' => 'Data berhasil dihapus!']);
        // SerahTerima::find($id)->delete();
    }

    public function destroyResi($id)
    {
        $serah_terima_detail = SerahTerimaDetail::select('id', 'serah_terima_id', 'no_resi')
                                                ->whereRaw("REPLACE(no_resi, '\n', '') = '$id'")
                                                ->first();
        $serah_terima_detail->deleted_at = 1;
        $serah_terima_detail->save();

        DB::table('log_activitis')->insert([
            'users_id' => Auth::user()->id,
            'serah_terima_id' => $serah_terima_detail->serah_terima_id,
            'serah_terima_detail_resi' => $id,
            'keterangan' => "delete data serah terima berdasarkan no resi"
        ]);
        // DB::table('serah_terima_details')->where('no_resi', $id)->delete();
        // DB::table('serah_terima_detail_temps')->where('no_resi', $id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }

    public function printTandaTerima($id)
    {

        $serahterima = DB::table('serah_terimas as st')
            ->leftjoin('expedisis as ex', 'ex.id', '=', 'st.expedisi_id')
            ->select('st.*', 'ex.expedisi')
            ->where('st.id', $id)
            ->where('st.deleted_at', 0)
            ->first();
        $detail = DB::table('serah_terima_details as std')
            ->leftjoin('serah_terimas as st', 'st.id', '=', 'std.serah_terima_id')
            ->leftjoin('expedisis as ex', 'ex.id', '=', 'st.expedisi_id')
            ->where('std.serah_terima_id', $id)
            ->where('std.deleted_at', 0)
            ->select('std.*', 'ex.expedisi')
            ->get();
        $count = DB::table('serah_terima_details')->where('serah_terima_id', $id)->where('deleted_at', 0)->count();
        $date = Carbon::now();

        $pdf = new Dompdf();
        $pdf->loadHtml(view('serahterima.cetak_tanda_terima', compact('serahterima', 'detail', 'count', 'date'))->render());
        $pdf->setPaper('A4', 'portrait', 210, 100); // 210mm x 250mm

        // Membuat objek Options
        $options = new Options();

        // Menetapkan judul file PDF dengan nomor invoice
        $options->set('title', 'Invoice-' . $serahterima->no_tanda_terima);

        // Menetapkan opsi ke objek DOMPDF
        $pdf->setOptions($options);

        $pdf->render();

        // Menetapkan judul file PDF dengan nomor invoice

        $pdf->stream('TandaTerima-' . $serahterima->no_tanda_terima . '.pdf', array("Attachment" => false));
        exit;
    }

    public function printTandaTerimaTerm($id)
    {

        $serahterima = DB::table('serah_terimas as st')
            ->leftjoin('expedisis as ex', 'ex.id', '=', 'st.expedisi_id')
            ->select('st.*', 'ex.expedisi')
            ->where('st.id', $id)
            ->where('st.deleted_at', 0)
            ->first();
        $detail = DB::table('serah_terima_details as std')
            ->leftjoin('serah_terimas as st', 'st.id', '=', 'std.serah_terima_id')
            ->leftjoin('expedisis as ex', 'ex.id', '=', 'st.expedisi_id')
            ->where('std.serah_terima_id', $id)
            ->where('std.deleted_at', 0)
            ->select('std.*', 'ex.expedisi')
            ->get();
        $count = DB::table('serah_terima_details')->where('serah_terima_id', $id)->where('deleted_at', 0)->count();
        $date = Carbon::now();

        // return view('serahterima.cetak_tanda_terima_term', compact('serahterima', 'detail', 'count', 'date'));
        // die;

        $pdf = new Dompdf();
        $pdf->loadHtml(view('serahterima.cetak_tanda_terima_term', compact('serahterima', 'detail', 'count', 'date'))->render());
        $pdf->setPaper(array(0, 0, 283.00, 567.80)); // 210mm x 250mm

        // Membuat objek Options
        $options = new Options();

        // Menetapkan judul file PDF dengan nomor invoice
        $options->set('title', 'TT-' . $serahterima->no_tanda_terima);

        // Menetapkan opsi ke objek DOMPDF
        $pdf->setOptions($options);

        $pdf->render();

        // Menetapkan judul file PDF dengan nomor invoice

        $pdf->stream('TandaTerima-' . $serahterima->no_tanda_terima . '.pdf', array("Attachment" => false));
        exit;
    }
}


class ExportExcel implements FromCollection, WithHeadings, WithColumnWidths, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'No TT',
            'Expedisi',
            'No Resi',
            'Tanggal',
            'Catatan'
            // tambahkan kolom lainnya
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 20,
            'C' => 30,
            'D' => 20,
            'E' => 30,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => [
                'font' => [
                    'size' => 14,
                ]
            ],
        ];
    }
}
