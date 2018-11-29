<?php

namespace Modules\Idocs\Http\Controllers;

use Log;
use Modules\Core\Http\Controllers\BasePublicController;
use Modules\Idocs\Repositories\CategoryRepository;
use Modules\Idocs\Repositories\DocRepository;
use Modules\User\Contracts\Authentication;
use Request;
use Route;

class PublicController extends BasePublicController
{

    private $doc;
    private $category;
    private $auth;

    public function __construct(DocRepository $doc, CategoryRepository $category)
    {
        parent::__construct();
        $this->doc = $doc;
        $this->category = $category;

    }

    public function index()
    {
        $docs = $this->doc->paginate(12);
        $tpl = 'idocs::frontend.index';
        return view($tpl, compact('docs'));
    }

    public function category($categorySlug)
    {
        $category = $this->category->findBySlug($categorySlug);
        $docs = $this->doc->getByCategory($category->id);
        $tpl = 'idocs::frontend.index';
        return view($tpl, compact('docs', 'category'));
    }

    public function show($categorySlug, $slug)
    {
        $category = $this->category->findBySlug($categorySlug);
        $doc = $this->doc->findBySlug($slug);

        if($doc->category_id!==$category->id){
            return abort(404);
        }

        $tpl = 'idocs::frontend.index';

        return view($tpl, compact('doc', 'category'));
    }

    public function indexUser()
    {
        $this->auth=new Authentication;
        $user=$this->auth->user();
        $docs = $this->doc->getByUser($user->id);
        $tpl = 'idocs::frontend.index';
        return view($tpl, compact('doc'));
    }

}