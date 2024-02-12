<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Role::orderBy('id', 'DESC')->paginate(5);
        return view('pages.roles.index', [
            'data' => $data,
            'meta' => [
                'title' => 'Roles',
                'page_title' => [
                    [
                        'title' => 'Roles',
                        'slug' => route('role.index')
                    ]
                ]
            ]
        ]);
    }

    public function serverside(Request $request)
    {
        if ($request->ajax()) {
            $query = Role::query();
            // Apply search filter
            if ($request->has('search') && !empty($request->search['value'])) {
                $searchValue = $request->search['value'];
                $query->where(function ($query) use ($searchValue) {
                    $query->where('name', 'like', '%' . $searchValue . '%');
                });
            }
            $roles = $query->get();
            return DataTables::of($roles->map(function ($role, $index) {
                $role->index = $index;
                return $role;
            }))
                ->addColumn('no', function ($role) use ($request) {
                    return $role->index + 1;
                })
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('action', function ($data) {
                    return '
                    <a href="' . route('role.edit', $data->id) . '" class="btn btn-warning text-white"><i class="fas fa-edit"></i></a>
                    <a href="' . route('role.show', $data->id) . '" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                    <form id="delete-form" action="' . route('role.destroy', $data->id) . '" method="POST">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="button" onclick="confirmDelete();" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                    </form>
                ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('pages.roles.create', [
            'data' => $permission,
            'meta' => [
                'title' => 'Roles',
                'page_title' => [
                    [
                        'title' => 'Roles',
                        'slug' => route('role.index')
                    ],
                    [
                        'title' => 'Create',
                        'slug' => route('role.create')
                    ]
                ]
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('role.index')
            ->with('success', 'Role created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('pages.roles.show', [
            'data' => $role,
            'data_permission' => $rolePermissions,
            'data_role_permissions' => $permission,
            'meta' => [
                'title' => 'Roles',
                'page_title' => [
                    [
                        'title' => 'Roles',
                        'slug' => route('role.index')
                    ],
                    [
                        'title' => 'Show',
                        'slug' => ''
                    ]
                ]
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('pages.roles.edit', [
            'data' => $role,
            'data_permission' => $rolePermissions,
            'data_role_permissions' => $permission,
            'meta' => [
                'title' => 'Roles',
                'page_title' => [
                    [
                        'title' => 'Roles',
                        'slug' => route('role.index')
                    ],
                    [
                        'title' => 'Edit',
                        'slug' => ''
                    ]
                ]
            ]
        ]);
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
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));

        return redirect()->route('role.index')
            ->with('success', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Role::where('id', $id)->delete();
        return redirect()->route('role.index')
            ->with('success', 'Role deleted successfully');
    }
}
