<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Mariage\Entities\DeclarationMariage;

class DebugMariageQuery extends Command
{
    protected $signature = 'debug:mariage-query';
    protected $description = 'Debug la requête de déclaration de mariage';

    public function handle()
    {
        $this->info("=== DÉBOGAGE DE LA REQUÊTE DE DÉCLARATION DE MARIAGE ===");
        
        // 1. Vérifier les données de base
        $this->info("\n1. Vérification des données de base:");
        
        $totalDeclarations = DB::table('t_declaration_mariage')->count();
        $this->line("Total des déclarations de mariage: $totalDeclarations");
        
        $declarationsApprouvees = DB::table('t_declaration_mariage')
            ->where('cec_approuver', 'OUI')
            ->count();
        $this->line("Déclarations approuvées par le CEC: $declarationsApprouvees");
        
        // Vérifier les types de déclaration
        $typesDeclaration = DB::table('t_declaration_mariage')
            ->select('type_declaration', DB::raw('count(*) as count'))
            ->groupBy('type_declaration')
            ->get();
        $this->line("Types de déclaration:");
        foreach($typesDeclaration as $type) {
            $this->line("  - {$type->type_declaration}: {$type->count}");
        }
        
        // 2. Vérifier les institutions
        $this->info("\n2. Vérification des institutions:");
        $institutions = DB::table('t_declaration_mariage')
            ->select('code_institution', 'code_institution_destinataire', DB::raw('count(*) as count'))
            ->groupBy('code_institution', 'code_institution_destinataire')
            ->get();
        foreach($institutions as $inst) {
            $this->line("  - Institution: {$inst->code_institution}, Destinataire: {$inst->code_institution_destinataire}, Count: {$inst->count}");
        }
        
        // 3. Vérifier les réquisitions
        $this->info("\n3. Vérification des réquisitions:");
        $requisitions = DB::table('t_requisition')
            ->select('code_declaration', 'statut', DB::raw('count(*) as count'))
            ->groupBy('code_declaration', 'statut')
            ->get();
        foreach($requisitions as $req) {
            $this->line("  - Déclaration: {$req->code_declaration}, Statut: {$req->statut}, Count: {$req->count}");
        }
        
        // 4. Vérifier les jugements
        $this->info("\n4. Vérification des jugements:");
        $jugements = DB::table('t_jugement')
            ->select('code_declaration', 'statut', DB::raw('count(*) as count'))
            ->groupBy('code_declaration', 'statut')
            ->get();
        foreach($jugements as $jug) {
            $this->line("  - Déclaration: {$jug->code_declaration}, Statut: {$jug->statut}, Count: {$jug->count}");
        }
        
        // 5. Test avec la déclaration spécifique des données SQL
        $this->info("\n5. Test avec la déclaration CDM_00000001:");
        
        $declaration = DB::table('t_declaration_mariage')
            ->where('code_declaration_mariage', 'CDM_00000001')
            ->first();
            
        if ($declaration) {
            $this->line("Déclaration trouvée:");
            $this->line("  - cec_approuver: {$declaration->cec_approuver}");
            $this->line("  - type_declaration: {$declaration->type_declaration}");
            $this->line("  - tribunal_approuver: {$declaration->tribunal_approuver}");
            $this->line("  - code_institution: {$declaration->code_institution}");
            $this->line("  - code_institution_destinataire: {$declaration->code_institution_destinataire}");
            
            // Vérifier la réquisition
            $requisition = DB::table('t_requisition')
                ->where('code_declaration', 'CDM_00000001')
                ->first();
            if ($requisition) {
                $this->line("  - Réquisition trouvée avec statut: {$requisition->statut}");
            } else {
                $this->line("  - Aucune réquisition trouvée");
            }
            
            // Vérifier le jugement
            $jugement = DB::table('t_jugement')
                ->where('code_declaration', 'CDM_00000001')
                ->first();
            if ($jugement) {
                $this->line("  - Jugement trouvée avec statut: {$jugement->statut}");
            } else {
                $this->line("  - Aucun jugement trouvé");
            }
        } else {
            $this->line("Déclaration CDM_00000001 non trouvée");
        }
        
        // 6. Test de la requête complète
        $this->info("\n6. Test de la requête complète:");
        
        // Simuler l'institution (basé sur les données SQL)
        $institutionCode = 'INS_0047';
        
        $result = DeclarationMariage::where(function($query) use ($institutionCode) {
            $query->where("code_institution_destinataire", $institutionCode)
                  ->orWhere("code_institution", $institutionCode);
        })
        ->where("cec_approuver", "OUI")
        ->where(function($query) {
            $query->where("type_declaration", "!=", "DISPENSE")
                  ->orWhere(function($subQuery) {
                      $subQuery->where("type_declaration", "DISPENSE")
                               ->where("tribunal_approuver", "OUI")
                               ->where(function($requisitionQuery) {
                                   $requisitionQuery->whereHas('requisition', function($reqQuery) {
                                                       $reqQuery->where('statut', 'envoyée');
                                                   })
                                                   ->orWhereHas('jugement', function($jugQuery) {
                                                       $jugQuery->where('statut', 'envoyée');
                                                   });
                               });
                  });
        })
        ->get();
        
        $this->line("Résultat de la requête complète: " . $result->count() . " déclarations");
        
        if ($result->count() > 0) {
            foreach ($result as $decl) {
                $this->line("  - {$decl->code_declaration_mariage}: {$decl->type_declaration}");
            }
        }
        
        $this->info("\n=== FIN DU DÉBOGAGE ===");
    }
}


