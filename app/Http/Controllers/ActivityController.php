<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ActivityService;

class ActivityController extends Controller
{
    protected $activityService;

    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get more activities for the dedicated page
        $activities = $this->activityService->getActivities(50);

        return view('activities.index', compact('activities'));
    }
}
