<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;

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

    Route::post(
        '/workspaces/{workspace}/invite',
        [WorkspaceInvitationController::class, 'invite']
    );

    Route::get(
        '/my/workspace-invitations',
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
});

