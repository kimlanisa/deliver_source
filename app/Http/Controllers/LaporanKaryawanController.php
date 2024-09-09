<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanKaryawan;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\LaporanKaryawanDetail;

class LaporanKaryawanController extends Controller
{
    public function index()
    {
        $laporanKaryawan = LaporanKaryawan::all();
        return view('laporan_karyawan.index', compact('laporanKaryawan'));
    }

    public function create()
    {
        return view('laporan_karyawan.create-update');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pekerjaan' => 'required',
            'date' => 'required',
        ]);

        $input = $request->all();
        $data_input = json_decode($input['pekerjaan'], true);
        $id = $request->id;

        try {
            DB::transaction(function() use ($input, $id, $data_input) {
                if($id) {
                    $laporanKaryawan = LaporanKaryawan::findOrFail($id);
                    $laporanKaryawan->fill([
                        'updated_by_id' => auth()->id(),
                    ]);
                } else {
                    $laporanKaryawan = new LaporanKaryawan();
                    $no_laporan = LaporanKaryawan::generateNomorTransaksi();
                    $laporanKaryawan->fill([
                        'user_id' => auth()->id(),
                        'created_by_id' => auth()->id(),
                        'no_laporan' => $no_laporan,
                    ]);
                }
                $laporanKaryawan->date = $input['date'];
                $laporanKaryawan->pic_id = $input['pic_id'];
                $laporanKaryawan->save();

                $images = [];
                foreach ($data_input ?? [] as $key => $item) {
                    // preg_match_all('/<img[^>]+src="([^">]+)"/i', $item['pekerjaan'] ?? '', $matches);
                    // foreach ($matches[1] as $match) {
                    //     $images[$key][] = $match;
                    // }

                    $attachment_files = $input['attachment_file'][$item['id']] ?? [];
                    if($attachment_files) {
                        foreach ($attachment_files as $attachment_file) {
                            $file = [];
                            if(is_file($attachment_file)) {
                                $file = [
                                    'name' => $attachment_file->getClientOriginalName(),
                                    'size' => $attachment_file->getSize(),
                                    'type' => $attachment_file->getMimeType(),
                                    'extension' => $attachment_file->getClientOriginalExtension(),
                                    'url' => upload_image($attachment_file, 'laporan_karyawan', 'laporan_karyawan'),
                                ];
                            } else {
                                $file = json_decode($attachment_file);
                            }

                           $images[$key][] = $file;
                        }
                    }

                    $laporanKaryawanDetail = LaporanKaryawanDetail::find($item['id_pekerjaan']) ?? new LaporanKaryawanDetail();
                    $laporanKaryawanDetail->pekerjaan = $item['pekerjaan'] ?? '';
                    $laporanKaryawanDetail->status = $item['status'] ?? '';
                    $laporanKaryawanDetail->laporan_karyawan_id = $laporanKaryawan->id ?? null;
                    $laporanKaryawanDetail->images = json_encode($images[$key] ?? []);
                    $laporanKaryawanDetail->save();
                }

                if($input['id_delete'] ?? []) {
                    $input['id_delete'] = explode(',', $input['id_delete']);
                    LaporanKaryawanDetail::whereIn('id', $input['id_delete'])->delete();
                }

            });

            return response()->json(['status'=> true,'message' => 'Laporan Karyawan berhasil disimpan.']);
        } catch (\Exception $e) {
            return response()->json(['status'=> false,'message' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        if((Auth::user()->role == 'admin' || canPermission('Laporan Karyawan.Full_Akses')) || ((Auth::user()->role == 'user' || !canPermission('Laporan Karyawan.Full_Akses')) && date('Y-m-d', strtotime($data->created_at)) >= date('Y-m-d'))) {
            $data = LaporanKaryawan::with('laporanKaryawanDetail');

            if(!canPermission('Laporan Karyawan.Full_Akses') && !Auth::user()->role !== 'admin') {
                $data = $data->where('created_by_id', auth()->id());
            }

            $data = $data->findOrFail($id);
            return view('laporan_karyawan.create-update', compact('data'));
        }
        return abort(404);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pekerjaan' => 'required|array',
            'status' => 'required|array',
        ]);

        $laporanKaryawan = LaporanKaryawan::findOrFail($id);
        $laporanKaryawan->update([
            'pekerjaan' => $request->pekerjaan,
            'status' => $request->status,
        ]);

        return redirect()->route('laporan-karyawan.index')->with('success', 'Laporan Karyawan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DB::transaction(function() use ($id) {
            $laporanKaryawan = LaporanKaryawan::findOrFail($id);
            $laporanKaryawan->laporanKaryawanDetail()->delete();
            $laporanKaryawan->delete();
        });

        return response()->json(['status'=> true,'message' => 'Laporan Karyawan berhasil dihapus.']);
    }

    public function show($id, Request $request)
    {
        $data = LaporanKaryawan::with(['laporanKaryawanDetail', 'picReport', 'updatedBy'])->findOrFail($id);
        $show_detail_modal = $request->show_detail_modal ?? false;
        if(!$show_detail_modal) {
            return view('laporan_karyawan.show_page', compact('data', 'show_detail_modal'));
        }

        return [
            'view' => view('laporan_karyawan.show', compact('data'))->render(),
            'data' => $data,
        ];
    }


    public function dataTable(Request $request)
    {
        $data = LaporanKaryawan::select('laporan_karyawans.*', 'created_by.name as created_by_name')
                                ->with('laporanKaryawanDetail')
                                ->join('users as created_by', 'created_by.id', '=', 'laporan_karyawans.created_by_id');
                                if(empty($request->order[0]['column'])) {
                                    $data = $data->orderBy('id', 'desc');
                                }
                                $data = $data->filter($request->filter);

        if(!canPermission('Laporan Karyawan.Full_Akses') && !Auth::user()->role !== 'admin') {
            $data = $data->where('laporan_karyawans.created_by_id', auth()->id());
        }

        return DataTables::of($data)
                            ->addindexColumn()
                            ->addColumn('no_laporan', function($data) {
                                return '<a href="javascript:void(0)" style="cursor: pointer" class="detailData" data-id="'.$data->id.'" style="white-space: nowrap">'.$data->no_laporan.'</a>';
                            })
                            ->addColumn('pekerjaan', function($data) {
                                return '';
                            })
                            ->addColumn('action', function($data) {
                                $actionButton = '';
                                $statusButton = '';
                                if((Auth::user()->role == 'admin' || canPermission('Laporan Karyawan.Full_Akses')) || ((Auth::user()->role == 'user' || !canPermission('Laporan Karyawan.Full_Akses')) && date('Y-m-d', strtotime($data->created_at)) >= date('Y-m-d'))) {
                                    $actionButton = ' <a class="dropdown-item" href="'.route('laporan-karyawan.edit', $data->id).'">
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
                                            <a
                                            href="'.route('laporan-karyawan.show', $data->id).'"
                                            class="btn btn-sm btn-alt-secondary ">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <div class="dropdown">
                                            <button
                                                class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-print"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                           '.$actionButton.'
                                            </ul>
                                            </button>
                                        </div>';
                            })->rawColumns(['action', 'no_laporan', 'pekerjaan'])
                           ->smart(true)
                           ->make(true);
    }
}
