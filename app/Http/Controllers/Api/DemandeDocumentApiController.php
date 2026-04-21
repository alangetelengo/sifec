<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Mobile\Entities\DemandeDocument;

class DemandeDocumentApiController extends Controller
{
    /**
     * Consulter le statut d'une demande
     *
     * GET /api/v1/demande/{code}/statut
     */
    public function consulterStatut($code)
    {
        $demande = DemandeDocument::where('code_demande_document', $code)->first();

        if (! $demande) {
            return response()->json([
                'code' => '404',
                'message' => 'Demande non trouvée',
            ], 404);
        }

        return response()->json([
            'code' => '200',
            'demande' => [
                'code_demande' => $demande->code_demande_document,
                'statut' => $demande->statut,
                'type_document' => $demande->getLibelleTypeDocument(),
                'type_acte' => $demande->getLibelleTypeActe(),
                'numero_acte' => $demande->numero_acte,
                'prix' => $demande->prix,
                'date_demande' => $demande->date_demande?->format('d/m/Y H:i'),
                'date_traitement' => $demande->date_traitement?->format('d/m/Y H:i'),
                'date_livraison' => $demande->date_livraison?->format('d/m/Y H:i'),
                'origine' => $demande->origine_demande,
                'est_signee' => $demande->estSignee(),
            ],
        ]);
    }
}
