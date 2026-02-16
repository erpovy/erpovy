<?php

namespace Modules\Manufacturing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Manufacturing\Services\MRPService;

class MRPController extends Controller
{
    protected $mrpService;

    public function __construct(MRPService $mrpService)
    {
        $this->mrpService = $mrpService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyId = auth()->user()->company_id;
        $data = $this->mrpService->calculateRequirements($companyId);
        
        $stats = $data['stats'];
        $requirements = $data['requirements'];
        $rawMaterialNeeds = $data['raw_material_needs'];

        return view('manufacturing::mrp.index', compact('stats', 'requirements', 'rawMaterialNeeds'));
    }
}
