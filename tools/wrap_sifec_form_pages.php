<?php
/**
 * Enveloppe @section('corps') des vues create.blade.php / edit.blade.php avec .page-sifec-form
 */
$base = dirname(__DIR__);
$dirs = [$base . DIRECTORY_SEPARATOR . 'Modules'];

$skipSubstrings = [
    'page-sifec-form',
    'page-utilisateur-form-sifec',
    'page-fonctionnalite-form-sifec',
    'page-fonction-form-sifec',
    'page-rectification',
];

$skipExtends = [
    "extends('reporting::",
    'extends("reporting::',
    "extends('mobile::",
    'extends("mobile::',
    "extends('mariage::layouts.master",
    'extends("mariage::layouts.master',
];

function walkPhp(string $dir, array $names): Generator
{
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
    );
    foreach ($it as $file) {
        if ($file->isDir()) {
            continue;
        }
        $n = $file->getFilename();
        if (in_array($n, $names, true) && strpos($file->getPathname(), DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR) === false) {
            yield $file->getPathname();
        }
    }
}

$updated = 0;
$skipped = 0;

foreach (walkPhp($dirs[0], ['create.blade.php', 'edit.blade.php']) as $filepath) {
    $raw = file_get_contents($filepath);
    if ($raw === false) {
        continue;
    }

    $normPath = str_replace('\\', '/', $filepath);
    if (strpos($normPath, '/Modules/Rectification/') !== false
        || stripos($normPath, '/Nouveau dossier/') !== false) {
        $skipped++;
        continue;
    }

    foreach ($skipExtends as $ex) {
        if (strpos($raw, $ex) !== false) {
            $skipped++;
            continue 2;
        }
    }
    if (!preg_match("/@extends\\(\\s*['\"]layout\\.app['\"]\\s*\\)/", $raw)
        && !preg_match('/@extends\\(\\s*["\']layout\\.app["\']\\s*\\)/', $raw)) {
        $skipped++;
        continue;
    }

    foreach ($skipSubstrings as $sub) {
        if (strpos($raw, $sub) !== false) {
            $skipped++;
            continue 2;
        }
    }

    if (!preg_match("/@section\\(\\s*['\"]corps['\"]\\s*\\)/", $raw)) {
        $skipped++;
        continue;
    }

    if (preg_match('/@section\\(\\s*[\'"]corps[\'"]\\s*\\)\\s*\\R\\s*<div class="page-sifec-form">/', $raw)) {
        $skipped++;
        continue;
    }

    $corpsTag = null;
    $tagLen = 0;
    if (preg_match("/@section\\(\\s*'corps'\\s*\\)/", $raw, $m, PREG_OFFSET_CAPTURE)) {
        $corpsTag = $m[0][1];
        $tagLen = strlen($m[0][0]);
    } elseif (preg_match('/@section\\(\\s*"corps"\\s*\\)/', $raw, $m, PREG_OFFSET_CAPTURE)) {
        $corpsTag = $m[0][1];
        $tagLen = strlen($m[0][0]);
    } else {
        $skipped++;
        continue;
    }

    $startInner = $corpsTag + $tagLen;
    $len = strlen($raw);
    while ($startInner < $len && ($raw[$startInner] === "\r" || $raw[$startInner] === "\n")) {
        $startInner++;
    }

    $endPos = strpos($raw, '@endsection', $startInner);
    if ($endPos === false) {
        $skipped++;
        continue;
    }

    $inner = substr($raw, $startInner, $endPos - $startInner);
    if (strpos(trim($inner), '<div class="page-sifec-form">') === 0) {
        $skipped++;
        continue;
    }

    $wrapped = '<div class="page-sifec-form">' . "\n"
        . rtrim($inner, "\r\n")
        . "\n</div>\n";

    $newRaw = substr($raw, 0, $startInner) . $wrapped . substr($raw, $endPos);
    file_put_contents($filepath, $newRaw);
    $updated++;
    echo "OK: " . str_replace($base . DIRECTORY_SEPARATOR, '', $filepath) . "\n";
}

echo "\nDone. Updated: $updated, skipped: $skipped\n";
