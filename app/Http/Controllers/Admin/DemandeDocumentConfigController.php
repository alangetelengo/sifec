<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemandeDocumentConfig;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DemandeDocumentConfigController extends Controller
{
    public function edit()
    {
        $config = DemandeDocumentConfig::instance();

        return view('admin.demande-document-config.edit', compact('config'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'validite_document_mois' => 'required|integer|min:1|max:120',
        ]);

        try {
            $config = DemandeDocumentConfig::instance();
            $config->validite_document_mois = $validated['validite_document_mois'];
            $config->save();

            Log::channel('sifec')->info('Paramètre validité documents demande mis à jour', [
                'validite_mois' => $config->validite_document_mois,
            ]);

            return redirect()
                ->route('admin.demande-document-config.edit')
                ->with('success', 'Durée de validité des documents enregistrée.');
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur sauvegarde config demande document', ['error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'Erreur lors de l\'enregistrement.');
        }
    }
}
