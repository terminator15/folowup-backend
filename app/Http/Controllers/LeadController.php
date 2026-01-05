<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{
    /**
     * List leads for logged-in user
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = DB::table('leads')
            ->where('owner_id', $user->id);

        // Optional filters
        if ($request->filled('lead_type')) {
            $query->where('lead_type', $request->lead_type);
        }

        if ($request->filled('min_amount')) {
            $query->where('deal_value', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('deal_value', '<=', $request->max_amount);
        }

        $leads = $query
            ->orderBy('created_at', 'desc')
            ->get();

        // Attach meta to each lead
        $leadIds = $leads->pluck('id')->toArray();

        $meta = DB::table('lead_meta')
            ->whereIn('lead_id', $leadIds)
            ->get()
            ->groupBy('lead_id');

        $leads = $leads->map(function ($lead) use ($meta) {
            $lead->meta = $meta[$lead->id] ?? [];
            return $lead;
        });

        return response()->json($leads);
    }

    /**
     * Create a new lead
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'email'            => 'nullable|email',
            'title'            => 'nullable|string|max:255',
            'lead_type'        => 'required|string|max:50',
            'status'           => 'nullable|string|max:50',
            'deal_value'       => 'nullable|numeric',
            'currency'         => 'nullable|string|max:10',
            'source'           => 'nullable|string|max:50',
            'next_followup_at' => 'nullable|date',
            'meta'             => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            // Get user's workspace (hidden personal workspace)
            $workspaceId = DB::table('workspace_user')
                ->where('user_id', $user->id)
                ->value('workspace_id');

            $leadId = DB::table('leads')->insertGetId([
                'workspace_id'     => $workspaceId,
                'owner_id'         => $user->id,
                'title'            => $validated['title'] ?? null,
                'name'             => $validated['name'],
                'phone'            => $validated['phone'],
                'email'            => $validated['email'] ?? null,
                'lead_type'        => $validated['lead_type'],
                'status'           => $validated['status'] ?? 'new',
                'deal_value'       => $validated['deal_value'] ?? null,
                'currency'         => $validated['currency'] ?? 'INR',
                'source'           => $validated['source'] ?? null,
                'next_followup_at' => $validated['next_followup_at'] ?? null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            // Save dynamic meta
            if (!empty($validated['meta'])) {
                foreach ($validated['meta'] as $key => $value) {
                    DB::table('lead_meta')->insert([
                        'lead_id' => $leadId,
                        'key'     => $key,
                        'value'   => is_array($value) ? json_encode($value) : $value,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Lead created successfully',
                'lead_id' => $leadId,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create lead',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
