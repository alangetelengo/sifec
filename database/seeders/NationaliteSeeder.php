<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\Nationalite;

class NationaliteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0");
        DB::statement("truncate tr_nationalite");

        // Nationalités organisées par régions géographiques
        $donnes = [
            // Afrique Centrale
            "CONGOLAIS(E)",
            "GABONAIS(E)",
            "CAMEROUNAIS(E)",
            "CENTRAFRICAIN(E)",
            "TCHADIEN(NE)",
            "EQUATO-GUINEEN(NE)",
            "SAO-TOME-ET-PRINCIPIEN(NE)",

            // Afrique de l'Ouest
            "SENEGALAIS(E)",
            "IVOIRIEN(NE)",
            "MALIEN(NE)",
            "BURKINABE",
            "NIGERIEN(NE)",
            "BENINOIS(E)",
            "TOGOLAIS(E)",
            "GUINEEN(NE)",
            "SIERRA-LEONAIS(E)",
            "LIBERIEN(NE)",
            "GHANEEN(NE)",
            "GAMBIEN(NE)",
            "GUINEEN(NE) - BISSAU",
            "CAP-VERDIEN(NE)",
            "MAURITANIEN(NE)",
            "NIGERIAN(E)",

            // Afrique de l'Est
            "ETHIOPIEN(NE)",
            "KENYAN(E)",
            "TANZANIEN(NE)",
            "OUGANDAIS(E)",
            "RWANDAIS(E)",
            "BURUNDAIS(E)",
            "SOUDANAIS(E)",
            "SOUDANAIS(E) - DU SUD",
            "DJIBOUTIEN(NE)",
            "ERYTHREEN(NE)",
            "SOMALIEN(NE)",

            // Afrique du Nord
            "ALGERIEN(NE)",
            "MAROCAIN(E)",
            "TUNISIEN(NE)",
            "LYBIEN(NE)",
            "EGYPTIEN(NE)",

            // Afrique Australe
            "SUD-AFRICAIN(E)",
            "ANGOLAIS(E)",
            "MOZAMBICAIN(E)",
            "ZAMBIEN(NE)",
            "ZIMBABWEEN(NE)",
            "BOTSWANAIS(E)",
            "NAMIBIEN(NE)",
            "MALAWIEN(NE)",
            "LESOTHAN(E)",
            "SWAZILAND(IS)(E)",
            "MALGACHE",

            // Afrique (Autres)
            "CONGOLAIS(E) - RDC",
            "COMORIEN(NE)",
            "MAURICIEN(NE)",
            "SEYCHELLOIS(E)",

            // Europe
            "FRANCAIS(E)",
            "BELGE",
            "SUISSE",
            "ALLEMAND(E)",
            "ESPAGNOL(E)",
            "ITALIEN(NE)",
            "PORTUGAIS(E)",
            "BRITANNIQUE",
            "IRLANDAIS(E)",
            "NEERLANDAIS(E)",
            "LUXEMBOURGEOIS(E)",
            "AUTRICHIEN(NE)",
            "POLONAIS(E)",
            "ROUMAIN(E)",
            "GREC(QUE)",
            "RUSSE",
            "UKRAINIEN(NE)",
            "SERBE",
            "CROATE",
            "TURC(QUE)",

            // Amériques
            "AMERICAIN(E)",
            "CANADIEN(NE)",
            "BRESILIEN(NE)",
            "ARGENTIN(E)",
            "MEXICAIN(E)",
            "COLOMBIEN(NE)",
            "CHILIEN(NE)",
            "PERUVIEN(NE)",
            "VENEZUELIEN(NE)",
            "CUBAIN(E)",
            "HAITIEN(NE)",

            // Asie
            "CHINOIS(E)",
            "INDIEN(NE)",
            "JAPONAIS(E)",
            "COREN(NE) - DU SUD",
            "VIETNAMIEN(NE)",
            "THAILANDAIS(E)",
            "INDOENESIEN(NE)",
            "MALAYSIEN(NE)",
            "PHILIPPIN(E)",
            "PAKISTANAIS(E)",
            "BANGLADAIS(E)",
            "SRI-LANKAIS(E)",
            "IRANIEN(NE)",
            "IRAKIEN(NE)",
            "SAOUDIEN(NE)",
            "EMIRATI(E) - ARABES UNIS",
            "LIBANAIS(E)",
            "SYRIEN(NE)",
            "JORDANIEN(NE)",
            "ISRAELIEN(NE)",
            "PALESTINIEN(NE)",

            // Océanie
            "AUSTRALIEN(NE)",
            "NEO-ZELANDAIS(E)",

            // Autres
            "APATRIDE",
            "NON DECLARE"
        ];

        $data = [];
        for($i = 0; $i < count($donnes); $i++){
            $intCode = $i + 1;
            $count = $intCode;
            $strCode = "NAT_".str_pad($count,4,"0",STR_PAD_LEFT);
            $data[] = ["code_nationalite"=>$strCode,'lib_nationalite'=>$donnes[$i]];
        }


        // Supprimer les doublons dans le tableau avant insertion
        $uniqueData = [];
        $seenLibelles = [];
        foreach($data as $d){
            $libelle = $d['lib_nationalite'];
            if (!in_array($libelle, $seenLibelles)) {
                $seenLibelles[] = $libelle;
                $uniqueData[] = $d;
            }
        }

        foreach($uniqueData as $d){
            Nationalite::create($d);
        }
    }
}
