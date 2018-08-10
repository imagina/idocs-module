<?php

namespace Modules\Idocs\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Modules\Idocs\Entities\Doc;
use Modules\Idocs\Entities\Status;
use Modules\Idocs\Repositories\Collection;
use Modules\Idocs\Repositories\DocRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Laracasts\Presenter\PresentableTrait;

class EloquentDocRepository extends EloquentBaseRepository implements DocRepository
{
    /**
     * @param  int $id
     * @return object
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->model->orderBy('created_at', 'DESC')->paginate(12);
    }

    /**
     * Update a resource
     * @param $doc
     * @param  array $data
     * @return mixed
     */
    public function update($doc, $data)
    {
        $doc->update($data);


        //event(new DocWasUpdated($doc, $data));

        return $doc;
    }

    /**
     * Create a idocs doc
     * @param  array $data
     * @return Doc
     */
    public function create($data)
    {
        $doc = $this->model->create($data);

        event(new DocWasCreated($doc, $data));

        return $doc;
    }

    public function destroy($model)
    {
        //event(new DocWasDeleted($model->id, get_class($model)));

        return $model->delete();
    }


    /**
     * search a set of post for id category
     *
     * @param  object $id
     * @return object
     */

    public function whereCategory($id)
    {

        return $this->model->whereHas('categories', function ($query) use ($id) {
            $query->where('category_id', $id);
        })->orderBy('created_at', 'DESC')->paginate(12);

    }
}
