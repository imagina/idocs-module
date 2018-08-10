<?php

namespace Modules\Idocs\Repositories\Cache;

use Modules\Idocs\Repositories\CategoryRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheCategoryDecorator extends BaseCacheDecorator implements CategoryRepository
{
    public function __construct(CategoryRepository $category)
    {
        parent::__construct();
        $this->entityName = 'categories';
        $this->repository = $category;
    }


    /**
     * @param $name
     * @return mixed
     */
    public function findBySlug($slug)
    {
        return $this->cache
            ->tags([$this->entityName, 'global'])
            ->remember("{$this->locale}.{$this->entityName}.findByName.{$slug}", $this->cacheTime,
                function () use ($slug) {
                    return $this->repository->findByName($slug);
                }
            );
    }

}
