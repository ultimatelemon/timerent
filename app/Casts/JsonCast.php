<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class JsonCast implements CastsAttributes
{
    /**
     * Cast the given value
     *
     * @param $model
     * @param $key
     * @param $value
     * @param $attributes
     * @return mixed|null
     */
    public function get($model, $key, $value, $attributes)
    {
        return $value == null ? null : json_decode($value, true);
    }

    /**
     * Prepare the given value for storage
     *
     * @param $model
     * @param $key
     * @param $value
     * @param $attributes
     * @return mixed
     */
    public function set($model, $key, $value, $attributes)
    {
        return $value == null ? [] : json_encode($value, true);
    }
}