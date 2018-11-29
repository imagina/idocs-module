<?php


namespace Modules\Idocs\Events\Handlers;

use Modules\Idocs\Events\DocWasCreated;
use Modules\Idocs\Repositories\DocRepository;

class SaveDocOptions
{
    private $doc;
    public function __construct(DocRepository $doc)
    {
        $this->doc = $doc;
    }
    public function handle(DocWasCreated $event)
    {
        $id = $event->entity->id;
        if (!empty($event->data['mainimage'])) {
            $mainimage = saveImage($event->data['mainimage'], "assets/idocs/doc/" . $id . ".jpg");
            if(isset($event->data['options'])){
                $options=(array)$event->data['options'];
            }else{$options = array();}
            $options["mainimage"] = $mainimage;

            $event->data['options'] = json_encode($options);
        }else{
            $event->data['options'] = json_encode($event->data['options']);
        }
        $this->doc->update($event->entity, $event->data);
    }
}