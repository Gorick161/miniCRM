<?php

namespace App\Http\Controllers;

use App\Models\{Company, Contact, Deal, Pipeline, Stage, Activity};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // KPIs
        $stats = [
            'companies'   => Company::count(),
            'contacts'    => Contact::count(),
            'deals_open'  => Deal::where('status', 'open')->count(),
            'deals_won'   => Deal::where('status', 'won')->count(),
            'deals_lost'  => Deal::where('status', 'lost')->count(),
            'value_open'  => Deal::where('status', 'open')->sum('value_cents'),
            'value_won'   => Deal::where('status', 'won')->sum('value_cents'),
        ];

        // Erste Pipeline + Stages mit Deal-Zahlen
        $pipeline = Pipeline::with('stages')->first();
        $stages = collect();
        if ($pipeline) {
            $stages = Stage::where('pipeline_id', $pipeline->id)
                ->orderBy('position')
                ->withCount('deals')
                ->get();
        }

        // Letzte Deals & Aktivitäten
        $recentDeals = Deal::with(['company','stage'])
            ->latest('updated_at')
            ->limit(8)
            ->get();

        $recentActivities = Activity::with('user')
            ->latest('happened_at')
            ->limit(8)
            ->get();

        return view('dashboard.index', compact('stats','pipeline','stages','recentDeals','recentActivities'));
    }
}
