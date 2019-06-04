<?php /** @noinspection ALL */

namespace App\Http\Controllers\API\Admin\Documents;

use App\{Document, Driver, User};
use App\Http\Requests\API\Documents\{CreateDocumentRequest, UpdateDocumentRequest};

class DriverDocumentsController extends DocumentsController
{
    protected $type;

    public function __construct ()
    {
        $this->type = Driver::class;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param User $driver
     * @param CreateDocumentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function setDocument (User $user, CreateDocumentRequest $request)
    {
        $attributes = $request->all();
        $attributes['documentable_id'] = $user->driver->id;
        $attributes['documentable_type'] = $this->type;
        $document = parent::store($attributes);

        return $this->isSuccessWithData(['id' => $document->id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateDocumentRequest $request
     * @param User $user
     * @param Document $document
     * @return \Illuminate\Http\Response
     */
    public function updateDocument (UpdateDocumentRequest $request, User $user, Document $document)
    {
        parent::update($request->all(), $document);

        return $this->isSuccess();
    }
}
