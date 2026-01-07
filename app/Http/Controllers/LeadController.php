<?php

namespace App\Http\Controllers;

use App\Services\LeadService;
use App\DTO\Lead\CreateLeadDTO;
use App\DTO\Lead\LeadResponseDTO;
use Illuminate\Http\Request;
use App\DTO\Lead\LeadFilterDTO;

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
}
