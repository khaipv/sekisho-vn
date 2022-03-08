<?php

namespace App\Providers;
use App\Client;
use  App\Policies\ClientPolicy;
use DB;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        
        Client::class  => ClientPolicy::class     
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view', function ($user) {

      $role= DB::table('users')
          ->leftjoin('role', 'users.id', '=', 'role.userID')
          ->where('users.id', '=', $user->id)
          ->first();
        return  $role->rank >1;
    });
    }
}
