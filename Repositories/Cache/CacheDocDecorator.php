<?php

namespace Modules\Idocs\Repositories\Cache;

use Modules\Idocs\Repositories\DocRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheDocDecorator extends BaseCacheDecorator implements DocRepository
{
    public function __construct(DocRepository $doc)
    {
        parent::__construct();
        $this->entityName = 'idocs.docs';
        $this->repository = $doc;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getByCategory($id)
    {
        return $this->remember(function ()use ($id) {
            return $this->repository->getByCategory($id);
        });
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getByUser($id)
    {
        return $this->remember(function ()use ($id) {
            return $this->repository->getByUser($id);
        });
    }
}
