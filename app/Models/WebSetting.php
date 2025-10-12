<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebSetting extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'description' , 'keywords' , 'logo' , 'icon'];
}
