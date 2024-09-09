<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Role::latest()->get();
        return view('role_permission.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::orderBy('name', 'asc')->get();
        return view('role_permission.create-update', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->id)
            $request->validate([
                'name' => 'required|unique:roles,name,' . $request->id,
                'permission' => 'required',
            ]);
        else
            $request->validate([
                'name' => 'required|unique:roles,name',
                'permission' => 'required',
            ]);

        if($request->id){
            $role = Role::find($request->id);
            $role->name = $request->name;
            $role->save();
        }else{
            $role = Role::create(['name' => $request->name]);
        }

        $permission = $request->permission;
        if($permission){
            $role->revokePermissionTo($permission);
            $role->syncPermissions($permission);
        }else{
            $role->revokePermissionTo($permission);
        }

        return redirect(route('role-permission.index'))->with('success', 'Data berhasil disimpan!')->withInput();
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
        $data = Role::findOrFail($id);
        $permission = Permission::orderBy('name', 'asc')->get();
        return view('role_permission.create-update', compact('permission', 'data'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
