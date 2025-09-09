<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can perform any action on the model.
     */
    private function isOwner(User $user, File $file)
    {
        return $file->created_by === $user->id;
    }

    public function view(User $user, File $file)
    {
        return $this->isOwner($user, $file);
    }

    public function update(User $user, File $file)
    {
        return $this->isOwner($user, $file);
    }

    public function delete(User $user, File $file)
    {
        return $this->isOwner($user, $file);
    }

    public function restore(User $user, File $file)
    {
        return $this->isOwner($user, $file);
    }

    public function forceDelete(User $user, File $file)
    {
        return $this->isOwner($user, $file);
    }

    public function download(User $user, File $file)
    {
        return $this->isOwner($user, $file);
    }

    public function duplicate(User $user, File $file)
    {
        return $this->isOwner($user, $file);
    }
}
