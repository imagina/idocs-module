<?php

namespace Modules\Idocs\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Idocs\Entities\Doc;
use Modules\Idocs\Http\Requests\CreateDocRequest;
use Modules\Idocs\Http\Requests\UpdateDocRequest;
use Modules\Idocs\Repositories\DocRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class DocController extends AdminBaseController
{
    /**
     * @var DocRepository
     */
    private $doc;

    public function __construct(DocRepository $doc)
    {
        parent::__construct();

        $this->doc = $doc;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$docs = $this->doc->all();

        return view('idocs::admin.docs.index', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('idocs::admin.docs.create');
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
        return view('idocs::admin.docs.edit', compact('doc'));
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
