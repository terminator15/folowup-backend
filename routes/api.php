<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\WorkspaceInvitationController;
use App\Http\Controllers\LeadActivityController;

Route::post('/auth/google', [AuthController::class, 'google']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/set-password', [AuthController::class, 'setPassword']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::post('/leads', [LeadController::class, 'store']);
    Route::get('/leads', [LeadController::class, 'index']);
    Route::get('/leads/{lead}', [LeadController::class, 'show']);
    Route::put('/leads/{lead}', [LeadController::class, 'update']);
    Route::delete('/leads/{lead}', [LeadController::class, 'destroy']);

    Route::get('/workspaces', [WorkspaceController::class, 'index']);
    Route::post('/workspaces', [WorkspaceController::class, 'store']);
    Route::get(
        '/workspaces/{workspace}/members',
        [WorkspaceController::class, 'members']
    );
    Route::post(
        '/workspaces/{workspace}/invite',
        [WorkspaceInvitationController::class, 'invite']
    );

    Route::get(
        '/workspace-invitations',
        [WorkspaceInvitationController::class, 'myInvites']
    );

    Route::post(
        '/workspace-invitations/{invite}/accept',
        [WorkspaceInvitationController::class, 'accept']
    );

    Route::post(
        '/workspace-invitations/{invite}/reject',
        [WorkspaceInvitationController::class, 'reject']
    );

    Route::delete(
        '/workspaces/{workspace}/users/{user}',
        [WorkspaceInvitationController::class, 'removeMember']
    );

    Route::get('/leads/{lead}/activities', [LeadActivityController::class, 'index']);

    Route::post('/leads/{lead}/note', [LeadActivityController::class, 'addNote']);
    Route::post('/leads/{lead}/status', [LeadActivityController::class, 'changeStatus']);
    Route::post('/leads/{lead}/followup', [LeadActivityController::class, 'addFollowup']);
    Route::post('/leads/{lead}/call-log', [LeadActivityController::class, 'logCall']);
});

