<?php

namespace Modules\Idocs\Repositories\Eloquent;

use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Iblog\Entities\Status;
use Modules\Idocs\Repositories\DocRepository;

class EloquentDocRepository extends EloquentBaseRepository implements DocRepository
{



    /**
     * @param $id
     * @return mixed
     */
    public function getByCategory($id)
    {

        $query = $this->model->with('translations', 'category');
        $query->whereHas('categories', function (Builder $q) use ($id) {
            $q->where('categroy_id', "$id");
        });
        $query->whereStatus(Status::PUBLISHED);
        $query->where('user_id',0);
        $query->orderBy('created_at', 'DESC')->paginate(12);

        return $query;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getByUser($id)
    {
        $query = $this->model->with('translations', 'category', 'user');
        $query->whereHas('user', function (Builder $q) use ($id) {
            $q->where('user_id', $id);
        });
        $query->orderBy('created_at', 'DESC')->paginate(12);
        $query->whereStatus(Status::PUBLISHED);
        return $query;
    }


    /**
     * @inheritdoc
     */
    public function findBySlug($slug)
    {
        $query = $this->model->with('translations', 'category');

        $query->whereHas('translations', function (Builder $q) use ($slug) {
            $q->where('slug', $slug);
        });
        $query->whereStatus(Status::PUBLISHED);
        $query->whereHas('user', function (Builder $q) {
            $q->where('user_id', null);
        });
        return $query->first();
    }

    /**
     * @inheritdoc
     */
    public function paginate($perPage = 15)
    {

        $query=$this->model->with('translations');
        $query->whereStatus(Status::PUBLISHED);
        $query->whereHas('user', function (Builder $q) {
            $q->where('user_id', null);
        });
        $this->model->orderBy('created_at', 'DESC')->paginate($perPage);

        return $query;


    }

    /**
     * @inheritdoc
     */
    public function create($data)
    {
        $doc=$this->model->create($data);
        $doc->categories()->sync(array_get($data, 'categories', []));
        $doc->users()->sync(array_get($data, 'users', []));
        event(new PostWasCreated($post, $data));
        return $doc;
    }

    /**
     * @inheritdoc
     */
    public function update($model, $data)
    {
        $doc= $model->update($data);
        $doc->categories()->sync(array_get($data, 'categories', []));
        $doc->users()->sync(array_get($data, 'users', []));
        return $model;
    }

}
