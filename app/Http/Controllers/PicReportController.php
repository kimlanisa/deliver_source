<?php

namespace App\Http\Controllers;

use App\Models\PicReport;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class PicReportController extends Controller
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
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PicReport::select('id', 'name',);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group"><a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-edit"
                        data-bs-toggle="tooltip" data-name="'.$row->name.'" data-color="'.$row->color.'"><i class="fa fa-fw fa-pencil-alt"></i></span>';
                    $btn .= '<a href="javascript:void(0)" id="' . $row->id . '" data-id="'.$row->id.'" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-delete"
                                 data-bs-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>';
                    '</div>';
                    return $btn;
                })
                ->escapeColumns([])
                ->make(true);
        }
        return view('pic_report.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $input = $request->all();

        $data = PicReport::find($request->id);

        $validator = Validator::make($input, [
            'name' => 'required',
        ]);

        if($data)
            $validator = Validator::make($input, [
                'name' => 'required|unique:pic_reports,name,' . $data->id,
            ]);
        else
            $validator = Validator::make($input, [
                'name' => 'required',
            ]);

        if($validator->fails())
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);

        if($data)
            $data->update($input);
        else
            $data = PicReport::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
            'data' => $data->id
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (request()->ajax()) {
            $data = PicReport::FindOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PicReport $pic_report)
    {
        $dataPost = $request->all();
        $cekData = PicReport::where([
            ['id', '!=', $dataPost['id']],
            ['name', $dataPost['name']]
        ])->exists();
        if ($cekData) {
            return response()->json([
                'success' => false,
                'message' => 'Nama PIC sudah ada!'
            ]);
        }

        $pic_report->update($dataPost);
        return response()->json([
            'success' => true,
            'message' => 'Berhasil diupdate!'
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
        PicReport::find($id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus!'
        ]);
    }
}
