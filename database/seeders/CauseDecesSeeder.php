<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\CauseDeces;

class CauseDecesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('set foreign_key_checks = 0');
        DB::statement('truncate tr_cause_deces');

        $donnes = [
            "Paludisme",
            "Paludisme grave forme anémique",        
            "Anémie",        
            "Anémie sévère",        
            "Diarrhée",        
            "Diarrhée vomissement",
            "Diarrhée Gastro-entérite aigue",
            "Sepsis", 
            "Septicemie",       
            "Sepsis sévère",        
            "Hypertension artérielle",        
            "Accident vasculaire cérébral (A.V.C)",
            "Arrêt cardiaque",        
            "Arrêt cardio respiratoire",        
            "Etat de choc septique",
            "Hyperglycémie",        
            "Hypoglycémie",        
            "Diabète",        
            "Cellulite",        
            "Carie dentaire",        
            "Corona virus",        
            "Blessure",        
            "Traumatisme",
            "Traumatisme crânien",
            "Traumatisme pied",
            "Traumatisme jambe",
            "Œdème aigue des poumons (O.A.P)",
            "Infection respiratoire aigüe (L.R.A)",
            "Otite aigue ou chronique",
            "Méningite",        
            "Toxoplamose",        
            "Sida",        
            "Tuberculose",        
            "Insuffisance rénale",
            "Insufisance cardiaque",        
            "Cancer du poumon",
            "Cancer du foie",
            "Cancer de l'estomac",
            "Cancer du sein",
            "Cancer de l'utérus",
            "Malnutrition",
            "Asphyxie périnatale",
            "Malformation congénitale",        
            "Fièvre jaune",        
            "Pneumopathie",        
            "Pneumonie",        
            "Infection foeto-maternelle",
            "Hemorragie de Ja délivrance", 
            "Cancer primitif du foie",
            "Cancer de l’estomac",        
            "Cancer de la prostate",        
            "Eclampsie",        
            "Dystocie",        
            "Hematome retro placentaire",        
            "Coagulation intraveineuse disséminée (CIVD)",
            "Fausse couche provoquée",        
            "Hemorragie digestive",        
            "Embolie amniotique",        
            "Rupture utérine",        
            "Souffrance cérébrale",        
            "Hemorragie post-partum",        
            "Placenta praevia",        
            "Cyrrhose du foie"        
        ];
        
        $data = [];
        for($i = 0; $i < count($donnes); $i++){
            $intCode = $i + 1;
            $count = $intCode;
            $strCode = "CD_".str_pad($count,4,"0",STR_PAD_LEFT);
            $data[] = ["code_cause_deces"=>$strCode,'lib_cause_deces'=>$donnes[$i]];
        }

        foreach ($data as $d){
            CauseDeces::create($d);
        }
    }
}
