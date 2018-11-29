<?php

namespace Modules\Idocs\Presenters;

use Laracasts\Presenter\Presenter;
use Modules\Idocs\Entities\Status;

class DocPresenter extends Presenter
{
    /**
     * @var \Modules\Idocs\Entities\Status
     */
    protected $status;
    /**
     * @var \Modules\Idocs\Repositories\DocRepository
     */
    private $doc;

    public function __construct($entity)
    {
        parent::__construct($entity);
        $this->doc = app('Modules\Idocs\Repositories\DocRepository');
        $this->status = app('Modules\Idocs\Entities\Status');
    }

    /**
     * Get the doc status
     * @return string
     */
    public function status()
    {
        return $this->status->get($this->entity->status);
    }

    /**
     * Getting the label class for the appropriate status
     * @return string
     */
    public function statusLabelClass()
    {
        switch ($this->entity->status) {
            case Status::DRAFT:
                return 'bg-red';
                break;
            case Status::PENDING:
                return 'bg-orange';
                break;
            case Status::PUBLISHED:
                return 'bg-green';
                break;
            case Status::UNPUBLISHED:
                return 'bg-purple';
                break;
            default:
                return 'bg-red';
                break;
        }
    }
}
