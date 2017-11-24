<?php

namespace App\Policies;

use App\User;
use App\Models\OrganisationMember;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganisationMemberPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the organisationMember.
     *
     * @param  \App\User  $user
     * @param  \App\Models\OrganisationMember  $organisationMember
     * @return mixed
     */
    public function view(User $user, OrganisationMember $organisationMember)
    {
        return $user->campaign->id == $organisationMember->character->campaign_id;
    }

    /**
     * Determine whether the user can create organisationMembers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->member();
    }

    /**
     * Determine whether the user can update the organisationMember.
     *
     * @param  \App\User  $user
     * @param  \App\Models\OrganisationMember  $organisationMember
     * @return mixed
     */
    public function update(User $user, OrganisationMember $organisationMember)
    {
        return $user->campaign->id == $organisationMember->character->campaign_id &&
            ($user->member());
    }

    /**
     * Determine whether the user can delete the organisationMember.
     *
     * @param  \App\User  $user
     * @param  \App\Models\OrganisationMember  $organisationMember
     * @return mixed
     */
    public function delete(User $user, OrganisationMember $organisationMember)
    {
        return $user->campaign->id == $organisationMember->character->campaign_id &&
            ($user->member());
    }
}
