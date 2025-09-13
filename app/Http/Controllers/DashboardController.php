<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\Pipeline;
use App\Models\Stage;

class DashboardController extends Controller
{
    // Keep the logic here, keep the view slim.
    public function index()
    {
        // Find "Won" stages (robust even if you add multiple pipelines)
        $wonStageIds = Stage::where('name', 'Won')->pluck('id');

        $stats = [
            'companies'   => Company::count(),
            'contacts'    => Contact::count(),
            'deals_open'  => Deal::whereNotIn('stage_id', $wonStageIds)->count(),
            'deals_won'   => Deal::whereIn('stage_id', $wonStageIds)->count(),
            'value_open'  => Deal::whereNotIn('stage_id', $wonStageIds)->sum('value_cents'),
            'value_won'   => Deal::whereIn('stage_id', $wonStageIds)->sum('value_cents'),
        ];

        // Pick first pipeline as default (adjust if you have a current-user pipeline)
        $pipeline = Pipeline::first();

        $stages = $pipeline
            ? Stage::where('pipeline_id', $pipeline->id)
            ->withCount('deals')
            ->orderBy('position')
            ->get()
            : collect();

        return view('dashboard', compact('stats', 'pipeline', 'stages'));
    }
}
