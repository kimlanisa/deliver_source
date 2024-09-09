<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use App\Models\Expedisi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ExpedisiController extends Controller
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
            $data = Expedisi::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('expedisi', function ($row) {
                    $color = "";
                    if ($row->color == null) $color .= '#2B4C99';
                    else $color .= $row->color;
                    $btn = '<span class="badge" style="background: ' . $color . ';color:white">' . $row->expedisi . '</span>';
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group"><a href="javascript:void(0)" id="' . $row->id . '" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-edit-ex"
                        data-bs-toggle="tooltip" title="Edit"><i class="fa fa-fw fa-pencil-alt"></i></span>';
                    $btn .= '<a href="javascript:void(0)" id="' . $row->id . '" class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-delete"
                                 data-bs-toggle="tooltip" title="Delete"><i class="fa fa-fw fa-times"></i></a>';
                    '</div>';
                    return $btn;
                })
                ->escapeColumns([])
                ->make(true);
        }
        return view('expedisi.list');
    }

    public function list(Request $request)
    {
        $data = Expedisi::orderBy('expedisi', 'asc')->get();
        return response()->json($data);
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

        $dataPost = $request->all();
        $cekData = Expedisi::where('expedisi', $dataPost['expedisi'])->exists();
        if ($cekData) {
            return response()->json([
                'success' => false,
                'message' => 'Expedisi sudah ada!'
            ]);
        }

        $dataPost['prefix'] = json_encode($dataPost['prefix'] ?? []);
        Expedisi::create($dataPost);
        return response()->json([
            'success' => true,
            'message' => 'Berhasil disimpan!'
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
            $data = Expedisi::FindOrFail($id);
            $data->prefix = json_decode($data->prefix);
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
    public function update(Request $request, Expedisi $expedisi)
    {
        $dataPost = $request->all();
        $validator = Validator::make($dataPost, [
            'expedisi' => 'required|unique:expedisis,expedisi,' . $expedisi->id . ',id',
            'color' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        // $cekData = Expedisi::where([
        //     ['id', '!=', $dataPost['id']],
        //     ['expedisi', $dataPost['expedisi']]
        // ])->exists();
        // if ($cekData) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Expedisi sudah ada!'
        //     ]);
        // }

        $dataPost['prefix'] = json_encode($dataPost['prefix'] ?? []);
        $expedisi->update($dataPost);
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
        Expedisi::find($id)->delete();
    }
}
