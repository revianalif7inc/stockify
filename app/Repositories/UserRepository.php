<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

/**
 * UserRepository: Bertanggung jawab untuk interaksi data dasar (CRUD) dengan Model User.
 */
class UserRepository
{
    /**
     * Ambil semua pengguna dengan paginasi.
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllUsersPaginated(int $perPage = 10)
    {
        return User::orderBy('name', 'asc')->paginate($perPage);
    }

    /**
     * Temukan pengguna berdasarkan ID.
     *
     * @param int $id
     * @return User|null
     */
    public function findUserById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Buat pengguna baru.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Update pengguna yang sudah ada.
     *
     * @param User $user
     * @param array $data
     * @return bool
     */
    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    /**
     * Hapus pengguna.
     *
     * @param User $user
     * @return bool|null
     */
    public function delete(User $user): ?bool
    {
        return $user->delete();
    }

    /**
     * Hitung total pengguna.
     *
     * @return int
     */
    public function count(): int
    {
        return User::count();
    }
}