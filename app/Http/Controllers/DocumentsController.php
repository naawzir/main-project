<?php

namespace App\Http\Controllers;

use App\ConveyancingCase;
use App\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Document $document
     * @return \Illuminate\Http\Response
     */
    public function show($documentId)
    {
        $document = loadRecord(new Document, $documentId);
        $path = resource_path('documents/cases/' . $document->target_id . '/' . $document->file_name);
        $headers = array(
            'Content-Type: ' . $document->file_type,
        );

        return response()->download($path, $document->file_name, $headers);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Document $document
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document $document)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Document $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        //
    }

    public function getDocumentsForCase($caseslug)
    {
        $case = loadRecord(new ConveyancingCase, $caseslug);
        $documentModel = new Document;
        $document = $documentModel->getDocumentsForCase($case);
        return $document;
    }
}
