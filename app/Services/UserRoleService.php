<?php

namespace App\Services;
use App\Models\User;
class UserRoleService
{
    public function assign(User $user, string $role): User
    {
        $user->assignRole($role);
        return $user->fresh();
    }

    public function update(User $user, string $role): User
    {
        $user->syncRoles([$role]); 
        return $user->fresh();
    }

    public function revoke(User $user, string $role): User
    {
        $user->removeRole($role);
        return $user->fresh();
    }
}
