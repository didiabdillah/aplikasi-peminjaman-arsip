<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'status',
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
    ];

    /**
    * Check if user is admin
    */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    /**
    * Check if user is peminjam
    */
    public function isPeminjam()
    {
        return $this->role === 'peminjam';
    }
    /**
    * Get archives created by this user
    */
    public function createdArchives()
    {
        return $this->hasMany(Archive::class, 'created_by');
    }
    /**
    * Get loans made by this user
    */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
    /**
    * Get loans approved by this user (for admin)
    */
    public function approvedLoans()
    {
        return $this->hasMany(Loan::class, 'approved_by');
    }
}
