<?php

namespace App\Models;

use Hyperf\Database\Model\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password'];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }
}