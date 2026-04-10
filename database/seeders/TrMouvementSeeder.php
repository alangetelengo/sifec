<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Modules\Referentiel\Entities\Mouvement;

class TrMouvementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Mouvement::truncate();

        $donnes = [
            [
                'code_mouvement' => 'MOUV_0001',
                'lib_mouvement' => 'Déclaration de naissance envoyée',
                'description' => 'La formation sanitaire envoie une déclaration de naissance au centre d\'état civil'
            ],
            [
                'code_mouvement' => 'MOUV_0002',
                'lib_mouvement' => 'Déclaration de décès envoyée',
                'description' => 'La formation sanitaire envoie une déclaration de décès au centre d\'état civil'
            ],
            [
                'code_mouvement' => 'MOUV_0003',
                'lib_mouvement' => 'Déclaration reçue et traitée par le centre d\'état civil',
                'description' => 'Le centre d\'état civil reçoit et traite le dossier envoyé par la formation sanitaire'
            ],
            [
                'code_mouvement' => 'MOUV_0004',
                'lib_mouvement' => 'Dossier renvoyé',
                'description' => 'Une institution peut renvoyer le dossier après traitement'
            ],
            [
                'code_mouvement' => 'MOUV_0005',
                'lib_mouvement' => 'Acte généré et envoyé à la signature',
                'description' => 'Après génération, l\'acte est envoyé à la signature de l\'officier d\'état civil'
            ],
            [
                'code_mouvement' => 'MOUV_0006',
                'lib_mouvement' => 'Certificat envoyé au tribunal',
                'description' => 'Le centre d\'état civil envoie le certificat de non inscription au tribunal'
            ],
            [
                'code_mouvement' => 'MOUV_0009',
                'lib_mouvement' => 'Réquisition envoyée au centre d\'état civil',
                'description' => 'Le tribunal envoie une réquisition au centre d\'état civil pour transcription'
            ],
            [
                'code_mouvement' => 'MOUV_0010',
                'lib_mouvement' => 'Jugement envoyé au centre d\'état civil',
                'description' => 'Le tribunal envoie un jugement au centre d\'état civil pour transcription'
            ],
            [
                'code_mouvement' => 'MOUV_0011',
                'lib_mouvement' => 'Document transmis au centre d\'état civil',
                'description' => 'Le tribunal transmet le document importé au centre d\'état civil'
            ],
            [
                'code_mouvement' => 'MOUV_0012',
                'lib_mouvement' => 'Document reçu par le centre d\'état civil',
                'description' => 'Le centre d\'état civil reçoit le document du tribunal'
            ],
            [
                'code_mouvement' => 'MOUV_0013',
                'lib_mouvement' => 'En attente de transcription de l\'acte',
                'description' => 'L\'acte est en attente de transcription au centre d\'état civil.'
            ],
            [
                'code_mouvement' => 'MOUV_0014',
                'lib_mouvement' => 'Acte produit et en attente d\'approbation de l\'officier d\'état civil',
                'description' => 'L\'acte est produit et attend l\'approbation de l\'officier d\'état civil.'
            ],
            [
                'code_mouvement' => 'MOUV_0015',
                'lib_mouvement' => 'Acte produit non rétiré',
                'description' => 'L\'acte a été produit mais n\'a pas encore été rétiré par le demandeur.'
            ],
            [
                'code_mouvement' => 'MOUV_0016',
                'lib_mouvement' => 'Acte rétiré',
                'description' => 'L\'acte a été rétiré par le demandeur.'
            ],
            [
                'code_mouvement' => 'MOUV_0017',
                'lib_mouvement' => 'Acte annulé',
                'description' => 'L\'acte a été annulé.'
            ],
            [
                'code_mouvement' => 'MOUV_0018',
                'lib_mouvement' => 'Dossier archivé',
                'description' => 'Le dossier est archivé pour la traçabilité finale'
            ],
            [
                'code_mouvement' => 'MOUV_0019',
                'lib_mouvement' => 'Dossier confirmé par le centre d\'état civil',
                'description' => 'Le centre d\'état civil confirme la conformité du dossier et le prépare pour la génération de l\'acte.'
            ],
            [
                'code_mouvement' => 'MOUV_0020',
                'lib_mouvement' => 'Dossier validé par l\'officier d\'état civil',
                'description' => 'l\'officier d\'état civil valide le dossier après vérification.'
            ],
            [
                'code_mouvement' => 'MOUV_0021',
                'lib_mouvement' => 'Dossier validé',
                'description' => 'La mairie valide le dossier pour finaliser la procédure.'
            ],
            [
                'code_mouvement' => 'MOUV_0022',
                'lib_mouvement' => 'Dossier validé par le tribunal',
                'description' => 'Le tribunal valide le dossier dans le cadre d\'une procédure judiciaire.'
            ],
            [
                'code_mouvement' => 'MOUV_0023',
                'lib_mouvement' => 'Acte rectifié',
                'description' => 'L\'acte a fait l\'objet d\'une rectification officielle.'
            ],
            [
                'code_mouvement' => 'MOUV_0024',
                'lib_mouvement' => 'Déclaration de naissance enregistrée',
                'description' => 'La formation sanitaire enregistre une déclaration de naissance'
            ],
            [
                'code_mouvement' => 'MOUV_0033',
                'lib_mouvement' => 'Certificat de naissance enregistré',
                'description' => 'La formation sanitaire ou l\'établissement enregistre un certificat de naissance'
            ],
            [
                'code_mouvement' => 'MOUV_0034',
                'lib_mouvement' => 'Certificat transformé en déclaration de naissance',
                'description' => 'Le centre d\'état civil valide le certificat et enregistre le dossier comme déclaration de naissance.'
            ],
            [
                'code_mouvement' => 'MOUV_0035',
                'lib_mouvement' => 'Certificat de naissance envoyé',
                'description' => 'La formation sanitaire envoie un certificat de naissance au centre d\'état civil'
            ],
            [
                'code_mouvement' => 'MOUV_0025',
                'lib_mouvement' => 'Événement naissance (code historique MOUV_0025)',
                'description' => 'Code mouvement conservé pour l’historique des dossiers ; non utilisé par les flux métier actuels.'
            ],
            [
                'code_mouvement' => 'MOUV_0026',
                'lib_mouvement' => 'Certificat de non inscription enregistré',
                'description' => 'Le certificat de non inscription est enregistré dans le système.'
            ],
            [
                'code_mouvement' => 'MOUV_0027',
                'lib_mouvement' => 'Certificat de destruction enregistré',
                'description' => 'Le certificat de destruction est enregistré dans le système.'
            ],
            [
                'code_mouvement' => 'MOUV_0028',
                'lib_mouvement' => 'Jugement d\'homologation enregistré',
                'description' => 'Le jugement d\'homologation est enregistré dans le système.'
            ],
            [
                'code_mouvement' => 'MOUV_0029',
                'lib_mouvement' => 'Jugement d\'adoption enregistré',
                'description' => 'Le jugement d\'adoption est enregistré dans le système.'
            ],
            [
                'code_mouvement' => 'MOUV_0030',
                'lib_mouvement' => 'Jugement supplétif enregistré',
                'description' => 'Le jugement supplétif est enregistré dans le système.'
            ],
            [
                'code_mouvement' => 'MOUV_0031',
                'lib_mouvement' => 'Fiche de transcription enregistrée',
                'description' => 'La fiche de transcription est enregistrée dans le système.'
            ],
            [
                'code_mouvement' => 'MOUV_0032',
                'lib_mouvement' => 'Déclaration de décès enregistrée',
                'description' => 'La formation sanitaire enregistre une déclaration de décès'
            ],
            [
                'code_mouvement' => 'MOUV_0109',
                'lib_mouvement' => 'Décès confirmé par le tribunal',
                'description' => 'Le tribunal confirme la déclaration de décès.'
            ],
            [
                'code_mouvement' => 'MOUV_0113',
                'lib_mouvement' => 'Acte de décès signé et non rétiré',
                'description' => 'L\'acte de décès a été signé mais n\'a pas encore été rétiré par le demandeur.'
            ],
            [
                'code_mouvement' => 'MOUV_0114',
                'lib_mouvement' => 'Acte de décès rétiré',
                'description' => 'L\'acte de décès a été rétiré par le demandeur.'
            ],
            [
                'code_mouvement' => 'MOUV_0212',
                'lib_mouvement' => 'Acte de mariage signé et rétiré',
                'description' => 'L\'acte de mariage a été signé et rétiré par le demandeur.'
            ],
            [
                'code_mouvement' => 'MOUV_1001',
                'lib_mouvement' => 'Réquisition importée par le tribunal',
                'description' => 'Le tribunal a importé une réquisition pour la déclaration concernée.'
            ],
            [
                'code_mouvement' => 'MOUV_1002',
                'lib_mouvement' => 'Jugement importé par le tribunal',
                'description' => 'Le tribunal a importé un jugement pour la déclaration concernée.'
            ],
            [
                'code_mouvement' => 'MOUV_1019',
                'lib_mouvement' => 'Dossier confirmé par le tribunal',
                'description' => 'Le tribunal confirme la conformité du dossier et le prépare pour la suite du traitement.'
            ],
            [
                'code_mouvement' => 'MOUV_2001',
                'lib_mouvement' => 'Fiche de rectification envoyée au tribunal',
                'description' => 'Une fiche de rectification a été transmise au tribunal pour traitement.'
            ],
            [
                'code_mouvement' => 'MOUV_2002',
                'lib_mouvement' => 'Fiche de rectification validée par le tribunal',
                'description' => 'Le tribunal a validé la fiche de rectification.'
            ],
            [
                'code_mouvement' => 'MOUV_2003',
                'lib_mouvement' => 'Fiche de rectification rejetée par le tribunal',
                'description' => 'Le tribunal a rejeté la fiche de rectification.'
            ],
            [
                'code_mouvement' => 'MOUV_2004',
                'lib_mouvement' => 'Fiche de rectification créée',
                'description' => 'Une nouvelle fiche de rectification a été créée dans le système.'
            ],
            [
                'code_mouvement' => 'MOUV_2005',
                'lib_mouvement' => 'Certificat de constatation de décès enregistré',
                'description' => 'Le certificat de constatation de décès est enregistré dans le système.'
            ],
            [
                'code_mouvement' => 'MOUV_2006',
                'lib_mouvement' => 'Certificat de constatation de décès envoyé',
                'description' => 'Le certificat de constatation de décès est envoyé au centre d\'état civil.'
            ],
            [
                'code_mouvement' => 'MOUV_2007',
                'lib_mouvement' => 'Formulaire enregistré',
                'description' => 'La demande d\'enregistrement de mariage effectuée.'
            ],
            [
                'code_mouvement' => 'MOUV_2008',
                 'lib_mouvement' => 'Formulaire type envoyé au tribunal',
                'description' => 'la demande de dispense est envoyée au tribunal.'
            ],
            [
                'code_mouvement' => 'MOUV_2009',
                'lib_mouvement' => 'Publication de ban de mariage effectuée',
                 'description' => 'La publication des bans de mariage a été effectuée conformément à la procédure légale.'
            ],
            [
                'code_mouvement' => 'MOUV_2010',
                'lib_mouvement' => 'Célébration de mariage effectuée',
                'description' => 'La cérémonie de mariage a été célébrée officiellement par l\'officier d\'état civil.'
            ],
            [
                'code_mouvement' => 'MOUV_2011',
                'lib_mouvement' => 'Certificat de transcription enregistré',
                'description' => 'Le certificat de transcription est enregistré dans le système.'
            ],
        ];

        foreach ($donnes as $d){
            Mouvement::create([
                "code_mouvement" => $d['code_mouvement'],
                "lib_mouvement" => $d['lib_mouvement'],
                "description" => $d['description'],
                "created_at" => now(),
                "updated_at" => now(),
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
