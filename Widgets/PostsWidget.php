<?php

namespace Modules\Idocs\Widgets;

use Modules\Idocs\Repositories\DocRepository;
use Modules\Dashboard\Foundation\Widgets\BaseWidget;

class DocsWidget extends BaseWidget
{
    /**
     * @var \Modules\Idocs\Repositories\DocRepository
     */
    private $doc;

    public function __construct(DocRepository $doc)
    {
        $this->doc = $doc;
    }

    /**
     * Get the widget name
     * @return string
     */
    protected function name()
    {
        return 'DocsWidget';
    }

    /**
     * Get the widget view
     * @return string
     */
    protected function view()
    {
        return 'idocs::admin.widgets.docs';
    }

    /**
     * Get the widget data to send to the view
     * @return string
     */
    protected function data()
    {
        return ['docCount' => $this->doc->all()->count()];
    }

     /**
     * Get the widget type
     * @return string
     */
    protected function options()
    {
        return [
            'width' => '2',
            'height' => '2',
            'x' => '0',
        ];
    }
}
