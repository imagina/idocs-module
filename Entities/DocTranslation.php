<?php

namespace Modules\Idocs\Entities;

use Illuminate\Database\Eloquent\Model;

class DocTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'idocs__doc_translations';
}
