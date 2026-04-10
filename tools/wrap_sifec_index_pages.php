<?php
/**
 * Enveloppe le contenu de @section('corps') avec .page-sifec-index > .an-shell > .an-body
 */
$base = dirname(__DIR__);
$dirs = [
    $base . DIRECTORY_SEPARATOR . 'Modules',
    $base . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views',
];

$skipSubstrings = [
    'page-sifec-index',
    'page-notifications-sifec',
];

/** Pages référentiel géographique déjà en charte « localités » (éviter double coque) */
$skipIfContains = [
    '<div class="sifec-localite-page',
];

$skipExtends = [
    "extends('reporting::",
    'extends("reporting::',
    "extends('mobile::",
    'extends("mobile::',
];

function collectIndexFiles(string $dir): Generator
{
    if (!is_dir($dir)) {
        return;
    }
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
    );
    foreach ($it as $file) {
        if (strtolower($file->getFilename()) === 'index.blade.php') {
            yield $file->getPathname();
        }
    }
}

$updated = 0;
$skipped = 0;

foreach ($dirs as $dir) {
    foreach (collectIndexFiles($dir) as $filepath) {
        $raw = file_get_contents($filepath);
        if ($raw === false) {
            continue;
        }

        foreach ($skipExtends as $ex) {
            if (strpos($raw, $ex) !== false) {
                $skipped++;
                continue 2;
            }
        }

        foreach ($skipSubstrings as $sub) {
            if (strpos($raw, $sub) !== false) {
                $skipped++;
                continue 2;
            }
        }
        foreach ($skipIfContains as $sub) {
            if (strpos($raw, $sub) !== false) {
                $skipped++;
                continue 2;
            }
        }

        if (!preg_match("/@section\\(\\s*['\"]corps['\"]\\s*\\)/", $raw)) {
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
        if (strpos(trim($inner), '<div class="page-sifec-index">') === 0) {
            $skipped++;
            continue;
        }

        $wrapped = '<div class="page-sifec-index">' . "\n"
            . '<div class="an-shell">' . "\n"
            . '<div class="an-body">' . "\n"
            . rtrim($inner, "\r\n")
            . "\n</div>\n</div>\n</div>\n";

        $newRaw = substr($raw, 0, $startInner) . $wrapped . substr($raw, $endPos);
        file_put_contents($filepath, $newRaw);
        $updated++;
        echo "OK: " . str_replace($base . DIRECTORY_SEPARATOR, '', $filepath) . "\n";
    }
}

echo "\nDone. Updated: $updated, skipped: $skipped\n";
