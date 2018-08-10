<?php

namespace Modules\Idocs\Http\Controllers;

use Mockery\CountValidator\Exception;
use Modules\Idocs\Repositories\CategoryRepository;
use Modules\Idocs\Repositories\DocRepository;
use Modules\Core\Http\Controllers\BasePublicController;
use Route;
use Request;
use Log;

class PublicController extends BasePublicController
{
    /**
     * @var DocRepository
     */
    private $doc;
    private $category;

    public function __construct(DocRepository $doc, CategoryRepository $category)
    {
        parent::__construct();
        $this->doc = $doc;
        $this->category = $category;
    }

    public function index($slug)
    {

        //Default Template
        $tpl = 'idocs::frontend.index';
        $ttpl = 'idocs.index';


        $category = $this->category->findBySlug($slug);
        $docs = $this->doc->whereCategory($category->id);
        //Get Custom Template.
        $ctpl = "idocs.category.{$category->id}.index";
        if (view()->exists($ctpl)) $tpl = $ctpl;

        if (view()->exists($ttpl)) $tpl = $ttpl;

        return view($tpl, compact('docs', 'category'));

    }

    public function show($slug)
    {
        $tpl = 'idocs::frontend.show';
        $ttpl = 'idocs.show';

        $doc = $this->doc->findBySlug($slug);
        $category = $doc->categories()->first();
        //Get Custom Template.
        $ctpl = "idocs.category.{$category->id}.show";
        if (view()->exists($ctpl)) $tpl = $ctpl;
        if (view()->exists($ttpl)) $tpl = $ttpl;

        return view($tpl, compact('doc', 'category'));
    }
}
