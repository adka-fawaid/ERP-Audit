<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'mst_anggota';

    protected $primaryKey = 'id_anggota';

    public $timestamps = false;

    protected $fillable = [
        'nik',
        'nama',
        'email',
        'password_hash',
        'status_hapus',
        'freeze',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected $appends = [
        'name',
        'role',
        'is_active',
    ];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function getNameAttribute()
    {
        return $this->attributes['nama'] ?? null;
    }

    public function qadRole()
    {
        return $this->hasOne(QadUserRole::class, 'nik', 'nik');
    }

    public function getRoleAttribute()
    {
        return optional($this->qadRole)->role ?? 'viewer';
    }

    public function getIsActiveAttribute()
    {
        return (int) ($this->attributes['status_hapus'] ?? 1) === 1
            && (int) ($this->attributes['freeze'] ?? 0) === 0;
    }

    public function getIdAttribute()
    {
        return $this->attributes['id_anggota'] ?? null;
    }
}