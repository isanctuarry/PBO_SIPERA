<?php
namespace App\Core;

class App
{
    public function __construct()
    {
        // Set timezone default
        date_default_timezone_set('Asia/Jakarta');

        // Error reporting untuk debugging (non-production)
        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        // Jika ingin menambahkan environment loader (opsional):
        // $this->loadEnv();
    }

    /**
     * (Opsional) Fungsi untuk memuat file .env
     * Jika kamu ingin pakai dotenv, tinggal aktifkan.
     */
    private function loadEnv(): void
    {
        $envFile = __DIR__ . '/../../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue; // skip comments
                putenv($line);
            }
        }
    }
}
