<?php

namespace App\Services;

use App\Helpers\StringHelper;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function list(array $filter)
    {
        return User::where($filter)->get();
    }

    public function create(array $data)
    {
        $code = StringHelper::newCode(8);
        $data['password'] = Hash::make($code);

        $user = User::create($data);

        $user['code'] = $code;

        return $user;
    }

    public function delete(array $data): void
    {
        $user = User::where('telegram_id', $data['telegram_id']);

        if (!$user->exists()) {
            throw new Exception("Usuário não encontrado");
        }

        $deletedCount = $user->where('email', $data['email'])->delete();

        if ($deletedCount === 0) {
            throw new Exception("Email inválido. O email não corresponde a sua conta.");
        }
    }
}
