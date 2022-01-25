<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReturnBookPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the return book.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ReturnBook  $returnBook
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can create return books.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->user_type === 1 || $user->user_type === 2;
    }
}
