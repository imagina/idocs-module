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

        $this->crud->addField([ // image
            'label' => trans('iblog::common.image'),
            'name' => "mainimage",
            'type' => 'image',
            'upload' => true,
            'crop' => true, // set to true to allow cropping, false to disable
            'aspect_ratio' => 0, // ommit or set to 0 to allow any aspect ratio
            'fake' => true,
            'store_in' => 'options',
            'viewposition' => 'left',
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

        //modificacion para permisologia de usuarios
        $this->crud->addField([       // Select2Multiple = n-n relationship (with pivot table)
            'label' => 'usuarios permitidos',
            'type' => 'checklist',
            'name' => 'users', // the method that defines the relationship in your Model
            'entity' => 'users', // the method that defines the relationship in your Model
            'attribute' => 'email', // foreign key attribute that is shown to user
            'model' => "Modules\\User\\Entities\\{$driver}\\User", // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
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
        if (!empty($request['mainimage']) && !empty($request['id'])) {
            $request['mainimage'] = $this->saveImage($request['mainimage'], "assets/idocs/doc/" . $request['id'] . ".jpg");
        }
        return parent::updateCrud($request);
    }

    /**
     * StoreCRUD
     *
     * @param
     * @return Response
     */
    public function storeCrud(\Modules\Bcrud\Http\Requests\CrudRequest $request = null)
    {

        $this->crud->hasAccessOrFail('create');

        // fallback to global request instance
        if (is_null($request)) {
            $request = \Request::instance();
        }

        // replace empty values with NULL, so that it will work with MySQL strict mode on
        foreach ($request->input() as $key => $value) {
            if (empty($value) && $value !== '0') {
                $request->request->set($key, null);
            }
        }

        //Imagina- Defaults?
        $requestimage = $request["mainimage"];
        //Put a default image while we save.
        $request["mainimage"] = "assets/icommerce/category/default.jpg";


        // insert item in the db
        $item = $this->crud->create($request->except(['save_action', '_token', '_method']));
        $this->data['entry'] = $this->crud->entry = $item;


        //Let's save the image for the post.
        if(!empty($requestimage && !empty($item->id))) {
            $mainimage = $this->saveImage($requestimage,"assets/icommerce/category/".$item->id.".jpg");

            $options = (array)$item->options;
            $options["mainimage"] = $mainimage;

            $item->update($this->crud->compactFakeFields($options));
            //TODO: i don't like the re-save. Find another way to do it.
        }

        // show a success message
        //\Alert::success(trans('bcrud::crud.insert_success'))->flash();

        // redirect the user where he chose to be redirected
        // save the redirect choice for next time
        $this->setSaveAction();

        return $this->performSaveAction($item->getKey());
    }

    /**
     * Save Image.
     *
     * @param  Value
     * @param  Destination
     * @return Response
     */
    public function saveImage($value,$destination_path)
    {

        $disk = "publicmedia";

        //Defined return.
        if(ends_with($value,'.jpg')) {
            return $value;
        }

        // if a base64 was sent, store it in the db
        if (starts_with($value, 'data:image'))
        {
            // 0. Make the image
            $image = \Image::make($value);
            // resize and prevent possible upsizing

            $image->resize(config('asgard.iblog.config.imagesize.width'), config('asgard.iblog.config.imagesize.height'), function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            if(config('asgard.iblog.config.watermark.activated')){
                $image->insert(config('asgard.iblog.config.watermark.url'), config('asgard.iblog.config.watermark.position'), config('asgard.iblog.config.watermark.x'), config('asgard.iblog.config.watermark.y'));
            }
            // 2. Store the image on disk.
            \Storage::disk($disk)->put($destination_path, $image->stream('jpg','80'));


            // Save Thumbs
            \Storage::disk($disk)->put(
                str_replace('.jpg','_mediumThumb.jpg',$destination_path),
                $image->fit(config('asgard.iblog.config.mediumthumbsize.width'),config('asgard.iblog.config.mediumthumbsize.height'))->stream('jpg','80')
            );

            \Storage::disk($disk)->put(
                str_replace('.jpg','_smallThumb.jpg',$destination_path),
                $image->fit(config('asgard.iblog.config.smallthumbsize.width'),config('asgard.iblog.config.smallthumbsize.height'))->stream('jpg','80')
            );

            // 3. Return the path
            return $destination_path;
        }

        // if the image was erased
        if ($value==null) {
            // delete the image from disk
            \Storage::disk($disk)->delete($destination_path);

            // set null in the database column
            return null;
        }
    }
}
