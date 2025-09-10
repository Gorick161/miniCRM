<?php

namespace App\Http\Controllers;

use App\Models\Pipeline;
use App\Models\Stage;
use App\Models\Deal;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index()
    {
        // Lade Pipelines + Stages + Deals
        $pipelines = Pipeline::with(['stages' => function ($q) {
            $q->orderBy('position');
        }])->get();

        // Für Demo: wir nehmen die erste Pipeline
        $pipeline = $pipelines->first();

        $stages = Stage::with(['deals' => function ($q) {
            $q->with('company')->orderBy('updated_at', 'desc');
        }])
        ->where('pipeline_id', $pipeline->id ?? null)
        ->orderBy('position')
        ->get();

        return view('deals.index', compact('pipeline', 'stages'));
    }

    public function updateStage(Deal $deal, Request $request)
    {
        $request->validate([
            'stage_id' => ['required', 'exists:stages,id'],
        ]);

        $deal->update(['stage_id' => $request->stage_id]);

        return back()->with('status', 'Deal erfolgreich verschoben');
    }
}