<?php

namespace Modules\Idocs\Repositories\Cache;

use Modules\Idocs\Repositories\Collection;
use Modules\Idocs\Repositories\DocRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheDocDecorator extends BaseCacheDecorator implements DocRepository
{
    public function __construct(DocRepository $doc)
    {
        parent::__construct();
        $this->entityName = 'doc';
        $this->repository = $doc;
    }

    /**
     * faind by id
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->cache
            ->remember("{$this->locale}.{$this->entityName}.find.{$id}", $this->cacheTime,
                function () use ($id) {
                    return $this->repository->find($id);
                }
            );
    }

    /**
     * Get the all doc of the given doc
     * @return object
     */
    public function all()
    {

        return $this->cache
            ->remember("{$this->locale}.{$this->entityName}.all", $this->cacheTime,
                function () {
                    return $this->repository->all();
                }
            );
    }

    /**
     * search of category
     * @param $slug
     * @return object
     */
    public function whereCategory($id)
    {

        return $this->cache
            ->remember("{$this->locale}.{$this->entityName}.whereCategory.{$id}", $this->cacheTime,
                function () use ($id) {
                    return $this->repository->whereCategory($id);
                }
            );
    }
}
