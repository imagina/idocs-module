<?php

namespace Modules\Idocs\Repositories;

use Modules\Core\Repositories\BaseRepository;

/**
 * Interface CategoryRepository
 * @package Modules\Idocs\Repositories
 */
interface CategoryRepository extends BaseRepository
{

    /**
     * @param  string $slug
     * @return mixed
     */
    public function findBySlug($slug);



}
