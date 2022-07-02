<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function getJsonDataAttribute($value)
    {
        $jsonObject = json_decode($value);
        if (is_null($jsonObject)) {
            $jsonObject = new \stdClass();
        }
        return $jsonObject;

    }

    public function hasRight($right = null)
    {
        if (isset($this->json_data->isRoot) && $this->json_data->isRoot) {
            return true;
        }
        if (isset($this->json_data->$right) && $this->json_data->$right) {
            return true;
        }
        return false;
    }
}