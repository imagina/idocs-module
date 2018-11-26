<?php

namespace Modules\Idocs\Http\Controllers;

use Mockery\CountValidator\Exception;
use Modules\Idocs\Repositories\CategoryRepository;
use Modules\Idocs\Repositories\DocRepository;
use Modules\Core\Http\Controllers\BasePublicController;
use Route;
use Request;
use Log;

use Modules\User\Contracts\Authentication;
use Modules\Iprofile\Repositories\ProfileRepository;

class PublicController extends BasePublicController
{
    /**
     * @var DocRepository
     */
    private $doc;
    private $category;

    protected $auth;
    private $profile;

    public function __construct(DocRepository $doc, CategoryRepository $category, ProfileRepository $profile)
    {
        parent::__construct();
        $this->doc = $doc;
        $this->category = $category;

        $this->profile = $profile;
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

    public function see_all()
    {
        $user = $this->auth->user();

        if(isset($user) && !empty($user)) {
            //Default Template
            $tpl = 'iprofile::frontend.document';
            $ttpl = 'iprofile.document';

            $docs = $this->doc->whereUser($user->id);

            if (view()->exists($ttpl)) $tpl = $ttpl;

            return view($tpl, compact('docs', 'user'));
        }else{
            return redirect()->intended(route(config('asgard.user.config.redirect_route_after_login')));
        }
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
