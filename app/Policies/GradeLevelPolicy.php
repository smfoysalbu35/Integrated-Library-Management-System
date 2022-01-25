<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GradeLevelPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the grade level.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GradeLevel  $gradeLevel
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can create grade levels.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can update the grade level.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GradeLevel  $gradeLevel
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->user_type === 1;
    }

    /**
     * Determine whether the user can delete the grade level.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GradeLevel  $gradeLevel
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->user_type === 1;
    }
}
