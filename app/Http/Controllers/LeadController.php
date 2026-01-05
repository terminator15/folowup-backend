<?php

namespace App\Http\Controllers;

use App\Services\LeadService;
use App\DTO\Lead\CreateLeadDTO;
use App\DTO\Lead\LeadResponseDTO;
use Illuminate\Http\Request;

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
        $leads = $this->service->list($request->all());

        return response()->json(
            $leads->map(fn ($lead) => LeadResponseDTO::fromModel($lead))
        );
    }
}
