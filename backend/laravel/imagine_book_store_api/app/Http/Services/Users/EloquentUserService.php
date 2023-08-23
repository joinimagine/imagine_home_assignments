<?php

namespace App\Http\Services\Users;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EloquentUserService implements UserService
{

    public function store($data) {

        $user = new User($data);
        $user->password = Hash::make($user->password);
        $user->save();

        $this->assignDefaultRole($user);

        return $user;
    }

    protected function assignDefaultRole($user) {

        $user->attachRole(Role::getUserRole());
    }

}
