<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PenaltyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any penalties.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->user_type === 1 || $user->user_type === 2;
    }

    /**
     * Determine whether the user can view the penalty.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Penalty  $penalty
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->user_type === 1;
    }
}
