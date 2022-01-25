<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatronTypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the patron type.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PatronType  $patronType
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can create patron types.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can update the patron type.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PatronType  $patronType
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can delete the patron type.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PatronType  $patronType
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->user_type === 1;
    }
}
