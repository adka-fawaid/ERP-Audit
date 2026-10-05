<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QadUserRole extends Model
{
    protected $table = 'qad_user_roles';

    protected $fillable = [
        'nik',
        'role',
        'status',
    ];
}