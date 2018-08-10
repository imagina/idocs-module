<?php

namespace Modules\Idocs\Repositories\Eloquent;

use Modules\Idocs\Repositories\CategoryRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

class EloquentCategoryRepository extends EloquentBaseRepository implements CategoryRepository
{
    /**
     * Find a resource by the given slug
     *
     * @param  string $slug
     * @return object
     */
    public function findBySlug($slug)
    {
        return $this->model->where('slug', "$slug")->firstOrFail();
    }
}
