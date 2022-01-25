<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the book.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Book  $book
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can create books.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can update the book.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Book  $book
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can delete the book.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Book  $book
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->user_type === 1;
    }
}
