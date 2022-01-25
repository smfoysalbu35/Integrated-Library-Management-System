<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BorrowPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any borrows.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->user_type === 1 || $user->user_type === 2;
    }

    /**
     * Determine whether the user can view the borrow.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Borrow  $borrow
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can create borrows.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->user_type === 1 || $user->user_type === 2;
    }
}
