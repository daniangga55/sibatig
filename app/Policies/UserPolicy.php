<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    public function before(User $user): ?bool
    {
        return $user->role === UserRole::SuperAdmin ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function view(User $user, User $model): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, User $model): bool
    {
        return $user->role === UserRole::Admin && $model->role !== UserRole::SuperAdmin;
    }

    public function delete(User $user, User $model): bool
    {
        return $this->update($user, $model) && ! $user->is($model);
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }
}
