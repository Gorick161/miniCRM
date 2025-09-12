<?php

namespace App\Http\Controllers;

use App\Models\Pipeline;
use App\Models\Stage;
use Illuminate\Support\Collection;
use App\Models\Deal;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index()
{
    
    $pipelineId = Pipeline::orderBy('id')->value('id');

    
    $pipeline = $pipelineId ? Pipeline::with('stages')->find($pipelineId) : null;

    
    $stages = $pipelineId
        ? Stage::where('pipeline_id', $pipelineId)
            ->with(['deals' => fn($q) => $q->with('company')->orderBy('position')])
            ->orderBy('position')
            ->get()
        : collect();

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

    public function reorder(Request $request)
{
    $data = $request->validate([
        'stage_id'  => ['required','exists:stages,id'],
        'deal_ids'  => ['required','array'],
        'deal_ids.*'=> ['integer','exists:deals,id'],
    ]);

    foreach ($data['deal_ids'] as $i => $id) {
        Deal::where('id', $id)->update([
            'stage_id' => $data['stage_id'],
            'position' => $i,
        ]);
    }

    return response()->json(['ok' => true]);
}
}