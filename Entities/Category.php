<?php

namespace Modules\Idocs\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Translatable;

    protected $table = 'idocs__categories';
    public $translatedAttributes = ['title','description','slug'];
    protected $fillable = ['title','description','slug','parent_id','options'];

    protected $fakeColumns = ['options'];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array'
    ];

    /*
   |--------------------------------------------------------------------------
   | RELATIONS
   |--------------------------------------------------------------------------
   */
    public function parent()
    {
        return $this->belongsTo('Modules\Idocs\Entities\Category', 'parent_id');
    }
    public function children()
    {
        return $this->hasMany('Modules\Idocs\Entities\Category', 'parent_id');
    }

    public function docs()
    {
        return $this->belongsToMany('Modules\Idocs\Entities\Doc', 'idocs__doc_category');
    }
    public function getOptionsAttribute($value) {
        return json_decode(json_decode($value));
    }
    /*
       |--------------------------------------------------------------------------
       | SCOPES
       |--------------------------------------------------------------------------
       */
    public function scopeFirstLevelItems($query)
    {
        return $query->where('depth', '1')
            ->orWhere('depth', null)
            ->orderBy('lft', 'ASC');
    }
}
