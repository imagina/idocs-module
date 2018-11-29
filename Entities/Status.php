<?php
/**
 * Created by PhpStorm.
 * User: imagina
 * Date: 28/11/2018
 * Time: 12:15 PM
 */

namespace Modules\Idocs\Entities;


class Status
{
    const DRAFT = 0;
    const PENDING = 1;
    const PUBLISHED = 2;
    const UNPUBLISHED = 3;

    /**
     * @var array
     */
    private $statuses = [];

    public function __construct()
    {
        $this->statuses = [
            self::DRAFT => trans('idocs::common.status.draft'),
            self::PENDING => trans('idocs::common.status.pending'),
            self::PUBLISHED => trans('idocs::common.status.published'),
            self::UNPUBLISHED => trans('idocs::common.status.unpublished'),
        ];
    }

    /**
     * Get the available statuses
     * @return array
     */
    public function lists()
    {
        return $this->statuses;
    }

    /**
     * Get the doc status
     * @param int $statusId
     * @return string
     */
    public function get($statusId)
    {
        if (isset($this->statuses[$statusId])) {
            return $this->statuses[$statusId];
        }

        return $this->statuses[self::DRAFT];
    }
}