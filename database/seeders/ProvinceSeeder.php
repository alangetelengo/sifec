<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $provinces = [
            ["code"=>"CDBU", "libelle"=> "Bas-Uele"],
            ["code"=>"CDKN", "libelle"=> "Kinshasa"],
            ["code"=>"CDKS" , "libelle"=> "Kaissaï"],
            ["code"=>"CDKC" , "libelle"=> "Kaissaï-Central"],
            ["code"=>"CDKE" , "libelle"=> "Kaissaï-Oriental"],
            ["code"=>"CDBC" , "libelle"=> "Kongo-Central"],
            ["code"=>"CDHK" , "libelle"=> "Haut-Katanga"]
        ];


        $donnes = ["Sous préfet","Officier d'état civil","Officier d'état civil délégué","Agent mairie","Agent pompes funèbres","Agent formation sanitaire","Agent centre d'hygiène","Agent tribunal","Président du tribunal","Procureur général","Super administrateur","Directeur pompes funèbres", "DGAT","Agent mairie centrale","DEC","Chef de service","Agent bureau d'enregistrement de décès","Procureur de la République","Agent ambassade"];

        $data = [];
        for($i = 0; $i < count($donnes); $i++){
            $intCode = $i + 1;
            $count = $intCode;
            $strCode = "FONC_".str_pad($count,4,"0",STR_PAD_LEFT);
            $data[] = ["code_fonction"=>$strCode,'lib_fonction'=>$donnes[$i]];
        }

        foreach ($data as $d){
            Fonction::create($d);
        }
    }
}
