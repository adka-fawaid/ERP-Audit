<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'user',
        'activity',
        'description',
        'ip_address',
        'browser',
    ];

    public static function record(Request $request, string $activity, string $description): ?self
    {
        $user = auth()->user();

        if (!$user) {
            return null;
        }

        return static::create([
            'user' => Str::limit($user->nik ?: $user->email ?: $user->name, 100, ''),
            'activity' => $activity,
            'description' => $description,
            'ip_address' => $request->ip(),
            'browser' => $request->userAgent(),
        ]);
    }
}