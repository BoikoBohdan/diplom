<?php /** @noinspection ALL */

namespace App\Http\Controllers\API\Admin\Documents;

use App\{Document, Vehicle};
use App\Http\Requests\API\Documents\{CreateDocumentRequest, UpdateDocumentRequest};

class VehicleDocumentsController extends DocumentsController
{
    protected $type;

    public function __construct ()
    {
        $this->type = Vehicle::class;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateDocumentRequest $request
     * @param Vehicle $vehicle
     * @return void
     */
    public function setDocument (CreateDocumentRequest $request, Vehicle $vehicle)
    {
        $attributes = $request->all();
        $attributes['documentable_id'] = $vehicle->id;
        $attributes['documentable_type'] = $this->type;
        $document = parent::store($attributes);

        return $this->isSuccessWithData(['id' => $document->id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return void
     */
    public function updateDocument (UpdateDocumentRequest $request, Vehicle $vehicle, Document $document)
    {
        parent::update($request->all(), $document);

        return $this->isSuccess();
    }
}
