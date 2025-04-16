<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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
        $cacheKey = "user_setting_{$userId}_{$key}";
        
        return Cache::remember($cacheKey, 60*24, function() use ($key, $userId, $default) {
            $setting = self::where('key', $key)
                ->where('user_id', $userId)
                ->first();
                
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set or update a user setting
     *
     * @param string $key
     * @param mixed $value
     * @param int $userId
     * @return Setting
     */
    public static function setUserSetting($key, $value, $userId)
    {
        $setting = self::updateOrCreate(
            ['key' => $key, 'user_id' => $userId],
            ['value' => $value]
        );
        
        // Clear the cache for this setting
        Cache::forget("user_setting_{$userId}_{$key}");
        
        return $setting;
    }

    /**
     * Get multiple user settings at once
     *
     * @param array $keys
     * @param int $userId
     * @param array $defaults
     * @return array
     */
    public static function getUserSettings(array $keys, $userId, array $defaults = [])
    {
        $settings = self::where('user_id', $userId)
            ->whereIn('key', $keys)
            ->get()
            ->pluck('value', 'key')
            ->toArray();
            
        foreach ($keys as $key) {
            if (!isset($settings[$key]) && isset($defaults[$key])) {
                $settings[$key] = $defaults[$key];
            } elseif (!isset($settings[$key])) {
                $settings[$key] = null;
            }
        }
        
        return $settings;
    }
}
