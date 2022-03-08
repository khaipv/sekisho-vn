<?php

namespace App\Policies;

use App\User;
use App\Client;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the client.
     *
     * @param  \App\User  $user
     * @param  \App\Client  $client
     * @return mixed
     */
    public function view(User $user)
    {
        dd($user->id);
        return true;
    }
    public function update($user, $client) {
     
        if ($user->id==1
        ){
        return true;
    }
            return false;

}

}
