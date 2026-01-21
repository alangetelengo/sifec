<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\Profession;

class ProfessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE tr_profession');

        // Professions adaptées au Congo-Brazzaville et à la zone géographique
        $donnes = [
            // Secteur Pétrolier et Gazier
            "Ingénieur pétrolier",
            "Technicien de forage",
            "Logisticien industriel",
            "Opérateur de production pétrolière",
            
            // Bâtiment et Travaux Publics (BTP)
            "Architecte",
            "Ingénieur en génie civil",
            "Entrepreneur en construction",
            "Maçon",
            "Menuisier",
            "Plombier",
            "Électricien",
            "Peintre en bâtiment",
            
            // Santé et Médical
            "Médecin",
            "Médecin spécialiste",
            "Infirmier(ère)",
            "Pharmacien(ne)",
            "Sage-femme",
            "Technicien de laboratoire médical",
            "Aide-soignant(e)",
            
            // Technologies de l'Information et Communication
            "Informaticien(ne)",
            "Développeur logiciel",
            "Technicien en maintenance informatique",
            "Webmaster",
            
            // Commerce et Services
            "Commerçant(e)",
            "Commerçant ambulant",
            "Vendeur(euse)",
            "Négociant(e)",
            "Importateur/Exportateur",
            
            // Justice et Droit
            "Juge",
            "Avocat(e)",
            "Greffier(ère)",
            "Huissier de justice",
            
            // Éducation
            "Enseignant(e)",
            "Professeur",
            "Directeur d'école",
            "Formateur(trice)",
            
            // Administration Publique
            "Fonctionnaire",
            "Administrateur civil",
            "Agent administratif",
            "Secrétaire",
            
            // Transport et Logistique
            "Chauffeur",
            "Mécanicien(ne)",
            "Transporteur",
            "Agent de fret",
            
            // Finance et Comptabilité
            "Comptable",
            "Banquier(ère)",
            "Agent d'assurance",
            "Caissier(ère)",
            
            // Médias et Communication
            "Journaliste",
            "Animateur radio/télévision",
            "Photographe",
            "Cameraman",
            
            // Agriculture et Agroalimentaire
            "Agriculteur(trice)",
            "Éleveur(euse)",
            "Pêcheur(euse)",
            "Transformateur de produits agricoles",
            
            // Artisanat et Culture
            "Artisan",
            "Sculpteur(euse)",
            "Musicien(ne)",
            "Artiste plasticien(ne)",
            "Tailleur(euse)",
            "Cordonnier(ère)",
            
            // Hôtellerie et Restauration
            "Chef cuisinier",
            "Serveur(euse)",
            "Gestionnaire d'hôtel",
            "Guide touristique",
            
            // Sécurité et Défense
            "Policier(ère)",
            "Militaire",
            "Agent de sécurité",
            "Garde du corps",
            
            // Services Sociaux
            "Travailleur social",
            "Psychologue",
            "Éducateur spécialisé",
            
            // Autres Services
            "Coiffeur(euse)",
            "Esthéticien(ne)",
            "Couturier(ère)",
            "Réparateur de téléphones",
            "Réparateur d'électroménager",
            
            // Professions libérales
            "Consultant(e)",
            "Expert-comptable",
            
            // Sport et Loisirs
            "Entraîneur sportif",
            "Animateur socioculturel",
            
            // Autres
            "Étudiant(e)",
            "Sans emploi",
            "Retraité(e)",
            "Non déclaré"
        ];

        $data = [];
        for($i = 0; $i < count($donnes); $i++){
            $intCode = $i + 1;
            $count = $intCode;
            $strCode = "PROF_".str_pad($count,4,"0",STR_PAD_LEFT);
            $data[] = ["code_profession"=>$strCode,'lib_profession'=>$donnes[$i]];
        }

        foreach ($data as $d){
            Profession::create($d);
        }
    }
}
