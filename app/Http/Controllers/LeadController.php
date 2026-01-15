<?php

namespace App\Http\Controllers;

use App\Services\LeadService;
use App\DTO\Lead\CreateLeadDTO;
use App\DTO\Lead\LeadResponseDTO;
use Illuminate\Http\Request;
use App\DTO\Lead\LeadFilterDTO;
use App\DTO\Lead\UpdateLeadDTO;
use App\Models\Lead;

class LeadController extends Controller
{
    public function __construct(
        private LeadService $service
    ) {}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'workspace_id' => 'required|integer|exists:workspaces,id',
        ]);

        $dto = CreateLeadDTO::fromRequest(
            $request->all(),
            $request->user()->id
        );

        $leadId = $this->service->create($dto);

        return response()->json([
            'message' => 'Lead created successfully',
            'lead_id' => $leadId
        ], 201);
    }

    public function index(Request $request)
    {
        $filterDto = LeadFilterDTO::fromRequest($request->all(), $request->user()->id);
        // dd($request->user()->id);
        $leads = $this->service->list($filterDto);

        return response()->json([
            'data' => $leads->getCollection()
                ->map(fn ($lead) => LeadResponseDTO::fromModel($lead)),
            'meta' => [
                'current_page' => $leads->currentPage(),
                'per_page' => $leads->perPage(),
                'total' => $leads->total(),
                'last_page' => $leads->lastPage(),
            ]
        ]);
    }

    public function show(Lead $lead)
    {
        $this->authorize('view', $lead);

        return response()->json([
            'id' => $lead->id,
            'name' => $lead->name,
            'phone' => $lead->phone,
            'lead_type' => $lead->lead_type_id,
            'deal_value' => $lead->deal_value,
            'owner_id' => $lead->owner_id,
            'workspace_id' => $lead->workspace_id,
            'meta' => $lead->meta,
        ]);
    }

    public function update(Request $request, Lead $lead)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'workspace_id' => 'required|integer|exists:workspaces,id',
        ]);
        
        $this->authorize('update', $lead);

        $dto = UpdateLeadDTO::fromRequest($request->all());

        $lead = $this->service->update($lead, $dto);

        return response()->json($lead);
    }


    public function destroy(Lead $lead)
    {
        $this->authorize('delete', $lead);

        $lead->delete();
        return response()->json([
            'message' => 'Lead deleted successfully'
        ]);
    }
    

}
