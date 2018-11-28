<?php

namespace Modules\Idocs\Repositories\Cache;

use Modules\Idocs\Repositories\CategoryRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheCategoryDecorator extends BaseCacheDecorator implements CategoryRepository
{
    public function __construct(CategoryRepository $category)
    {
        parent::__construct();
        $this->entityName = 'idocs.categories';
        $this->repository = $category;
    }
}
