<?php

namespace Modules\Idocs\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface DocRepository extends BaseRepository
{
    /**
     * faind by id
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * Get the all doc of the given doc
     * @return object
     */
    public function all();


    /**
     * search of category
     * @param $id
     * @return mixed
     */
    public function whereCategory($id);
}
