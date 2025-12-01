<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Models\User; // Digunakan untuk type hinting dan route model binding

class UserController extends Controller
{
    protected $userService;

    // Daftar Role yang valid untuk digunakan di form
    protected $validRoles = [
        'admin' => 'Admin',
        'manager' => 'Manajer Gudang',
        'staff' => 'Staff Gudang'
    ];

    public function __construct(UserService $userService)
    {
        // Dependency Injection UserService
        $this->userService = $userService;
    }

    /**
     * Menampilkan daftar semua pengguna dengan paginasi.
     * Route: GET admin/users
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Memanggil Service untuk mengambil data
        $users = $this->userService->getAllUsers(10);

        // Pastikan Anda memiliki view di 'resources/views/admin/users/index.blade.php'
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan form untuk membuat pengguna baru.
     * Route: GET admin/users/create
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Pastikan Anda memiliki view di 'resources/views/admin/users/create.blade.php'
        return view('admin.users.create', ['roles' => $this->validRoles]);
    }

    /**
     * Menyimpan pengguna baru ke database.
     * Route: POST admin/users
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,staff',
        ], [
            'required' => ':attribute wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        try {
            // 2. Memanggil Service untuk logika bisnis dan penyimpanan
            $this->userService->createUser($request->all());

            return redirect()->route('admin.users.index')
                ->with('success', 'Pengguna berhasil ditambahkan.');

        } catch (\Exception $e) {
            // Log error
            \Log::error('Gagal menyimpan pengguna: ' . $e->getMessage());
            return back()->withInput()->withErrors('Gagal menambahkan pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk mengedit pengguna.
     * Route: GET admin/users/{user}/edit
     *
     * @param User $user (Route Model Binding)
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        // Pastikan Anda memiliki view di 'resources/views/admin/users/edit.blade.php'
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $this->validRoles
        ]);
    }

    /**
     * Mengupdate pengguna di database.
     * Route: PUT/PATCH admin/users/{user}
     *
     * @param Request $request
     * @param User $user (Route Model Binding)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        // 1. Validasi Input
        $rules = [
            'name' => 'required|string|max:255',
            // Unique email, kecuali untuk diri sendiri
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,manager,staff',
            'is_active' => 'required|boolean',
        ];

        // Password hanya divalidasi jika diisi (opsional)
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $request->validate($rules, [
            'required' => ':attribute wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        try {
            // 2. Persiapkan data dan koersi nilai boolean
            $data = $request->all();
            $data['is_active'] = $request->boolean('is_active');

            // 3. Memanggil Service untuk logika bisnis dan penyimpanan
            $this->userService->updateUser($user->id, $data);

            return redirect()->route('admin.users.index')
                ->with('success', 'Pengguna berhasil diperbarui.');

        } catch (\Exception $e) {
            // Log error
            \Log::error('Gagal memperbarui pengguna: ' . $e->getMessage());
            return back()->withInput()->withErrors('Gagal memperbarui pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus pengguna dari database.
     * Route: DELETE admin/users/{user}
     *
     * @param User $user (Route Model Binding)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        try {
            // 1. Memanggil Service untuk logika bisnis dan penghapusan
            $this->userService->deleteUser($user->id);

            return redirect()->route('admin.users.index')
                ->with('success', 'Pengguna berhasil dihapus.');

        } catch (\Exception $e) {
            // Log error
            \Log::error('Gagal menghapus pengguna: ' . $e->getMessage());
            return back()->withErrors('Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }
}