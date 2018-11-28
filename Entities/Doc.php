<?php

namespace Modules\Idocs\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Doc extends Model
{
    use Translatable;

    protected $table = 'idocs__docs';
    public $translatedAttributes = [];
    protected $fillable = [];
}
