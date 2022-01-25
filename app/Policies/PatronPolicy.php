<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatronPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the patron.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Patron  $patron
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can create patrons.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can update the patron.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Patron  $patron
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can delete the patron.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Patron  $patron
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->user_type === 1;
    }
}
