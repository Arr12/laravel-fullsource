<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::limit(10)->get();
        return view('pages.users.index', [
            'data' => $users,
            'meta' => [
                'title' => 'Users',
                'page_title' => [
                    [
                        'title' => 'Users',
                        'slug' => route('users.index')
                    ]
                ]
            ]
        ]);
    }

    public function serverside(Request $request)
    {
        if ($request->ajax()) {
            $query = User::query();

            // Apply search filter
            if ($request->has('search') && !empty($request->search['value'])) {
                $searchValue = $request->search['value'];
                $query->where(function ($query) use ($searchValue) {
                    $query->where('name', 'like', '%' . $searchValue . '%')
                        ->orWhere('email', 'like', '%' . $searchValue . '%');
                });
            }
            $users = $query->get();
            return DataTables::of($users->map(function ($user, $index) {
                $user->index = $index;
                return $user;
            }))
                ->addColumn('no', function ($user) use ($request) {
                    return $user->index + 1;
                })
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('photo', function ($data) {
                    return '<img src="' . $data->photo . '" onerror="this.src=`/images/blank-profile.png`" alt="' . $data->name . '" width="100" height="100" style="width:100px;object-fit:cover;" />';
                })
                ->addColumn('email', function ($data) {
                    return $data->email;
                })
                ->addColumn('is_verified', function ($data) {
                    return $data->email_verified_at;
                })
                ->addColumn('action', function ($data) {
                    return '
                    <a href="' . route('users.edit', $data->uuid) . '" class="btn btn-warning text-white"><i class="fas fa-edit"></i></a>
                    <a href="' . route('users.show', $data->uuid) . '" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                    <form id="delete-form" action="' . route('users.destroy', $data->uuid) . '" method="POST">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="button" onclick="confirmDelete();" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                    </form>
                ';
                })
                ->rawColumns(['photo', 'action'])
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
        $role = Role::get();
        return view('pages.users.create', [
            'role' => $role,
            'meta' => [
                'title' => 'Create',
                'page_title' => [
                    [
                        'title' => 'Users',
                        'slug' => route('users.index')
                    ],
                    [
                        'title' => 'Create',
                        'slug' => route('users.create')
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
        $request->validate([
            'username' => 'required',
            'email' => 'required',
            'password' => 'required|string|min:6',
            'roles' => 'required',
        ]);

        $path_url = '';
        if ($request->file('photos')) {
            $file = $request->file('photos');
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            if (!checkFileExtension($file, $allowedExtensions)) {
                $errors = [
                    'max_file_size' => 'Maximum file size should be lower than 10 MB and please check the file extension.',
                ];
                return redirect()->back()->withErrors($errors)->withInput();
            } else {
                $path = $file->store('public/uploads');
                $path_url = Storage::url($path);
            }
        }
        $id = Uuid::uuid4()->toString();
        $data = [
            'id' => $id,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'email_verified_at' => $request->verified ? date('Y-m-d H:i:s') : '',
            'password' => Hash::make($request->password),
            'photo' => $path_url
        ];

        try {
            User::create($data);
            Mail::send('emails.joined-verification', ['id' => $id], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Join Invitation Mail');
            });
            return redirect()->route('users.index')->with('success', 'Data has been created and email has been send in ' . $request->email . '.');
        } catch (\Throwable $th) {
            $errors = [
                'failed' => 'Failed to create data please check the suitability forms or username already exists.',
            ];
            return redirect()->back()->withErrors($errors)->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = User::find($id);
        return view('pages.users.show', [
            'data' => $data,
            'meta' => [
                'title' => 'Show',
                'page_title' => [
                    [
                        'title' => 'Users',
                        'slug' => route('users.index')
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
        $data = User::find($id);
        $role = Role::get();
        $role_user = $data->getRoleNames();
        return view('pages.users.edit', [
            'data' => $data,
            'role' => $role,
            'role_user' => $role_user,
            'meta' => [
                'title' => 'Edit',
                'page_title' => [
                    [
                        'title' => 'Users',
                        'slug' => route('users.index')
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
        $request->validate([
            'name' => 'required',
            'username' => 'required',
            'email' => 'required',
            'roles' => 'required',
        ]);

        $path_url = '';
        if ($request->file('photos')) {
            $file = $request->file('photos');
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            if (!checkFileExtension($file, $allowedExtensions)) {
                $errors = [
                    'max_file_size' => 'Maximum file size should be lower than 10 MB and please check the file extension.',
                ];
                return redirect()->back()->withErrors($errors)->withInput();
            } else {
                $path = $file->store('public/uploads');
                $path_url = Storage::url($path);
            }
        }

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'email_verified_at' => $request->verified ? date('Y-m-d H:i:s') : '',
        ];


        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($path_url) {
            $data['photo'] = $path_url;
        }

        try {
            $user = User::findOrFail($id);
            $user->update($data);
            $user->assignRole($request->roles);
            return redirect()->route('users.index')->with('success', 'Data has been created');
        } catch (\Throwable $th) {
            $errors = [
                'failed' => 'Failed to update data please check the suitability forms or username already exists.',
            ];
            return redirect()->back()->withErrors($errors)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($user);
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('users.index')->with('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $errors = [
                'failed' => 'Failed to destroy data please check the suitability forms.',
            ];
            return redirect()->back()->withErrors($errors)->withInput();
        }
    }
}
