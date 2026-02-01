<?php

// app/Http/Controllers/LeadActivityController.php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadActivity;
use Illuminate\Http\Request;

class LeadActivityController extends Controller
{
    /**
     * List activities for a lead
     */
    public function index(Lead $lead)
    {
        return LeadActivity::with('user:id,name')
            ->where('lead_id', $lead->id)
            ->latest()
            ->get();
    }

    /**
     * Add a note to the lead
     */
    public function addNote(Request $request, Lead $lead)
    {
        $request->validate([
            'note' => 'required|string'
        ]);

        return LeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => auth()->id(),
            'type' => 'note',
            'meta' => [
                'note' => $request->note
            ]
        ]);
    }

    /**
     * Change the status of the lead
     */
    public function changeStatus(Request $request, Lead $lead)
    {
        $request->validate([
            'from' => 'required|string',
            'to' => 'required|string'
        ]);

        return LeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => auth()->id(),
            'type' => 'status_change',
            'meta' => [
                'from' => $request->from,
                'to' => $request->to
            ]
        ]);
    }

    /**
     * Schedule a follow-up activity for the lead
     */
    public function addFollowup(Request $request, Lead $lead)
    {
        $request->validate([
            'datetime' => 'required|date',
            'remark' => 'nullable|string'
        ]);

        return LeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => auth()->id(),
            'type' => 'followup',
            'meta' => [
                'datetime' => $request->datetime,
                'remark' => $request->remark
            ]
        ]);
    }

    /**
     * Log a call activity for the lead
     */
    public function logCall(Request $request, Lead $lead)
    {
        $request->validate([
            'duration' => 'required|integer', // seconds
            'status' => 'required|string' // completed / missed / rejected
        ]);

        return LeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => auth()->id(),
            'type' => 'call',
            'meta' => [
                'duration' => $request->duration,
                'status' => $request->status
            ]
        ]);
    }
}
