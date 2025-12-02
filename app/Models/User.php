<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected string $table = 'users';

    protected array $fillable = [
        'email',
        'password',
        'name',
        'role',
        'verification_token',
        'email_verified_at',
        'is_active',
    ];

    public function findByEmail(string $email): ?array
    {
        return $this->first('email', $email);
    }

    public function verifyEmail(string $token): bool
    {
        $user = $this->first('verification_token', $token);

        if (!$user) {
            return false;
        }

        return $this->update($user['id'], [
            'email_verified_at' => date('Y-m-d H:i:s'),
            'verification_token' => null,
        ]);
    }

    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
