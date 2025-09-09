<?php
// app/Http/Controllers/Admin/StoreUserController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

use Yajra\DataTables\Facades\DataTables;

class StoreUserController extends Controller
{
    public function index()
    {
        return view('layouts.admin.store-users.index');
    }

    public function getData()
    {
        $query = User::role('storeadmin')->latest();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('actions', function ($user) {
                $editUrl   = route('admin.store-users.edit', $user);
                $deleteUrl = route('admin.store-users.destroy', $user);

                return '
                    <a href="'.$editUrl.'" class="btn btn-sm btn-warning">Edit</a>
                    <form action="'.$deleteUrl.'" method="POST" style="display:inline-block;">
                        '.csrf_field().method_field('DELETE').'
                        <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm(\'Delete this user?\')">Delete</button>
                    </form>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
    

    public function create()
    {
        return view('layouts.admin.store-users.create');
    }

    public function store(Request $request)
    {
        
        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','unique:users,email'],
            'password' => ['required','min:8','confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email'=> $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole('storeadmin');

        return redirect()->route('admin.store-users.index')->with('status','Store user created');
    }

    public function edit(User $store_user)
    {
        return view('layouts.admin.store-users.edit', ['user' => $store_user]);

    }

    public function update(Request $request, User $store_user)
    {
        $data = $request->validate([
            'name'  => ['required','string','max:255'],
            'email' => ['required','email','unique:users,email,'.$store_user->id],
        ]);

        $store_user->update($data);
        
        return redirect()->route('admin.store-users.index')->with('status','Store user created');
    }

    public function destroy(User $store_user)
    {
        $store_user->delete();
        return back()->with('status','Deleted');
    }
}
