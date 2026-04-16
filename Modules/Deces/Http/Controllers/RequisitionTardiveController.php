<?php

namespace Modules\Deces\Http\Controllers;

use App\Sifec\Sifec;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Deces\Entities\DeclarationDeces;
use Spipu\Html2Pdf\Html2Pdf;

class RequisitionTardiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $typeDeclaration = 'DECLARATION TARDIVE';
        $requisitions = DeclarationDeces::where(['type_declaration' => $typeDeclaration])->get();
        // $registre = Registre::where(["statut"=>1,"code_type_registre"=>"TPRG_0004"])->first();

        return view('deces::requisition_tardive.index', compact('requisitions'));
    }

    public function etat($id)
    {
        $requisition = DeclarationDeces::find($id);

        if ($requisition == null) {
            flash()->error('Requisition indisponible');

            return back();
        }

        view()->share('tester', [], 'Vincent');
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('deces::etats.requisitions.requisition_tardive', compact('requisition'))->render());

        return $html2pdf->output($requisition->code_declaration_deces.'.pdf');

    }

    public function generateRequisition(Request $request, $id)
    {
        $certificat = DeclarationDeces::find($id);

        if ($certificat == null) {
            flash()->error('Certificat indisponible');

            return back();
        }
        if ($certificat->top_requisition == 1) {
            flash()->error('Cette réquisition existe déjà');

            return back();
        }

        try {
            DB::beginTransaction();

            $certificat->top_requisition = 1;
            $certificat->numero_req = Sifec::genererCodeUniqueReferentiel($certificat, 'numero_req', 4, [], '');
            $certificat->save();

            DB::commit();
            flash()->success('La réquisition a été créee avec succès');

            return back();

        } catch (Exception $e) {
            DB::rollBack();
            flash()->error($e->getMessage());

            return back();

        }
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('deces::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('deces::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
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
