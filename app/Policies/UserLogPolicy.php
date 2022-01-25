<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserLogPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the user log.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserLog  $userLog
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->user_type === 1;
    }
}
