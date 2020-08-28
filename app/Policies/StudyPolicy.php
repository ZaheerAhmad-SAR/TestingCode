<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Admin\Entities\Study;

class StudyPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        $this->authorizeResource(Study::class, 'study');
    }

    /**
     * Determine whether the user can view any studies.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the study.
     *
     * @param  \App\User  $user
     * @param  \App\Study  $study
     * @return mixed
     */
    public function view(User $user, Study $study)
    {
        //
    }

    /**
     * Determine whether the user can create studies.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the study.
     *
     * @param  \App\User  $user
     * @param  \App\Study  $study
     * @return mixed
     */
    public function update(User $user, Study $study)
    {
        //
    }

    /**
     * Determine whether the user can delete the study.
     *
     * @param  \App\User  $user
     * @param  \App\Study  $study
     * @return mixed
     */
    public function delete(User $user, Study $study)
    {
        //
    }

    /**
     * Determine whether the user can restore the study.
     *
     * @param  \App\User  $user
     * @param  \App\Study  $study
     * @return mixed
     */
    public function restore(User $user, Study $study)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the study.
     *
     * @param  \App\User  $user
     * @param  \App\Study  $study
     * @return mixed
     */
    public function forceDelete(User $user, Study $study)
    {
        //
    }
}
