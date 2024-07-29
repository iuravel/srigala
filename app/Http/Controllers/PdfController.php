<?php

namespace App\Http\Controllers;

use App\Models\BentukSurat;
use App\Models\GajiBerkalaAsn;
use App\Models\GajiBerkalaMiliter;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function previewKgbMil($id)
    {
        $record = GajiBerkalaMiliter::findOrFail($id);
        $surat = BentukSurat::findOrFail(1);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.kgb-militer-pdf', compact('record', 'surat'));
        return $pdf->stream('preview-kgb-'.strtolower(strval($record->nama)).'-'.strval($record->nrp).'.pdf');
    }
    public function previewKgbAsn($id)
    {
        $record = GajiBerkalaAsn::findOrFail($id);
        $surat = BentukSurat::findOrFail(1);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.kgb-asn-pdf', compact('record', 'surat'));
        return $pdf->stream('preview-kgb-'.strtolower(strval($record->nama)).'-'.strval($record->nip).'.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
