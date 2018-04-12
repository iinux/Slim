<?php

/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/5/20
 * Time: 23:20
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 *
 * Class Model
 * @package App\Core
 * @author zhuming
 * @method static Model findOrFail($id, $columns = ['*'])
 * @method static Model find($id, $columns = ['*'])
 * @method static Model create(array $attributes = [])
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder orWhere($column, $operator = null, $value = null)
 * @method static Builder whereIn($column, $values, $boolean = 'and', $not = false)
 * @method static Builder orWhereIn($column, $values)
 * @method static Builder whereNotIn($column, $values, $boolean = 'and')
 * @method static Builder orWhereNotIn($column, $values)
 * @method static Builder orderBy($column, $direction = 'asc')
 */
class Model extends BaseModel
{

}