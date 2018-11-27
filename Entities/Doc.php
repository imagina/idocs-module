<?php

namespace Modules\Idocs\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Bcrud\Support\Traits\CrudTrait;
use Laracasts\Presenter\PresentableTrait;
use Modules\Idocs\Presenters\DocPresenter;

class Doc extends Model
{
    use CrudTrait,  PresentableTrait;

    protected $table = 'idocs__docs';

    protected $fillable = ['title','description','url','options','category_id','user_id','customer_id','role_id','status'];
    protected $presenter = DocPresenter::class;
    protected $fakeColumns = ['options'];


    /**
     * The attributes that should be casted to native types.
     *
     * @var array
    */


    protected $casts = [
        'options' => 'array'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'idocs__doc__category');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //testeandose
    public function users()
    {
        $driver = config('asgard.user.config.driver');
        return $this->belongsToMany("Modules\\User\\Entities\\{$driver}\\User", 'idocs__doc__user', 'doc_id', 'user_allowed_id')->withTimestamps();
    }

    public function setUrlAttribute($value){

        $attribute_name = "url";
        $disk = "publicmedia";
        $destination_path = "assets/documets/".$this->category_id;

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function getSizeAttribute(){
        $doc= \Storage::disk('publicmedia')->getMetaData($this->url);
        $size = $doc['size'];


         return $this->bytes($size);

    }

   private function bytes($a) {
        $size = array("B","KB","MB","GB","TB","PB");
        $c = 0;
        while ($a>=1024) {
            $c++;
            $a = $a/1024;
        }
        return number_format($a,($c ? 2 : 0),",",".")." ".$size[$c];
   }

    public function getOptionsAttribute($value) {
        return json_decode(json_decode($value));
    }
}
