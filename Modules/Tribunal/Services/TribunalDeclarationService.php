<?php
namespace Modules\Tribunal\Services;

use Exception;
use App\Sifec\Sifec;
use App\Models\Jugement;
use App\Models\Requisition;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Tribunal\Services\MouvementService;
use Modules\Notification\Services\NotificationService;
use Modules\Notification\Notifications\DeclarationEnvoyeeCentreNotification;
use Modules\Notification\Notifications\DocumentImporteTribunalNotification;


class TribunalDeclarationService
{

    /**
     * Traite l'importation d'un document tribunal (réquisition ou jugement) pour une déclaration.
     */
    public function traiterImport($declaration, $request, $module, $user)
    {
         // Créez le répertoire de téléchargement s'il n'existe pas
         $uploadPath = public_path('app/tribunal');
         if (!file_exists($uploadPath)) {
             mkdir($uploadPath, 0755, true);
         }

         $hasPiece = false;
         $documentPath = null;

         // document venant de la boite noire du tribunal
        if ($request->hasFile('document_importer') && $request->file('document_importer')->isValid()) {
            $imageName = time() . '_'.$module.'.' . $request->file('document_importer')->extension();
            $request->file('document_importer')->move($uploadPath, $imageName);
            $documentPath = 'app/tribunal/' . $imageName;
            $hasPiece = true;
        } else {
            throw new Exception("Aucun fichier importé reçu ou fichier invalide.");
        }

        if ($request->type_document === 'requisition') {
            $requisition = new Requisition();
            $requisition->code_requisition = Sifec::genererCodeUniqueReferentiel($requisition, "code_requisition", 8, "REQ_");
            $requisition->num_requisition = $request->num_document;
            $requisition->date_requisition = $request->date_document;
            $requisition->document_requisition = $documentPath;
            $requisition->cui = $request->cui;
            $requisition->code_type_requisition = $request->code_type_requisition;
            $requisition->code_institution = $declaration->code_institution ?? null; //cette relation est à modifier avec le code de la declaration
            $requisition->statut_document = "En cours de traitement";
            $requisition->save();

            $declaration->code_requisition = $requisition->code_requisition;
            $declaration->save();
        } elseif ($request->type_document === 'jugement') {
            $jugement = new Jugement();
            $jugement->code_jugement = Sifec::genererCodeUniqueReferentiel($jugement, "code_jugement", 8, "JUG_");
            $jugement->num_jugement = $request->num_document;
            $jugement->date_jugement = $request->date_document;
            $jugement->document_jugement = $documentPath;
            $jugement->cui = $request->cui;
            $jugement->code_type_jugement = $request->code_type_jugement;
            $jugement->code_institution = $declaration->code_institution ?? null; //cette relation est à modifier avec le code de la declaration
            $jugement->statut_document = "En cours de traitement";
            $jugement->save();

            $declaration->code_jugement = $jugement->code_jugement;
            $declaration->save();
        }

    }

    /**
     * Détecte le module à partir de l'objet déclaration
     */
    private function detectModule($declaration)
    {
        if (isset($declaration->code_declaration_naissance)) return 'naissance';
        if (isset($declaration->code_declaration_deces)) return 'deces';
        if (isset($declaration->code_declaration_mariage)) return 'mariage';

        return null;
    }

    /**
     * Confirme un document reçu au tribunal
     */
    public function confirmerDocument($declaration, $user, $observation = null)
    {
        DB::beginTransaction();
        try {
            $module = $this->detectModule($declaration);
            if (!$module) {
                throw new \Exception("Module inconnu pour l'enregistrement du mouvement.");
            }
            $trmouvement = DB::table('tr_mouvement')->where('code_mouvement', 'MOUV_1019')->first();
            if (!$trmouvement) {
                throw new \Exception('Mouvement référentiel de confirmation introuvable.');
            }
            $result = app(MouvementService::class)->enregistrerMouvementDossier(
                $declaration, $module, $trmouvement, $user, $observation
            );
            $declaration->tribunal_approuver = "OUI";
            $declaration->save();
            NotificationService::notifierAgentsInstitution(
                $declaration->institution,
                new DeclarationEnvoyeeCentreNotification($declaration, $declaration->institution, 'confirmée', $observation)
            );
            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur TribunalDeclarationService::confirmerDocument : ' . $e->getMessage());
            return [false, $e->getMessage()];
        }
    }

