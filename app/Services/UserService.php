<?php

namespace App\Services;

use App\Helpers\StringHelper;
use App\Models\User;
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

    public function delete(array $data)
    {
        $user = User::where('telegram_id', $data['telegram_id']);
        $user->delete();
    }
}
