<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * UserService: Menangani logika bisnis terkait manajemen pengguna.
 */
class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Ambil semua pengguna dengan paginasi.
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllUsers(int $perPage = 10)
    {
        return $this->userRepository->getAllUsersPaginated($perPage);
    }

    /**
     * Buat pengguna baru, termasuk hashing password dan validasi role.
     *
     * @param array $data
     * @return User
     * @throws \InvalidArgumentException
     */
    public function createUser(array $data): User
    {
        $validRoles = ['admin', 'manager', 'staff'];
        if (!in_array($data['role'], $validRoles)) {
            throw new \InvalidArgumentException("Peran (role) pengguna tidak valid.");
        }

        // Hash password sebelum disimpan
        $data['password'] = Hash::make($data['password']);

        return $this->userRepository->create($data);
    }

    /**
     * Update pengguna.
     *
     * @param int $userId
     * @param array $data
     * @return User
     * @throws ModelNotFoundException|\InvalidArgumentException
     */
    public function updateUser(int $userId, array $data): User
    {
        $user = $this->userRepository->findUserById($userId);

        if (!$user) {
            throw new ModelNotFoundException("Pengguna tidak ditemukan.");
        }

        // Tangani update password (hanya jika ada)
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // Hapus dari data update jika kosong
        }

        // Pastikan role valid
        $validRoles = ['admin', 'manager', 'staff'];
        if (isset($data['role']) && !in_array($data['role'], $validRoles)) {
            throw new \InvalidArgumentException("Peran (role) pengguna tidak valid.");
        }

        // Pastikan nilai is_active berformat boolean saat menyimpan
        if (isset($data['is_active'])) {
            $data['is_active'] = (bool) $data['is_active'];
        }

        $this->userRepository->update($user, $data);
        return $user->fresh();
    }

    /**
     * Hapus pengguna.
     *
     * @param int $userId
     * @return bool
     * @throws ModelNotFoundException|\Exception
     */
    public function deleteUser(int $userId): bool
    {
        $user = $this->userRepository->findUserById($userId);

        if (!$user) {
            throw new ModelNotFoundException("Pengguna tidak ditemukan.");
        }

        // Pencegahan: Admin tidak boleh menghapus akunnya sendiri
        if (auth()->check() && auth()->id() === $user->id) {
            throw new \Exception("Anda tidak dapat menghapus akun Anda sendiri.");
        }

        return $this->userRepository->delete($user);
    }

    /**
     * Hitung total pengguna.
     *
     * @return int
     */
    public function countUsers(): int
    {
        return $this->userRepository->count();
    }
}