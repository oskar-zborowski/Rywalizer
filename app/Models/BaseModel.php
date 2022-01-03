<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class BaseModel extends Model
{
    use FilterQueryString, HasFactory;

    // protected $filters = [
    //     'sort',
    //     'greater',
    //     'greater_or_equal',
    //     'less',
    //     'less_or_equal',
    //     'between',
    //     'not_between',
    //     'in',
    //     'like'
    // ];
}
