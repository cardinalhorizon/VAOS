<?php

namespace App\Http\Controllers\Admin;

use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laratrust;
use Session;

class PermissionController extends Controller
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
        $permissions = Permission::all();

        return view('admin.permissions.view')->withPermissions($permissions);
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

        return view('admin.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! Laratrust::can('create-acl')) {
            return abort(403);
        }
        if ($request->permission_type == 'basic') {
            $this->validate($request, [
                'display_name' => 'required|max:255',
                'name' => 'required|max:255|alphadash|unique:permissions,name',
                'description' => 'sometimes|max:255',
            ]);
            $permission = new Permission();
            $permission->name = $request->name;
            $permission->display_name = $request->display_name;
            $permission->description = $request->description;
            $permission->save();
            Session::flash('success', 'Permission has been successfully added');

            return redirect()->route('permissions.index');
        } elseif ($request->permission_type == 'crud') {
            $this->validate($request, [
                'resource' => 'required|min:3|max:100|alpha',
            ]);
            $crud = $request->crud_selected;
            if (count($crud) > 0) {
                foreach ($crud as $x) {
                    $slug = strtolower($x).'-'.strtolower($request->resource);
                    $display_name = ucwords($x.' '.$request->resource);
                    $description = 'Allows a user to '.strtoupper($x).' a '.ucwords($request->resource);
                    $permission = new Permission();
                    $permission->name = $slug;
                    $permission->display_name = $display_name;
                    $permission->description = $description;
                    $permission->save();
                }
                Session::flash('success', 'Permissions were all successfully added');

                return redirect()->route('permissions.index');
            }
        } else {
            return redirect()->route('permissions.create')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        if (! Laratrust::can('update-acl')) {
            return abort(403);
        }

        return view('admin.permissions.edit')->withPermission($permission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        if (! Laratrust::can('update-acl')) {
            return abort(403);
        }
        $this->validate($request, [
            'display_name' => 'required|max:255',
            'description' => 'sometimes|max:255',
        ]);

        $permission->display_name = $request->display_name;
        $permission->description = $request->description;
        $permission->save();
        Session::flash('success', 'Updated the '.$permission->display_name.' permission.');

        return redirect()->route('permissions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        //
    }
}
