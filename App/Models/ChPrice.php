<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/5/20
 * Time: 23:21
 */

namespace App\Models;


class ChPrice extends Model
{

    protected $guarded = ['id'];

    const STATUS_NEW = 10;
    const STATUS_HISTORY = 20;
}
