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
}
