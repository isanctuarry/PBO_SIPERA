<?php
namespace App\Services;

use App\Repositories\AdminRepository;
use App\Repositories\PegawaiRepository;
use App\Utils\Jwt;
use App\Exceptions\HttpException;
use App\Exceptions\ValidationException;

class AuthService
{
    private AdminRepository $adminRepo;
    private PegawaiRepository $pegawaiRepo;

    public function __construct()
    {
        $this->adminRepo = new AdminRepository();
        $this->pegawaiRepo = new PegawaiRepository();
    }

    /**
     * Validasi input
     */
    public function validateLogin(array $data): void
    {
        $errors = [];

        if (empty($data['username'])) {
            $errors['username'] = 'Username wajib diisi';
        }

        if (empty($data['password'])) {
            $errors['password'] = 'Password wajib diisi';
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
    }

    /**
     * Login untuk admin dan pegawai (2 tabel berbeda)
     */
    public function login(string $username, string $password): array
    {
        // 1. Coba login sebagai ADMIN
        $admin = $this->adminRepo->findByUsername($username);
        if ($admin && password_verify($password, $admin['password'])) {

            $token = Jwt::encode([
                'sub'  => $admin['id'],
                'role' => 'admin'
            ]);

            return [
                'token' => $token,
                'user' => [
                    'id'   => $admin['id'],
                    'nama' => $admin['nama'],
                    'username' => $admin['username'],
                    'role' => 'admin'
                ]
            ];
        }

        // 2. Jika bukan admin → coba login sebagai PEGAWAI
        $pegawai = $this->pegawaiRepo->findByUsername($username);
        if ($pegawai && password_verify($password, $pegawai['password'])) {

            $token = Jwt::encode([
                'sub'  => $pegawai['id'],
                'role' => 'pegawai'
            ]);

            return [
                'token' => $token,
                'user' => [
                    'id'   => $pegawai['id'],
                    'nama' => $pegawai['nama'],
                    'username' => $pegawai['username'],
                    'role' => 'pegawai'
                ]
            ];
        }

        // Jika keduanya tidak ditemukan atau password salah
        throw new HttpException(401, "Username atau password salah");
    }
}
