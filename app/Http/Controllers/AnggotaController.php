<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;

class AnggotaController extends Controller
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
        $user = Auth::user();

        if (($user->role =='62')){
            $anggota = DB::table('anggotas as agt')
            ->leftjoin('desas as ds','ds.id','=','agt.desa_id')
            ->leftjoin('status_pribadis as sp','sp.id','=','agt.status_pribadi_id')
            ->select('agt.*','ds.kd_desa','ds.desa','sp.kd_status','sp.status_pribadi',DB::raw('YEAR(CURDATE())-YEAR(agt.tgl_lahir) AS usia'))
            ->orderBy('agt.desa_id', 'ASC')
            ->get();
        }

        if (($user->role =='71')){
            $anggota = DB::table('anggotas as agt')
            ->leftjoin('desas as ds','ds.id','=','agt.desa_id')
            ->leftjoin('status_pribadis as sp','sp.id','=','agt.status_pribadi_id')
            ->select('agt.*','ds.kd_desa','ds.desa','sp.kd_status','sp.status_pribadi',DB::raw('YEAR(CURDATE())-YEAR(agt.tgl_lahir) AS usia'))
            ->where('agt.desa_id',1)
            ->orderBy('no_index', 'ASC')
            ->get();
        }

        if (($user->role =='72')){
            $anggota = DB::table('anggotas as agt')
            ->leftjoin('desas as ds','ds.id','=','agt.desa_id')
            ->leftjoin('status_pribadis as sp','sp.id','=','agt.status_pribadi_id')
            ->select('agt.*','ds.kd_desa','ds.desa','sp.kd_status','sp.status_pribadi',DB::raw('YEAR(CURDATE())-YEAR(agt.tgl_lahir) AS usia'))
            ->where('agt.desa_id',2)
            ->orderBy('no_index', 'ASC')
            ->get();
        }

        if (($user->role =='73')){
            $anggota = DB::table('anggotas as agt')
            ->leftjoin('desas as ds','ds.id','=','agt.desa_id')
            ->leftjoin('status_pribadis as sp','sp.id','=','agt.status_pribadi_id')
            ->select('agt.*','ds.kd_desa','ds.desa','sp.kd_status','sp.status_pribadi',DB::raw('YEAR(CURDATE())-YEAR(agt.tgl_lahir) AS usia'))
            ->where('agt.desa_id',3)
            ->orderBy('no_index', 'ASC')
            ->get();
        }

        if (($user->role =='74')){
            $anggota = DB::table('anggotas as agt')
            ->leftjoin('desas as ds','ds.id','=','agt.desa_id')
            ->leftjoin('status_pribadis as sp','sp.id','=','agt.status_pribadi_id')
            ->select('agt.*','ds.kd_desa','ds.desa','sp.kd_status','sp.status_pribadi',DB::raw('YEAR(CURDATE())-YEAR(agt.tgl_lahir) AS usia'))
            ->where('agt.desa_id',4)
            ->orderBy('no_index', 'ASC')
            ->get();
        }

        if (($user->role =='75')){
            $anggota = DB::table('anggotas as agt')
            ->leftjoin('desas as ds','ds.id','=','agt.desa_id')
            ->leftjoin('status_pribadis as sp','sp.id','=','agt.status_pribadi_id')
            ->select('agt.*','ds.kd_desa','ds.desa','sp.kd_status','sp.status_pribadi',DB::raw('YEAR(CURDATE())-YEAR(agt.tgl_lahir) AS usia'))
            ->where('agt.desa_id',5)
            ->orderBy('no_index', 'ASC')
            ->get();
        }

        if (($user->role =='76')){
            $anggota = DB::table('anggotas as agt')
            ->leftjoin('desas as ds','ds.id','=','agt.desa_id')
            ->leftjoin('status_pribadis as sp','sp.id','=','agt.status_pribadi_id')
            ->select('agt.*','ds.kd_desa','ds.desa','sp.kd_status','sp.status_pribadi',DB::raw('YEAR(CURDATE())-YEAR(agt.tgl_lahir) AS usia'))
            ->where('agt.desa_id',6)
            ->orderBy('created_at', 'DESC')
            ->get();
        }

        
        if ($user->role =='62'){
            $countAnggota = DB::table('anggotas')
                     ->count();
            $countRijal = DB::table('anggotas')
                    ->where('anggotas.r_n','R')
                    ->count();
            $countNisa = DB::table('anggotas')
            ->where('anggotas.r_n','N')
            ->count();

            $count_r_1 = DB::table('anggotas as agt')
                    ->where('agt.r_n','R')
                    ->where('agt.kelas',1)
                    ->count();
            $count_n_1 = DB::table('anggotas as agt')
            ->where('agt.r_n','N')
            ->where('agt.kelas',1)
            ->count();

            $count_r_2 = DB::table('anggotas as agt')
                    ->where('agt.r_n','R')
                    ->where('agt.kelas',2)
                    ->count();
            $count_n_2 = DB::table('anggotas as agt')
            ->where('agt.r_n','N')
            ->where('agt.kelas',2)
            ->count();

            $count_r_3 = DB::table('anggotas as agt')
                    ->where('agt.r_n','R')
                    ->where('agt.kelas',3)
                    ->count();
            $count_n_3 = DB::table('anggotas as agt')
            ->where('agt.r_n','N')
            ->where('agt.kelas',3)
            ->count();

        
        } 


        if ($user->role =='71'){
            $countAnggota = DB::table('anggotas')
                        ->where('anggotas.desa_id',1)
                        ->count();
            $countRijal = DB::table('anggotas')
                        ->where('anggotas.desa_id',1)
                        ->where('anggotas.r_n','R')
                        ->count();
            $countNisa = DB::table('anggotas')
                        ->where('anggotas.desa_id',1)
                        ->where('anggotas.r_n','N')
                        ->count();

                $count_r_1 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',1)
                        ->where('agt.r_n','R')
                        ->where('agt.kelas',1)
                        ->count();

                $count_n_1 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',1)
                        ->where('agt.r_n','N')
                        ->where('agt.kelas',1)
                        ->count();
    
                $count_r_2 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',1)
                        ->where('agt.r_n','R')
                        ->where('agt.kelas',2)
                        ->count();

                $count_n_2 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',1)
                        ->where('agt.r_n','N')
                        ->where('agt.kelas',2)
                        ->count();
    
                $count_r_3 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',1)
                        ->where('agt.r_n','R')
                        ->where('agt.kelas',3)
                        ->count();
                $count_n_3 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',1)
                        ->where('agt.r_n','N')
                        ->where('agt.kelas',3)
                        ->count();
            }

        if ($user->role =='72'){
            $countAnggota = DB::table('anggotas')
                        ->where('anggotas.desa_id',2)
                        ->count();
            $countRijal = DB::table('anggotas')
                        ->where('anggotas.desa_id',2)
                        ->where('anggotas.r_n','R')
                        ->count();
            $countNisa = DB::table('anggotas')
                        ->where('anggotas.desa_id',2)
                        ->where('anggotas.r_n','N')
                        ->count();

                $count_r_1 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',2)
                        ->where('agt.r_n','R')
                        ->where('agt.kelas',1)
                        ->count();

                $count_n_1 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',2)
                        ->where('agt.r_n','N')
                        ->where('agt.kelas',1)
                        ->count();
    
                $count_r_2 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',2)
                        ->where('agt.r_n','R')
                        ->where('agt.kelas',2)
                        ->count();

                $count_n_2 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',2)
                        ->where('agt.r_n','N')
                        ->where('agt.kelas',2)
                        ->count();
    
                $count_r_3 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',2)
                        ->where('agt.r_n','R')
                        ->where('agt.kelas',3)
                        ->count();
                $count_n_3 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',2)
                        ->where('agt.r_n','N')
                        ->where('agt.kelas',3)
                        ->count();

            }

        if ($user->role =='73'){
            $countAnggota = DB::table('anggotas')
                        ->where('anggotas.desa_id',3)
                        ->count();
            $countRijal = DB::table('anggotas')
                        ->where('anggotas.desa_id',3)
                        ->where('anggotas.r_n','R')
                        ->count();
            $countNisa = DB::table('anggotas')
                        ->where('anggotas.desa_id',3)
                        ->where('anggotas.r_n','N')
                        ->count();

                $count_r_1 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',2)
                        ->where('agt.r_n','R')
                        ->where('agt.kelas',1)
                        ->count();

                $count_n_1 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',3)
                        ->where('agt.r_n','N')
                        ->where('agt.kelas',1)
                        ->count();
    
                $count_r_2 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',3)
                        ->where('agt.r_n','R')
                        ->where('agt.kelas',2)
                        ->count();

                $count_n_2 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',3)
                        ->where('agt.r_n','N')
                        ->where('agt.kelas',2)
                        ->count();
    
                $count_r_3 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',3)
                        ->where('agt.r_n','R')
                        ->where('agt.kelas',3)
                        ->count();
                $count_n_3 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',3)
                        ->where('agt.r_n','N')
                        ->where('agt.kelas',3)
                        ->count();

            }
        if ($user->role =='74'){
            $countAnggota = DB::table('anggotas')
                        ->where('anggotas.desa_id',4)
                        ->count();
            $countRijal = DB::table('anggotas')
                        ->where('anggotas.desa_id',4)
                        ->where('anggotas.r_n','R')
                        ->count();
            $countNisa = DB::table('anggotas')
                        ->where('anggotas.desa_id',4)
                        ->where('anggotas.r_n','N')
                        ->count();


                $count_r_1 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',4)
                        ->where('agt.r_n','R')
                        ->where('agt.kelas',1)
                        ->count();

                $count_n_1 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',4)
                        ->where('agt.r_n','N')
                        ->where('agt.kelas',1)
                        ->count();
    
                $count_r_2 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',4)
                        ->where('agt.r_n','R')
                        ->where('agt.kelas',2)
                        ->count();

                $count_n_2 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',4)
                        ->where('agt.r_n','N')
                        ->where('agt.kelas',2)
                        ->count();
    
                $count_r_3 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',4)
                        ->where('agt.r_n','R')
                        ->where('agt.kelas',3)
                        ->count();
                $count_n_3 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',4)
                        ->where('agt.r_n','N')
                        ->where('agt.kelas',3)
                        ->count();
            }
        if ($user->role =='75'){
            $countAnggota = DB::table('anggotas')
                        ->where('anggotas.desa_id',5)
                        ->count();
            $countRijal = DB::table('anggotas')
                        ->where('anggotas.desa_id',5)
                        ->where('anggotas.r_n','R')
                        ->count();
            $countNisa = DB::table('anggotas')
                        ->where('anggotas.desa_id',5)
                        ->where('anggotas.r_n','N')
                        ->count();

                $count_r_1 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',5)
                        ->where('agt.r_n','R')
                        ->where('agt.kelas',1)
                        ->count();

                $count_n_1 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',5)
                        ->where('agt.r_n','N')
                        ->where('agt.kelas',1)
                        ->count();
    
                $count_r_2 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',5)
                        ->where('agt.r_n','R')
                        ->where('agt.kelas',2)
                        ->count();

                $count_n_2 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',5)
                        ->where('agt.r_n','N')
                        ->where('agt.kelas',2)
                        ->count();
    
                $count_r_3 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',5)
                        ->where('agt.r_n','R')
                        ->where('agt.kelas',3)
                        ->count();
                $count_n_3 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',5)
                        ->where('agt.r_n','N')
                        ->where('agt.kelas',3)
                        ->count();
            }
        if ($user->role =='76'){
            $countAnggota = DB::table('anggotas')
                        ->where('anggotas.desa_id',6)
                        ->count();
            $countRijal = DB::table('anggotas')
                        ->where('anggotas.desa_id',6)
                        ->where('anggotas.r_n','R')
                        ->count();
            $countNisa = DB::table('anggotas')
                        ->where('anggotas.desa_id',6)
                        ->where('anggotas.r_n','N')
                        ->count();

                $count_r_1 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',6)
                        ->where('agt.r_n','R')
                        ->where('agt.kelas',1)
                        ->count();

                $count_n_1 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',6)
                        ->where('agt.r_n','N')
                        ->where('agt.kelas',1)
                        ->count();
    
                $count_r_2 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',6)
                        ->where('agt.r_n','R')
                        ->where('agt.kelas',2)
                        ->count();

                $count_n_2 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',6)
                        ->where('agt.r_n','N')
                        ->where('agt.kelas',2)
                        ->count();
    
                $count_r_3 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',6)
                        ->where('agt.r_n','R')
                        ->where('agt.kelas',3)
                        ->count();
                $count_n_3 = DB::table('anggotas as agt')
                        ->where('agt.desa_id',6)
                        ->where('agt.r_n','N')
                        ->where('agt.kelas',3)
                        ->count();
            }

        return view('anggota.list',compact('user','anggota','count_r_1','count_n_1','count_r_2','count_n_2','count_r_3','count_n_3',
                                    'countAnggota','countRijal','countNisa'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $desa = DB::table('desas')
                ->where('kd_desa',$user->role)
                ->first();

        $desaAll=DB::table('desas')->get();
        

        $statuspribadi = DB::table('status_pribadis')->get();
        return view('anggota.add',compact('desa','statuspribadi','user','desaAll'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Anggota::create($request->all());

        return redirect()->route('anggota.index')->with('success',"Data berhasil disimpan!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(request()->ajax()) {
                $data = DB::table('anggotas as agt')
                        ->leftjoin('desas as ds','ds.id','=','agt.desa_id')
                        ->leftjoin('status_pribadis as sp','sp.id','=','agt.status_pribadi_id')
                        ->select('agt.*','ds.kd_desa','ds.desa','sp.kd_status','sp.status_pribadi',DB::raw('YEAR(CURDATE())-YEAR(agt.tgl_lahir) AS usia'))
                        ->where('agt.id',$id)
                        ->first();
                return response()->json(['data'=>$data]);
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
        
        $user = Auth::user();
        $desa = DB::table('desas')
                ->where('kd_desa',$user->role)
                ->first();

        $desaAll=DB::table('desas')->get();
        $statuspribadi = DB::table('status_pribadis')->get();
        $anggota = DB::table('anggotas as agt')
                    ->leftjoin('desas as ds','ds.id','=','agt.desa_id')
                    ->leftjoin('status_pribadis as sp','sp.id','=','agt.status_pribadi_id')
                    ->select('agt.*','ds.kd_desa','ds.desa','sp.kd_status','sp.status_pribadi')
                    ->where('agt.id',$id)
                    ->first();
        return view('anggota.update',compact('anggota','desa','user','statuspribadi','desaAll'));
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
        $user_id = Auth::id();
        $anggota = Anggota::findOrFail($id);
        $anggota->nama = $request->nama;
        $anggota->desa_id = $request->desa_id;
        $anggota->r_n = $request->r_n;
        $anggota->tgl_lahir = $request->tgl_lahir;
        $anggota->tgl_masuk = $request->tgl_masuk;
        $anggota->kelas = $request->kelas;
        $anggota->status_pribadi_id = $request->status_pribadi_id;
        $anggota->status_kondisi = $request->status_kondisi;
        $anggota->alamat = $request->alamat;
        $anggota->no_hp = $request->no_hp;
        $anggota->pj = $request->pj;
        $anggota->keterangan = $request->keterangan;

        $anggota->save();
        return redirect()->route('anggota.index')->with('success',"Data berhasil diubah!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Anggota::find($id)->delete();
    }
}
