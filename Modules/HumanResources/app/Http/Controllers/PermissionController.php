<?php

namespace Modules\HumanResources\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = \Spatie\Permission\Models\Permission::latest()->paginate(10);
        return view('humanresources::permissions.index', compact('permissions'));
    }
}
