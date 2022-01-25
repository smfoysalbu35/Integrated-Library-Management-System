<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatronAccountPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the patron account.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PatronAccount  $patronAccount
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can delete the patron account.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PatronAccount  $patronAccount
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->user_type === 1;
    }
}
