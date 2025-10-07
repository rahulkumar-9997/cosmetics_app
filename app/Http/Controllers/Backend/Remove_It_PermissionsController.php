<?php
namespace App\Http\Controllers\Backend;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
class PermissionsController extends Controller
{
    public function index()
    {
        $permissions = Permission::latest()->paginate(15);
        $modules = Permission::distinct()->pluck('module')->filter();
        return view('backend.manage-user.permissions.index', compact('permissions', 'modules'));
    }

    public function create()
    {
        $modules = [
            'User Management',
            'Role Management', 
            'Permission Management',
            'Menu Management',
            'Product Management',
            'Inventory Management',
            'Customer Management',
            'Order Management',
            'Blog Management',            
        ];
        return view('backend.manage-user.permissions.create', compact('modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'module' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        Permission::create([
            'name' => $request->name,
            'module' => $request->module,
            'description' => $request->description,
            'guard_name' => 'web'
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    public function show(Permission $permission)
    {
        return view('backend.permissions.show', compact('permission'));
    }

    public function edit(Permission $permission)
    {
        $modules = [
            'User Management',
            'Role Management',
            'Permission Management', 
            'Menu Management',
            'Customer Management',
            'Order Management',
            'Blog Management',
            'Product Management',
            'Inventory Management'
        ];
        return view('backend.permissions.edit', compact('permission', 'modules'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'module' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $permission->update([
            'name' => $request->name,
            'module' => $request->module,
            'description' => $request->description,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }
}