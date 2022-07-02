<?php

namespace App\Models;


class ChPrice extends Model
{

    protected $guarded = ['id'];

    const STATUS_NEW = 10;
    const STATUS_HISTORY = 20;
}
