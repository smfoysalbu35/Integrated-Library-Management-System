<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuthorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the author.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Author  $author
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can create authors.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can update the author.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Author  $author
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can delete the author.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Author  $author
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->user_type === 1;
    }
}
