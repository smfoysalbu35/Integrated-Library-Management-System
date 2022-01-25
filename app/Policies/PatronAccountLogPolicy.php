<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatronAccountLogPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the patron account log.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PatronAccountLog  $patronAccountLog
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->user_type === 1;
    }
}
