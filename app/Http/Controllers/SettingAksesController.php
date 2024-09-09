<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Auth;

class SettingAksesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($request->ajax()) {
            $data = User::select('*')
                ->where('role', 'user')
                ->orderBy('created_at', 'DESC');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $chkAkses = $row->is_akses == 0 ? '' : 'checked';
                    $btn = '<div class="button r" id="button-1"><input type="checkbox" ' . $chkAkses . ' class="checkbox" onchange="handlerSettingAksesUser(event, ' . $row->id . ')" /><div class="knobs"></div><div class="layer"></div></div>';
                    return $btn;
                })
                ->addColumn('last_update_akses', function ($row) {
                    return ($row->last_updated_akses == null || $row->last_updated_akses == "") ? '-' : date('d F Y H:i', strtotime($row->last_updated_akses));
                })
                ->escapeColumns([])
                ->make(true);
        }
        return view('settingakses.list', compact('user'));
    }

    public function update(Request $request)
    {
        $result = DB::table('users')
            ->where('id', $request->idUser)
            ->update([
                'is_akses' => $request->type == 'false' ? 0 : 1,
                'last_updated_akses' => date('Y-m-d H:i:s')
            ]);

        if ($result > 0) {
            $response = [
                'status' => true,
                'message' => 'update akses users successfuly',
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'update akses users failes',
            ];
        }

        echo json_encode($response);
    }
}
