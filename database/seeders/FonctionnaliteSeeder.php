<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Authentification\Entities\Fonctionnalite;

class FonctionnaliteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE tr_fonctionnalite');

        $data = [
            ["code_fonctionnalite"=>"FNC_0001",'lib_fonctionnalite'=> "Gestion des menus","lib_technique"=>"module.menus","description_fonctionnalite"=>"Cette fonction permet à l'utilisateur connecté de pouvoir administrer les menus si il en a le droit","code_module"=>"MOD_0001","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>NULL],
            ["code_fonctionnalite"=>"FNC_0002",'lib_fonctionnalite'=> "Gestion des actes de naissance","lib_technique"=>"module.acteNaissance","description_fonctionnalite"=>"Permet à un utilisateur d'accéder aux données de naissance dans le système","code_module"=>"MOD_0002","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>NULL],
            ["code_fonctionnalite"=>"FNC_0003",'lib_fonctionnalite'=> "Gestion des actes de décès","lib_technique"=>"module.acteDeces","description_fonctionnalite"=>"Permet à un utilisateur d'accéder aux données de décès dans le système","code_module"=>"MOD_0003","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>NULL],
            ["code_fonctionnalite"=>"FNC_0004",'lib_fonctionnalite'=> "Gestion des utilisateurs","lib_technique"=>"module.users","description_fonctionnalite"=>"Permet à un utilisateur d'accéder aux données de utilisateurs dans le système","code_module"=>"MOD_0001","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>NULL],
            ["code_fonctionnalite"=>"FNC_0005",'lib_fonctionnalite'=> "Voir menu référentiel","lib_technique"=>"module.menus.referentiel","description_fonctionnalite"=>"Voir le menu référentiel","code_module"=>"MOD_0001","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0001"],
            ["code_fonctionnalite"=>"FNC_0006",'lib_fonctionnalite'=> "Voir menu formation sanitaire","lib_technique"=>"module.menus.formationSanitaire","description_fonctionnalite"=>"Voir le menu formation sanitaire","code_module"=>"MOD_0001","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0001"],
            ["code_fonctionnalite"=>"FNC_0007",'lib_fonctionnalite'=> "Voir menu centre d'hygiène","lib_technique"=>"module.menus.centreHygiene","description_fonctionnalite"=>"Voir le menu centre d'hygiène","code_module"=>"MOD_0001","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0001"],
            ["code_fonctionnalite"=>"FNC_0008",'lib_fonctionnalite'=> "Voir menu pompes funèbres","lib_technique"=>"module.menus.pompesFunebres","description_fonctionnalite"=>"Voir le menu pompes funèbres","code_module"=>"MOD_0001","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0001"],
            ["code_fonctionnalite"=>"FNC_0009",'lib_fonctionnalite'=> "Voir menu centre d'état civil","lib_technique"=>"module.menus.cec","description_fonctionnalite"=>"Voir le menu centre d'état civil","code_module"=>"MOD_0001","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0001"],
            ["code_fonctionnalite"=>"FNC_0010",'lib_fonctionnalite'=> "Voir menu tribunal","lib_technique"=>"module.menus.tribunal","description_fonctionnalite"=>"Voir le menu tribunal","code_module"=>"MOD_0001","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0001"],
            ["code_fonctionnalite"=>"FNC_0011",'lib_fonctionnalite'=> "Voir menu administration","lib_technique"=>"module.menus.administration","description_fonctionnalite"=>"Voir le menu administration","code_module"=>"MOD_0001","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0001"],
            ["code_fonctionnalite"=>"FNC_0012",'lib_fonctionnalite'=> "Créer une déclaration de naissance","lib_technique"=>"module.ActeNaissance.declarationNaissance.create","description_fonctionnalite"=>"Cette fonction permet à l'utilisateur connecté de pouvoir créer une déclaration de naissance si il en a le droit","code_module"=>"MOD_0002","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0002"],
            ["code_fonctionnalite"=>"FNC_0013",'lib_fonctionnalite'=> "Modifier une déclaration de naissance","lib_technique"=>"module.ActeNaissance.declarationNaissance.edit","description_fonctionnalite"=>"Cette fonction permet à l'utilisateur connecté de pouvoir modifier une déclaration de naissance si il en a le droit","code_module"=>"MOD_0002","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0002"],
            ["code_fonctionnalite"=>"FNC_0014",'lib_fonctionnalite'=> "générer un acte de naissance","lib_technique"=>"module.acteNaissance.generate","description_fonctionnalite"=>"Cette fonction permet à l'utilisateur connecté de pouvoir générer un acte de naissance si il en a le droit","code_module"=>"MOD_0002","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0002"],
            ["code_fonctionnalite"=>"FNC_0015",'lib_fonctionnalite'=> "Signer  un acte de naissance","lib_technique"=>"module.acteNaissance.signature","description_fonctionnalite"=>"Cette fonction permet à l'utilisateur connecté de pouvoir signer un acte de naissance si il en a le droit","code_module"=>"MOD_0002","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0002"],
            ["code_fonctionnalite"=>"FNC_0016",'lib_fonctionnalite'=> "Créer une déclaration de décès","lib_technique"=>"module.acteDeces.declarationacteDeces.create","description_fonctionnalite"=>"Cette fonction permet à l'utilisateur connecté de pouvoir créer une déclaration de décès si il en a le droit","code_module"=>"MOD_0003","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0003"],
            ["code_fonctionnalite"=>"FNC_0017",'lib_fonctionnalite'=> "générer un acte de décès","lib_technique"=>"module.acteDeces.generate","description_fonctionnalite"=>"Cette fonction permet à l'utilisateur connecté de pouvoir générer un acte de décès si il en a le droit","code_module"=>"MOD_0003","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0003"],
            ["code_fonctionnalite"=>"FNC_0018",'lib_fonctionnalite'=> "Créer un certificat de constatation de décès","lib_technique"=>"module.acteDeces.CCDeces.create","description_fonctionnalite"=>"Cette fonction permet à l'utilisateur connecté de pouvoir créer un certificat de constatation de décès si il en a le droit","code_module"=>"MOD_0003","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0003"],
            ["code_fonctionnalite"=>"FNC_0019",'lib_fonctionnalite'=> "Modifier une déclaration de décès","lib_technique"=>"module.acteDeces.declarationacteDeces.edit","description_fonctionnalite"=>"Cette fonction permet à l'utilisateur connecté de pouvoir modifier une déclaration de décès si il en a le droit","code_module"=>"MOD_0003","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0003"],
            ["code_fonctionnalite"=>"FNC_0020",'lib_fonctionnalite'=> "Signer  un acte de décès","lib_technique"=>"module.acteDeces.signature","description_fonctionnalite"=>"Cette fonction permet à l'utilisateur connecté de pouvoir signer un acte de décès si il en a le droit","code_module"=>"MOD_0003","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0003"],
            ["code_fonctionnalite"=>"FNC_0021",'lib_fonctionnalite'=> "Parapher un registre","lib_technique"=>"module.fonctionnalites.parapher","description_fonctionnalite"=>"Permet à l'utilisateur de parapher un registre si il en a le droit.","code_module"=>"MOD_0001","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0001"],
            ["code_fonctionnalite"=>"FNC_0022",'lib_fonctionnalite'=> "générer une réquisition","lib_technique"=>"module.fonctionnalites.requisitions","description_fonctionnalite"=>"Permet à l'utilisateur de générer une réquisition si il en a le droit.","code_module"=>"MOD_0001","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0001"],
            ["code_fonctionnalite"=>"FNC_0023",'lib_fonctionnalite'=> "Créer un certificat de non inscription de naissance","lib_technique"=>"module.acteNaissance.CNINaissance.create","description_fonctionnalite"=>"Cette fonction permet à l'utilisateur connecté de pouvoir créer un certificat de non inscription de naissance si il en a le droit","code_module"=>"MOD_0002","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0002"],
            ["code_fonctionnalite"=>"FNC_0024",'lib_fonctionnalite'=> "Créer un certificat de transcription de naissance","lib_technique"=>"module.acteNaissance.CTNaissance.create","description_fonctionnalite"=>"Cette fonction permet à l'utilisateur connecté de pouvoir créer un certificat de transcription de naissance si il en a le droit","code_module"=>"MOD_0002","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0002"],
            ["code_fonctionnalite"=>"FNC_0025",'lib_fonctionnalite'=> "Créer un certificat de destruction de l'acte de naissance","lib_technique"=>"module.acteNaissance.CDANaissance.create","description_fonctionnalite"=>"Cette fonction permet à l'utilisateur connecté de pouvoir créer un certificat de destruction de l'acte de naissance si il en a le droit","code_module"=>"MOD_0002","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0002"],
            ["code_fonctionnalite"=>"FNC_0026",'lib_fonctionnalite'=> "Créer un certificat de non inscription de décès","lib_technique"=>"module.acteDeces.CNIDeces.create","description_fonctionnalite"=>"Cette fonction permet à l'utilisateur connecté de pouvoir créer un certificat de non inscription de décès si il en a le droit","code_module"=>"MOD_0003","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0003"],
            ["code_fonctionnalite"=>"FNC_0027",'lib_fonctionnalite'=> "Créer un certificat de transcription de décès","lib_technique"=>"module.acteDeces.CTDeces.create","description_fonctionnalite"=>"Cette fonction permet à l'utilisateur connecté de pouvoir créer un certificat de transcription de décès si il en a le droit","code_module"=>"MOD_0003","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0003"],
            ["code_fonctionnalite"=>"FNC_0028",'lib_fonctionnalite'=> "créer un registre","lib_technique"=>"module.registre.create","description_fonctionnalite"=>"Permet à l'utilisateur de créer un registre si il en a le droit.","code_module"=>"MOD_0001","etat_fonctionnalite"=>"Activé","code_fonctionnalite_parent"=>"FNC_0001"],

        ];

        foreach ($data as $d){
            Fonctionnalite::create($d);
        }
    }
}
