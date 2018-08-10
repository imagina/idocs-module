<?php

namespace Modules\Idocs\Http\Controllers\Admin;

use Illuminate\Http\Response;
use Modules\Idocs\Entities\Category;
use Modules\Idocs\Http\Requests\IdocsRequest;

use Modules\Bcrud\Http\Controllers\BcrudController;
use Modules\User\Contracts\Authentication;


class CategoryController extends BcrudController
{
    /**
     * @var CategoryRepository
     */
    private $category;
    private $auth;
    public function __construct(Authentication $auth)
    {
        parent::__construct();
        $this->auth = $auth;

        $driver = config('asgard.user.config.driver');
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('Modules\Idocs\Entities\Category');
        $this->crud->setRoute('backend/idocs/category');
        $this->crud->setEntityNameStrings(trans('idocs::category.single'), trans('idocs::category.plural'));
        $this->access = [];

        $this->crud->enableAjaxTable();
        $this->crud->orderBy('created_at', 'DESC');
        $this->crud->allowAccess('reorder');
        $this->crud->enableReorder('title', 2);

        /*
        |--------------------------------------------------------------------------
        | COLUMNS AND FIELDS
        |--------------------------------------------------------------------------
        */
        // ------ CRUD COLUMNS
        $this->crud->addColumn([
            'name' => 'id',
            'label' => 'ID',
        ]);


        $this->crud->addColumn([
            'name' => 'title',
            'label' => trans('idocs::common.title'),
        ]);

        $this->crud->addColumn([
            'name' => 'slug',
            'label' => trans('idocs::common.slug'),
            'type' => 'text',

        ]);

        $this->crud->addColumn([
            'label' => trans('idocs::common.parent'),
            'type' => 'select',
            'name' => 'parent_id',
            'entity' => 'parent',
            'attribute' => 'title',
            'model' => 'Modules\Idocs\Entities\Category',
            'defaultvalue' => '0'
        ]);


        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => trans('idocs::common.created_at'),
        ]);

        // ------ CRUD FIELDS
        $this->crud->addField([
            'name' => 'title',
            'label' => trans('idocs::common.title'),
            'viewposition' => 'left'
        ]);

        $this->crud->addField([
            'name' => 'slug',
            'label' => 'Slug',
            'type' => 'text',
            'viewposition' => 'left'

        ]);

        $this->crud->addField([
            'label' => trans('idocs::common.parent'),
            'type' => 'select',
            'name' => 'parent_id',
            'entity' => 'parent',
            'attribute' => 'title',
            'model' => "Modules\\Idocs\\Entities\\Category", // foreign key model
            'viewposition' => 'right',
            'emptyvalue'=>0
        ]);


        $this->crud->addField([
            'name' => 'description',
            'label' => trans('idocs::common.description'),
            'type' => 'wysiwyg',
            'viewposition' => 'left'
        ]);


    }


    public function setup()
    {
        parent::setup();

        $permissions = ['index', 'create', 'edit', 'destroy'];
        $allowpermissions = [];
        foreach($permissions as $permission) {

            if($this->auth->hasAccess("idocs.categories.$permission")) {
                if($permission=='index') $permission = 'list';
                if($permission=='edit') $permission = 'update';
                if($permission=='destroy') $permission = 'delete';
                $allowpermissions[] = $permission;
            }

            $allowpermissions[] = 'reorder';

        }

        $this->crud->access = $allowpermissions;
    }

    public function store(IdocsRequest $request)
    {
        return parent::storeCrud();
    }


    public function update(IdocsRequest $request)
    {
        return parent::updateCrud($request);
    }


}
