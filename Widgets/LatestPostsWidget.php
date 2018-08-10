<?php

namespace Modules\Blog\Widgets;

use Modules\Blog\Repositories\DocRepository;
use Modules\Dashboard\Foundation\Widgets\BaseWidget;
use Modules\Setting\Contracts\Setting;

class LatestDocsWidget extends BaseWidget
{
    /**
     * @var DocRepository
     */
    private $doc;

    public function __construct(DocRepository $doc, Setting $setting)
    {
        $this->doc = $doc;
        $this->setting = $setting;
    }

    /**
     * Get the widget name
     * @return string
     */
    protected function name()
    {
        return 'LatestDocsWidget';
    }

    /**
     * Get the widget options
     * Possible options:
     *  x, y, width, height
     * @return string
     */
    protected function options()
    {
        return [
            'width' => '4',
            'height' => '4',
        ];
    }

    /**
     * Get the widget view
     * @return string
     */
    protected function view()
    {
        return 'blog::admin.widgets.latest-docs';
    }

    /**
     * Get the widget data to send to the view
     * @return string
     */
    protected function data()
    {
        $limit = $this->setting->get('idocs::widget-docs-amount', locale(), 5);

        return ['docs' => $this->doc->latest($limit)];
    }
}
