<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\WebsiteSetting;

class WebsiteSettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->canManage($user);
    }

    public function view(User $user, WebsiteSetting $model): bool
    {
        return $this->canManage($user);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user);
    }

    public function update(User $user, WebsiteSetting $model): bool
    {
        return $this->canManage($user);
    }

    public function delete(User $user, WebsiteSetting $model): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }

    public function deleteAny(User $user): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }

    private function canManage(User $user): bool
    {
        return $user->hasAnyRole(UserRole::SuperAdmin, UserRole::Admin);
    }
}
