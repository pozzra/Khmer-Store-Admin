<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
public function index()
{
    $admins = \App\Models\User::all(['id', 'name', 'email', 'password', 'role']);
    return \Inertia\Inertia::render('Admin/Index', [
        'admins' => $admins,
    ]);
}
    public function create()
    {
        // Just render the create admin page
        return Inertia::render('Admin/create');
    }
    
    public function store(\Illuminate\Http\Request $request)
    {
    $validated = $request->validate([
        'name'     => ['required', 'string', 'max:255'],
        'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:6'],
        'role'     => ['required', 'in:admin,user'],
    ]);

    \App\Models\User::create([
        'name'     => $validated['name'],
        'email'    => $validated['email'],
        'password' => \Hash::make($validated['password']),
        'role'     => $validated['role'], // <-- This line is critical!
    ]);

    return redirect()->route('admin.index')->with('success', 'Admin created successfully.');
    }
    public function update(\Illuminate\Http\Request $request, $id)
{
    $admin = \App\Models\User::findOrFail($id);

    $validated = $request->validate([
        'name'     => ['required', 'string', 'max:255'],
        'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$admin->id],
        'password' => ['nullable', 'string', 'min:6'],
        'role'     => ['required', 'in:admin,user'],
    ]);

    $admin->name = $validated['name'];
    $admin->email = $validated['email'];
    $admin->password = $validated['password'];
    $admin->role = $validated['role'];

    if (!empty($validated['password'])) {
        $admin->password = \Hash::make($validated['password']);
    }

    $admin->save();

    return redirect()->route('admin.index')->with('success', 'Admin updated successfully.');
}
public function edit($id)
{
    $admin = \App\Models\User::findOrFail($id);
    return \Inertia\Inertia::render('Admin/edit', [
        'admin' => $admin,
    ]);
}
public function destroy($id)
{
    $admin = \App\Models\User::findOrFail($id);
    $admin->delete();
    // return redirect()->route('admin.index')->with('success', 'Admin deleted successfully.');
    //  add if delete success please reload the page
    return redirect()->back()->with('success', 'Admin deleted successfully.');
}
}