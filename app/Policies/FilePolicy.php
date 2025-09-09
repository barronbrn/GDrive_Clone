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
    // Menentukan apakah pengguna adalah pemilik file (pembantu pribadi)
    private function isOwner(User $user, File $file)
    {
        return $file->created_by === $user->id;
    }

    // Menentukan apakah pengguna dapat melihat file
    public function view(User $user, File $file)
    {
        return $this->isOwner($user, $file);
    }

    // Menentukan apakah pengguna dapat memperbarui file
    public function update(User $user, File $file)
    {
        return $this->isOwner($user, $file);
    }

    // Menentukan apakah pengguna dapat menghapus file
    public function delete(User $user, File $file)
    {
        return $this->isOwner($user, $file);
    }

    // Menentukan apakah pengguna dapat memulihkan file
    public function restore(User $user, File $file)
    {
        return $this->isOwner($user, $file);
    }

    // Menentukan apakah pengguna dapat secara paksa menghapus file
    public function forceDelete(User $user, File $file)
    {
        return $this->isOwner($user, $file);
    }

    // Menentukan apakah pengguna dapat mengunduh file
    public function download(User $user, File $file)
    {
        return $this->isOwner($user, $file);
    }
}
