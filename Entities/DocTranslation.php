<?php

namespace Modules\Idocs\Entities;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class DocTranslation extends Model
{
    use Sluggable;
    public $timestamps = false;
    protected $fillable = ['title','description','slug','url'];
    protected $table = 'idocs__doc_translations';

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
