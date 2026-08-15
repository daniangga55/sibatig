<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\PkptActivity;
use App\Models\User;

class PkptActivityPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, PkptActivity $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $this->canWrite($user);
    }

    public function update(User $user, PkptActivity $model): bool
    {
        return $this->canWrite($user);
    }

    public function delete(User $user, PkptActivity $model): bool
    {
        return $this->canWrite($user);
    }

    public function deleteAny(User $user): bool
    {
        return $this->canWrite($user);
    }

    public function restore(User $user, PkptActivity $model): bool
    {
        return $this->canWrite($user);
    }

    public function restoreAny(User $user): bool
    {
        return $this->canWrite($user);
    }

    public function forceDelete(User $user, PkptActivity $model): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->role === UserRole::SuperAdmin;
    }

    private function canWrite(User $user): bool
    {
        return $user->hasAnyRole(UserRole::SuperAdmin, UserRole::Admin, UserRole::Pimpinan, UserRole::Auditor);
    }
}
