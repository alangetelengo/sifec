<?php

/**
 * Insère [] comme 2e argument (options) lorsque flash()->type($a, $b) se termine par une chaîne littérale $b.
 */
$roots = [
    dirname(__DIR__).DIRECTORY_SEPARATOR.'app',
    dirname(__DIR__).DIRECTORY_SEPARATOR.'Modules',
];

$pattern = '/flash\(\)->(success|error|warning|info)\((.*?),\s*((["\'])(?:\\\\.|(?!\4).)*\4)\s*\)/s';

$changedFiles = 0;
foreach ($roots as $root) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
    foreach ($it as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }
        $path = $file->getPathname();
        $content = file_get_contents($path);
        if (! str_contains($content, 'flash()->')) {
            continue;
        }
        $new = preg_replace_callback(
            $pattern,
            static function (array $m): string {
                $inner = $m[2];
                if (str_contains($inner, '[]')) {
                    return $m[0];
                }
                $title = $m[3];

                return 'flash()->'.$m[1].'('.$inner.', [], '.$title.')';
            },
            $content
        );
        if ($new !== null && $new !== $content) {
            file_put_contents($path, $new);
            $changedFiles++;
        }
    }
}

echo "Fichiers corrigés: {$changedFiles}\n";
