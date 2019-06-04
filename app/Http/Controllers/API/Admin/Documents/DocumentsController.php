<?php /** @noinspection ALL */

namespace App\Http\Controllers\API\Admin\Documents;

use App\Document;
use App\Http\Controllers\Controller;

class DocumentsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param array $attributes
     */
    public function store (array $attributes)
    {
        $document = new Document();
        return $document->add($attributes);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param array $attributes
     * @param Document $document
     */
    public function update (array $attributes, Document $document)
    {
        $document->change($attributes);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Document $document
     */
    public function destroy (Document $document)
    {
        $document->delete();
    }
}
