<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookSubjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the book subject.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BookSubject  $bookSubject
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can create book subjects.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can update the book subject.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BookSubject  $bookSubject
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can delete the book subject.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BookSubject  $bookSubject
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->user_type === 1;
    }
}
