<?php

namespace Modules\Idocs\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface DocRepository extends BaseRepository
{
    /**
     * @param $id
     * @return mixed
     */
    public function getByCategory($id);

    /**
     * @param $id
     * @return mixed
     */
    public function getByUser($id);
}
