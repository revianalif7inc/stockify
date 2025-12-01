<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = ['key', 'value'];

    public $timestamps = true;

    public static function get($key, $default = null)
    {
        $cacheKey = 'setting.' . $key;
        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $row = static::where('key', $key)->first();
            if (!$row)
                return $default;
            return $row->value;
        });
    }

    public static function set($key, $value)
    {
        $result = static::updateOrCreate(['key' => $key], ['value' => $value]);
        // invalidate cache for this key
        Cache::forget('setting.' . $key);
        return $result;
    }
}