    /**
     * Renvoie un certificat/document au centre d'état civil
     */
    public function renvoyerCertificat($declaration, $user, $observation = null)
    {
        DB::beginTransaction();
        try {
            $module = $this->detectModule($declaration);
            if (!$module) {
                throw new \Exception("Module inconnu pour l'enregistrement du mouvement.");
            }
            $trmouvement = DB::table('tr_mouvement')->where('code_mouvement', 'MOUV_0004')->first();
            if (!$trmouvement) {
                throw new \Exception('Mouvement référentiel de renvoi introuvable.');
            }
            $result = app(MouvementService::class)->enregistrerMouvementDossier(
                $declaration, $module, $trmouvement, $user, $observation
            );
            NotificationService::notifierAgentsInstitution(
                $declaration->institution,
                new DeclarationEnvoyeeCentreNotification($declaration, $declaration->institution, 'renvoyée', $observation)
            );

            // Mettre à jour le champ destinataire sur la déclaration (pour le renvoi)
            $declaration->code_institution_destinataire = $declaration->code_institution;

            if($declaration->type_declaration == "DISPENSE") {
                $declaration->epoux_approuver = "NON";
                    $declaration->epouse_approuver = "NON";
                    $declaration->cec_approuver = "NON";
                }
            $declaration->save();

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur TribunalDeclarationService::renvoyerCertificat : ' . $e->getMessage());
            return [false, $e->getMessage()];
        }
    }

    /**
     * Renvoie un dossier au centre d'état civil (cas général)
     */
    public function renvoyerAuCentre($declaration, $user, $observation = null)
    {
        DB::beginTransaction();
        try {
            $module = $this->detectModule($declaration);
            if (!$module) {
                throw new \Exception("Module inconnu pour l'enregistrement du mouvement.");
            }
            $trmouvement = DB::table('tr_mouvement')->where('code_mouvement', 'MOUV_0004')->first();
            if (!$trmouvement) {
                throw new \Exception('Mouvement référentiel de renvoi introuvable.');
            }
            $result = app(MouvementService::class)->enregistrerMouvementDossier(
                $declaration, $module, $trmouvement, $user, $observation
            );
            NotificationService::notifierAgentsInstitution(
                $declaration->institution,
                new DeclarationEnvoyeeCentreNotification($declaration, $declaration->institution, 'renvoyée', $observation)
            );
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur TribunalDeclarationService::renvoyerAuCentre : ' . $e->getMessage());
            return [false, $e->getMessage()];
        }
    }

    /**
     * Importe un document (réquisition ou jugement), enregistre le mouvement et notifie les agents
     */
    public function importerDocument($declaration, $request, $module, $user)
    {
        $documentPath = null;
        try {
            // Déterminer le code de déclaration selon le module
            $codeDeclaration = '';
            if ($module === 'naissance') {
                $codeDeclaration = $declaration->code_declaration_naissance;
            } elseif ($module === 'deces') {
                $codeDeclaration = $declaration->code_declaration_deces;
            } elseif ($module === 'mariage') {
                $codeDeclaration = $declaration->code_declaration_mariage;
            } else {
                throw new Exception('Module inconnu : ' . $module);
            }

            // 1. Traitement de l'import du document (upload)
            $typeDocument = $request->type_document; // 'requisition' ou 'jugement'
            if ($typeDocument === 'requisition') {
                // Récupérer ou créer la réquisition
                $document = $declaration->requisition;
                if (!$document) {
                    // Créer automatiquement la réquisition si elle n'existe pas
                    $document = new \App\Models\Requisition();
                    $document->code_requisition = \App\Sifec\Sifec::genererCodeUniqueReferentiel($document, "code_requisition", 4, "REQ_");
                    $document->code_declaration = $codeDeclaration;
                    $document->cui = $user->affectationActive()->cui;
                    $document->code_type_requisition = $request->code_type_requisition;
                    $document->code_institution = $user->affectationActive()->institution->code_institution;
                    $document->statut = "importée";
                    $document->save();
                }
                $document->num_requisition = $request->num_document ?? null;
                $document->date_requisition = $request->date_document;
            } else {
                // Récupérer ou créer le jugement
                $document = $declaration->jugement;
                if (!$document) {
                    // Créer automatiquement le jugement s'il n'existe pas
                    $document = new \App\Models\Jugement();
                    $document->code_jugement = \App\Sifec\Sifec::genererCodeUniqueReferentiel($document, "code_jugement", 4, "JUG_");
                    $document->code_declaration = $codeDeclaration;
                    $document->cui = $user->affectationActive()->cui;
                    $document->code_type_jugement = $request->code_type_jugement;
                    $document->code_institution = $user->affectationActive()->institution->code_institution;
                    $document->statut = "importée";
                    $document->save();
                }
                $document->num_jugement = $request->num_document ?? null;
                $document->date_jugement = $request->date_document;
                $document->numero_ancien_acte = $request->numero_ancien_acte ?? null;
            }
            // Upload du fichier
            if ($request->hasFile('document_importer') && $request->file('document_importer')->isValid()) {
                $uploadPath = public_path('app/tribunal');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $imageName = time() . '_' . $typeDocument . '.' . $request->file('document_importer')->extension();
                $request->file('document_importer')->move($uploadPath, $imageName);
                $documentPath = 'app/tribunal/' . $imageName;
                if ($typeDocument === 'requisition') {
                    $document->document_requisition = $documentPath;
                } else {
                    $document->document_jugement = $documentPath;
                }
            }
            $document->save();

            // 2. Enregistrement du mouvement tribunal (sans blocage sur cec_approuver)
            $codeMouvement = $typeDocument === 'requisition' ? 'MOUV_1001' : 'MOUV_1002';
            $trmouvement = DB::table('tr_mouvement')->where('code_mouvement', $codeMouvement)->first();
            if (!$trmouvement) return [false, 'Mouvement référentiel introuvable.', null];
            $result = app(MouvementService::class)->enregistrerMouvementDossier(
                $declaration, $module, $trmouvement, $user, $request->observation ?? null
            );
            if (!$result[0]) return [false, $result[1], null];

            // 3. Notification des agents du centre d'état civil
            if ($declaration->institution && $declaration->institution->institutionsUsers) {
                foreach ($declaration->institution->institutionsUsers as $agent) {
                    $agent->notify(new \Modules\Notification\Notifications\DocumentImporteTribunalNotification($declaration, $typeDocument));
                }
            }
            return [true, 'Document importé, mouvement tracé et notification envoyée.', $documentPath];
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur importerDocument TribunalDeclarationService : ' . $e->getMessage());
            return [false, $e->getMessage(), null];
        }
    }

