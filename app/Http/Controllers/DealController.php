<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Deal;
use App\Models\Pipeline;
use App\Models\Stage;
use Illuminate\Http\Request;

class DealController extends Controller
{
    /**
     * Kanban board: list pipelines, current pipeline, its stages and deals.
     * Query param ?pipeline={id} can switch pipelines.
     */
    public function index(Request $request)
    {
        // All pipelines for the dropdown (UX)
        $pipelines = Pipeline::orderBy('id')->get();

        // Determine selected pipeline: query ?pipeline=id, else first
        $pipeline = null;
        if ($request->filled('pipeline')) {
            $pipeline = Pipeline::with('stages')->find($request->integer('pipeline'));
        }
        if (!$pipeline) {
            $pipeline = Pipeline::with('stages')->orderBy('id')->first();
        }

        // Stages of the selected pipeline with deals (eager-loaded & ordered)
        $stages = $pipeline
            ? Stage::where('pipeline_id', $pipeline->id)
            ->with(['deals' => function ($q) {
                $q->with('company')->orderBy('position');
            }])
            ->orderBy('position')
            ->get()
            : collect();

        // For the "New Deal" modal
        $companies = Company::orderBy('name')->get();

        return view('deals.index', compact('pipelines', 'pipeline', 'stages', 'companies'));
    }

    /**
     * Create a new deal from the modal.
     * NOTE: value is provided as decimal euros; we store cents.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'      => ['required', 'string', 'max:255'],
            'value'      => ['nullable', 'numeric', 'min:0'],
            'currency'   => ['required', 'string', 'max:8'],
            'company_id' => ['nullable', 'exists:companies,id'],
            'stage_id'   => ['required', 'exists:stages,id'],
        ]);

        // Convert € to cents; guard against null
        $valueCents = isset($data['value'])
            ? (int) round($data['value'] * 100)
            : 0;

        // Position at the end of the target stage
        $lastPos = Deal::where('stage_id', $data['stage_id'])->max('position');
        $nextPos = is_null($lastPos) ? 0 : $lastPos + 1;

        Deal::create([
            'title'        => $data['title'],
            'value_cents'  => $valueCents,
            'currency'     => $data['currency'] ?: 'EUR',
            'company_id'   => $data['company_id'] ?? null,
            'stage_id'     => $data['stage_id'],
            'position'     => $nextPos,
            'owner_id' => auth()->id(),
        ]);

        return back()->with('status', 'Deal created');
    }

    /**
     * Move a deal to a different stage (drag across columns).
     * Also puts the deal at the end of the target stage.
     * Returns JSON for a smooth UX.
     */
    public function updateStage(Deal $deal, Request $request)
    {
        $request->validate([
            'stage_id' => ['required', 'exists:stages,id'],
        ]);

        $targetStageId = (int) $request->stage_id;

        // Append to end of the new stage
        $lastPos = Deal::where('stage_id', $targetStageId)->max('position');
        $nextPos = is_null($lastPos) ? 0 : $lastPos + 1;

        $deal->update([
            'stage_id' => $targetStageId,
            'position' => $nextPos,
        ]);

        return response()->json(['ok' => true]);
    }

    /**
     * Reorder deals within a stage (drag inside a column).
     * Expects payload: { stage_id: int, order: [dealId1, dealId2, ...] }
     */
    public function reorder(Request $request)
    {
        $data = $request->validate([
            'stage_id'   => ['required', 'exists:stages,id'],
            'order'      => ['required', 'array'],
            'order.*'    => ['integer', 'exists:deals,id'],
        ]);

        // Assign 0..n position in the given order
        foreach ($data['order'] as $i => $dealId) {
            Deal::where('id', $dealId)->update([
                'stage_id' => $data['stage_id'], // safety: ensure correct column
                'position' => $i,
            ]);
        }

        return response()->json(['ok' => true]);
    }
}
