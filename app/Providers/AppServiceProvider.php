<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use App\Repositories\Contracts\LeadRepositoryInterface;
use App\Repositories\Eloquent\LeadRepository;
use App\Models\Lead;
use App\Policies\LeadPolicy;
use App\Models\Workspace;
use App\Policies\WorkspacePolicy;
use App\Models\WorkspaceInvitation;
use App\Policies\WorkspaceInvitationPolicy;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Lead::class => LeadPolicy::class,
        Workspace::class => WorkspacePolicy::class,
        WorkspaceInvitation::class => WorkspaceInvitationPolicy::class,
    ];
    
    public function register(): void
    {
        $this->app->bind(
            LeadRepositoryInterface::class,
            LeadRepository::class
        );
    }

    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(
                $request->user()?->id ?: $request->ip()
            );
        });
    }
}
