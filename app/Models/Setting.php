<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    protected static ?array $runtimeCache = null;

    /**
     * Get a setting value by key with optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        if (self::$runtimeCache === null) {
            self::loadRuntimeCache();
        }

        return self::$runtimeCache[$key] ?? $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        if (self::$runtimeCache !== null) {
            self::$runtimeCache[$key] = $value;
        }

        Cache::forget('site_settings_all');
    }

    /**
     * Get all settings as key => value array.
     */
    public static function getAll(): array
    {
        if (self::$runtimeCache === null) {
            self::loadRuntimeCache();
        }

        return self::$runtimeCache;
    }

    /**
     * Load settings into runtime memory cache.
     */
    protected static function loadRuntimeCache(): void
    {
        try {
            self::$runtimeCache = Cache::remember('site_settings_all', 3600, function () {
                return static::pluck('value', 'key')->toArray();
            });
        } catch (\Throwable $e) {
            self::$runtimeCache = [];
        }
    }

    /**
     * Clear runtime and persistent cache.
     */
    public static function clearCache(): void
    {
        self::$runtimeCache = null;
        Cache::forget('site_settings_all');
    }
}
