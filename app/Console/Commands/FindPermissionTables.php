<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FindPermissionTables extends Command
{
    protected $signature = 'sifec:find-permission-tables';

    protected $description = 'Trouver les tables de permissions';

    public function handle()
    {
        $tables = DB::select("SHOW TABLES LIKE 'sifec2.%fonc%'");

        if (empty($tables)) {
            $tables = DB::select("SHOW TABLES LIKE '%fonc%'");
        }

        $this->info("Tables trouvées contenant 'fonc':");
        foreach ($tables as $table) {
            $tableName = array_values((array) $table)[0];
            $this->line('- '.$tableName);
        }

        return 0;
    }
}
