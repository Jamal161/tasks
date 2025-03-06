<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [
        Task::class => TaskPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
   

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    
}
