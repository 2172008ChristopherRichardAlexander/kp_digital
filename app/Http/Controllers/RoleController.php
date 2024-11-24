<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;

use App\Role;
use App\Http\Requests;
use App\Http\Resources\Role as RoleResource;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Role
        $role = Role::paginate(5);
        // Return collection of Role as a resource
        return RoleResource::collection($role);
    }

    public function listRolePengguna()
    {
        return RoleResource::collection($role = Role::where('status_role', '<', 2)->get());
    }

    public function listRoleKomponenPenilaian()
    {
        $matchThese = ['status_role' => 2];
        return RoleResource::collection(Role::where($matchThese)->get());
    }

    public function listRoleSyaratKetentuan()
    {
        $matchTheseOne = ['status_role' => 3,];
        $matchTheseTwo = ['id_role' => 5];
        return RoleResource::collection(Role::where($matchTheseOne)->orWhere($matchTheseTwo)->get());
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get Role
        $role = Role::where('id_role', $id)->first();

        // Return single Role as a Resource
        return new RoleResource($role);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //
    }
}
