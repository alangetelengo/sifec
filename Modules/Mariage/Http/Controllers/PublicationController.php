<?php

namespace Modules\Mariage\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Modules\Mariage\Entities\DeclarationMariage;
use Spipu\Html2Pdf\Html2Pdf;

class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $declarations = DeclarationMariage::all();

        return view('mariage::publication.index', compact('declarations'));
    }

    public function show($id)
    {
        $dm = DeclarationMariage::find($id);

        if ($dm == null) {
            flash()->error("Impossible d'accéder à cette page");

            return back();
        }
        view()->share('tester', [], 'Alange');
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('mariage::etats.Publication', compact('dm'))->render());

        return $html2pdf->output('PublicationMariage.pdf');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
