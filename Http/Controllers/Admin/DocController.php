<?php

namespace Modules\Idocs\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Idocs\Entities\Doc;
use Modules\Idocs\Http\Requests\IdocsRequest;
use Modules\Media\Repositories\FileRepository;

use Modules\Bcrud\Http\Controllers\BcrudController;
use Modules\User\Contracts\Authentication;
use Illuminate\Contracts\Foundation\Application;


class DocController extends BcrudController
{
    /**
     * @var DocRepository
     */
    private $doc;
    private $auth;
    private $file;

    public function __construct(Authentication $auth, FileRepository $file)
    {
        parent::__construct();

        $this->auth = $auth;
        $this->file= $file;
        $driver = config('asgard.user.config.driver');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('Modules\Idocs\Entities\Doc');
        $this->crud->setRoute('backend/idocs/doc');
        $this->crud->setEntityNameStrings(trans('idocs::doc.single'), trans('idocs::doc.plural'));
        $this->access = [];

        $this->crud->enableAjaxTable();
        $this->crud->orderBy('created_at', 'DESC');
        $this->crud->limit(100);


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
            'name' => 'categories', // The db column name
            'label' => trans('idocs::common.categories'),// Table column trans
            'type' => 'select_multiple',
            'attribute' => 'title',
            'entity' => 'categories',
            'model' => "Modules\\Idocs\\Entities\\Category", // foreign key model
            'pivot' => true,
        ]);
        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => trans('idocs::common.created_at'),
        ]);

        // ------ CRUD FIELDS
        $this->crud->addField([
            'name' => 'title',
            'label' => trans('idocs::common.title'),
            'viewposition' => 'left',

        ]);

        $this->crud->addField([
            'name' => 'description',
            'label' => trans('idocs::common.description'),
            'type' => 'wysiwyg',
            'viewposition' => 'left',
        ]);

        $this->crud->addField([  // Select
            'label' => trans('idocs::common.default_category'),
            'type' => 'select',
            'name' => 'category_id', // the db column for the foreign key
            'entity' => 'categories', // the method that defines the relationship in your Model
            'attribute' => 'title', // foreign key attribute that is shown to user
            'model' => "Modules\\Idocs\\Entities\\Category", // foreign key model
            'viewposition' => 'right',
            'nullable' => false
        ]);

        $this->crud->addField([   // DateTime
            'name' => 'created_at',
            'label' => trans('iblog::common.date') . ' ' . trans('iblog::common.optional'),
            'type' => 'datetime_picker',
            // optional:
            'datetime_picker_options' => [
                'format' => 'DD/MM/YYYY HH:mm:ss',
                'language' => 'es',
            ],
            'viewposition' => 'right',
        ]);

        $this->crud->addField([       // Select2Multiple = n-n relationship (with pivot table)
            'label' => trans('idocs::common.categories'),
            'type' => 'checklist',
            'name' => 'categories', // the method that defines the relationship in your Model
            'entity' => 'categories', // the method that defines the relationship in your Model
            'attribute' => 'title', // foreign key attribute that is shown to user
            'model' => "Modules\\Idocs\\Entities\\Category", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
            'viewposition' => 'right',
        ]);


        $this->crud->addField([   // Upload
            'name' => 'url',
            'label' => trans('idocs::common.url'),
            'type' => 'upload',
            'upload' => true,
            'disk' => 'publicmedia',
            'viewposition' => 'right',
        ]);

        $this->crud->addField([  // Select
            'label' => trans('idocs::common.author'),
            'type' => 'user',
            'name' => 'user_id', // the db column for the foreign key
            'entity' => 'user', // the method that defines the relationship in your Model
            'attribute' => 'email', // foreign key attribute that is shown to user
            'model' => "Modules\\User\\Entities\\{$driver}\\User", // foreign key model,
            'viewposition' => 'right',
        ]);

        $this->crud->addField([
            'name' => 'status',
            'label' => trans('idocs::common.status_text'),
            'type' => 'radio',
            'options' => [
                0 => trans('idocs::common.status.draft'),
                1 => trans('idocs::common.status.pending'),
                2 => trans('idocs::common.status.published'),
                3 => trans('idocs::common.status.unpublished')
            ],
            'viewposition' => 'right',
        ]);

        if (config()->has('asgard.idocs.config.fields')) {
            $fields = config()->get('asgard.idocs.config.fields');
            foreach ($fields as $field) {
                $this->crud->addField($field);
            }

        }



    }




    public function setup()
    {
        parent::setup();

        $permissions = ['index', 'create', 'edit', 'destroy'];
        $allowpermissions = [];
        foreach($permissions as $permission) {
            if($this->auth->hasAccess("idocs.docs.$permission")) {
                if($permission=='index') $permission = 'list';
                if($permission=='edit') $permission = 'update';
                if($permission=='destroy') $permission = 'delete';
                $allowpermissions[] = $permission;
            }

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
