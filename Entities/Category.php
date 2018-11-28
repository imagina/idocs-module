<?php

namespace Modules\Idocs\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Translatable;

    protected $table = 'idocs__categories';
    public $translatedAttributes = [];
    protected $fillable = [];
}
