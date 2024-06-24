<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;



    // Roles

    const ROLE_ADMIN = 'ADMIN';
    const ROLE_EMPLOYEE = 'EMPLOYEE';
    const ROLE_USER = 'USER';

    const ROLE_DEFAULT = self::ROLE_USER;
    const ROLES = [
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_EMPLOYEE => 'Employee',
        self::ROLE_USER => 'User',
    ];


    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin() || $this->isEmployee();
    }

    public function isAdmin(){
        return $this->role === self::ROLE_ADMIN;
    }

    public function isEmployee(){
        return $this->role === self::ROLE_EMPLOYEE;
    }



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];



}
