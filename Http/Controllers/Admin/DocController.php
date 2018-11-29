<?php

namespace Modules\Idocs\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Idocs\Entities\Doc;
use Modules\Idocs\Repositories\CategoryRepository;
use Modules\Idocs\Entities\Status;
use Modules\Idocs\Http\Requests\CreateDocRequest;
use Modules\Idocs\Http\Requests\UpdateDocRequest;
use Modules\Idocs\Repositories\DocRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\User\Repositories\UserRepository;
class DocController extends AdminBaseController
{
    /**
     * @var DocRepository
     */
    private $doc;
    private $user;
    private $status;
    private $category;

    public function __construct(DocRepository $doc,UserRepository $user,Status $status,CategoryRepository $category)
    {
        parent::__construct();

        $this->doc = $doc;
        $this->status = $status;
        $this->user = $user;
        $this->category=$category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $docs = $this->doc->paginate(12);

        return view('idocs::admin.docs.index', compact('docs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $users = $this->user->all();
        $categories=$this->category->all();
        $statuses = $this->status->lists();

        return view('idocs::admin.docs.create', compact('users','categories','statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateDocRequest $request
     * @return Response
     */
    public function store(CreateDocRequest $request)
    {
        $this->doc->create($request->all());

        return redirect()->route('admin.idocs.doc.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('idocs::docs.title.docs')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Doc $doc
     * @return Response
     */
    public function edit(Doc $doc)
    {
        $users = $this->user->all();
        $categories=$this->category->all();
        $statuses = $this->status->lists();
        return view('idocs::admin.docs.edit', compact('doc','users','categories','statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Doc $doc
     * @param  UpdateDocRequest $request
     * @return Response
     */
    public function update(Doc $doc, UpdateDocRequest $request)
    {
        $this->doc->update($doc, $request->all());

        return redirect()->route('admin.idocs.doc.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('idocs::docs.title.docs')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Doc $doc
     * @return Response
     */
    public function destroy(Doc $doc)
    {
        $this->doc->destroy($doc);

        return redirect()->route('admin.idocs.doc.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('idocs::docs.title.docs')]));
    }
}
