<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookAuthorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the book author.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BookAuthor  $bookAuthor
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can create book authors.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can update the book author.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BookAuthor  $bookAuthor
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can delete the book author.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BookAuthor  $bookAuthor
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->user_type === 1;
    }
}
