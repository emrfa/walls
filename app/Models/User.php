<?php

namespace App\Models;

class User
{
    public $name;
    public $email;
    public $password;
    public $nip;

    public $roles = [];

    public function __construct($data = [])
    {
        $this->name = $data['name'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->password = $data['password'] ?? null;
        $this->nip = $data['nip'] ?? null;
        $this->roles = $data['roles'] ?? [];
    }

    public function hasPermissionTo($permission)
    {
        foreach ($this->roles as $role) {
            if (in_array($permission, $role['permissions'] ?? [])) {
                return true;
            }
        }
        return false;
    }

    public function hasRole($roleName)
    {
        foreach ($this->roles as $role) {
            if (($role['name'] ?? '') === $roleName) {
                return true;
            }
        }
        return false;
    }
}