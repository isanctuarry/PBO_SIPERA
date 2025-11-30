<?php
// ========================================
// File: src/Factories/UserFactory.php
// ========================================

namespace App\Factories;

use App\Models\Admin;
use App\Models\Pegawai;
use App\Models\User;

class UserFactory
{
    public static function create(string $type, array $data = []): User
    {
        switch (strtolower($type)) {
            case 'admin':
                $user = new Admin();
                break;
            
            case 'pegawai':
                $user = new Pegawai();
                break;
            
            default:
                throw new \InvalidArgumentException("Unknown user type: {$type}");
        }
        
        if (!empty($data)) {
            $user->fromArray($data);
        }
        
        return $user;
    }

    public static function createFromRow(array $row): User
    {
        if (isset($row['admin_id'])) {
            return self::create('admin', $row);
        } elseif (isset($row['pegawai_id'])) {
            return self::create('pegawai', $row);
        }
        
        throw new \InvalidArgumentException("Cannot determine user type from row");
    }
}