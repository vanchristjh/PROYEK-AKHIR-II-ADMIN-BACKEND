<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'user_id',
    ];

    /**
     * Cast attributes to appropriate types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'string',
    ];

    /**
     * The user that owns the setting.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get setting value by key for a user
     *
     * @param string $key
     * @param int $userId
     * @param mixed $default
     * @return mixed
     */
    public static function getUserSetting($key, $userId, $default = null)
    {
        $setting = self::where('key', $key)
            ->where('user_id', $userId)
            ->first();

        return $setting ? $setting->value : $default;
    }
}
