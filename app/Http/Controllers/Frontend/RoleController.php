<?php

namespace App\Http\Controllers\Frontend;

use Laratrust;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Laratrust::can('read-acl')) {
            return abort(403);
        }

        $roles = Role::all();

        return view('admin.roles.view', compact($roles));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Laratrust::can('create-acl')) {
            return abort(403);
        }

        $permissions = Permission::all();

        return view('admin.roles.create', compact($permissions));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! Laratrust::can('create-acl')) {
            return abort(403);
        }

        $data = $this->validateWith([
            'display_name' => 'required|max:255',
            'name'         => 'required|max:100|alpha_dash|unique:roles,name',
            'description'  => 'sometimes|max:255',
        ]);
        $role = Role::create($data);

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Successfully created the new '.$role->display_name.' role in the database.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        if (! Laratrust::can('update-acl')) {
            return abort(403);
        }

        $permissions = Permission::all();

        return view('admin.roles.edit')->withRole($role)->withPermissions($permissions);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        if (! Laratrust::can('update-acl')) {
            return abort(403);
        }

        $data = $this->validateWith([
            'display_name' => 'required|max:255',
            'description'  => 'sometimes|max:255',
        ]);
        $role->update($data);

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Successfully update the '.$role->display_name.' role in the database.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //
    }
}
