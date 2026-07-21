<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get name attribute for AdminLTE support.
     */
    public function getNameAttribute()
    {
        return $this->nama;
    }

    /**
     * Get email attribute for compatibility.
     */
    public function getEmailAttribute()
    {
        return $this->username;
    }
}