    /**
     * Envoie officiellement le dossier traité au centre d'état civil (après import de document)
     * Cette méthode contourne la vérification cec_approuver pour permettre l'envoi après traitement tribunal
     */
    public function envoyerOfficiel($declaration, $user, $typeDocument)
    {


        DB::beginTransaction();
        try {
            $module = $this->detectModule($declaration);
            if (!$module) {
                throw new Exception("Module inconnu pour l'enregistrement du mouvement.");
            }

            // Vérifier que le dernier mouvement est bien un import de document
            $dernierMouvement = $declaration->mouvements()->latest('created_at')->first();
            if (!$dernierMouvement || !in_array($dernierMouvement->code_mouvement, ['MOUV_1001', 'MOUV_1002'])) {
                throw new Exception('Impossible d\'envoyer le dossier : le document de réponse n\'a pas encore été importé ou le dossier n\'est pas prêt à être transmis.');
            }

            // Récupérer le mouvement référentiel pour l'envoi officiel
            $trmouvement = DB::table('tr_mouvement')->where('code_mouvement', 'MOUV_0011')->first();
            if (!$trmouvement) {
                throw new Exception('Mouvement référentiel d\'envoi officiel introuvable.');
            }


            $result = app(MouvementService::class)->enregistrerMouvementDossier(
                $declaration, $module, $trmouvement, $user, null
            );

            if (!$result[0]) {
                throw new Exception($result[1]);
            }

            // Mettre à jour la déclaration pour indiquer qu'elle a été envoyée
            $declaration->tribunal_approuver = "OUI";
            $declaration->tribunal_approuve_par = $user->affectationActive()->cui;
            $declaration->tribunal_approuve_le = now();
            $declaration->save();

            // Mettre à jour le statut du document importé (réquisition ou jugement)
            if ($typeDocument && strpos($typeDocument, 'réquisition') !== false && $declaration->requisition) {
                $declaration->requisition->statut = "envoyée";
                $declaration->requisition->save();
            } elseif ($typeDocument && strpos($typeDocument, 'jugement') !== false && $declaration->jugement) {
                $declaration->jugement->statut = "envoyée";
                $declaration->jugement->save();
            } else {
                // Fallback : mettre à jour les deux si le type n'est pas clair
                if ($declaration->requisition) {
                    $declaration->requisition->statut = "envoyée";
                    $declaration->requisition->save();
                }
                if ($declaration->jugement) {
                    $declaration->jugement->statut = "envoyée";
                    $declaration->jugement->save();
                }
            }

            // Notification transmission officielle
            NotificationService::notifierAgentsInstitution(
                $declaration->institution,
                new DeclarationEnvoyeeCentreNotification($declaration, $declaration->institution, 'envoyée', null)
            );

            DB::commit();
            return [true, 'Dossier transmis au centre d\'état civil avec succès'];
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur TribunalDeclarationService::envoyerOfficiel : ' . $e->getMessage());
            return [false, $e->getMessage()];
        }
    }

}
