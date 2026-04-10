<?php
$srcPath = __DIR__ . '/../Modules/Authentification/Resources/views/partials/sifec-utilisateur-form-styles.blade.php';
$src = file_get_contents($srcPath);
$css = preg_replace('/<style>\s*|\s*<\/style>/s', '', $src);

/* Bloc racine variables */
$css = preg_replace(
    '/\.page-utilisateur-form-sifec(\s*\{)/',
    '.page-utilisateur-form-sifec, .page-sifec-form$1',
    $css
);

/* Toute règle ".page-utilisateur-form-sifec .descendant {" ou "select..." */
$css = preg_replace_callback(
    '/\.page-utilisateur-form-sifec(\s+)((?:\.|select)[^{]+)\{/m',
    function ($m) {
        $desc = trim($m[2]);
        return '.page-utilisateur-form-sifec' . $m[1] . $desc . ', .page-sifec-form' . $m[1] . $desc . ' {';
    },
    $css
);

$extra = <<<'CSS'

/* Cartes principales sans classe pu-card (create / edit génériques) */
.page-sifec-form > .row > [class*="col-"] > .card {
    border: 1px solid var(--pu-line);
    border-radius: var(--pu-radius);
    box-shadow: var(--pu-shadow-lg);
    overflow: hidden;
    background: var(--pu-paper);
}

.page-sifec-form > .row > [class*="col-"] > .card > .card-header {
    background: linear-gradient(135deg, var(--pu-green-soft) 0%, #f4f7f5 100%);
    border-bottom: 1px solid var(--pu-line);
    padding: 1.1rem 1.25rem;
}

.page-sifec-form > .row > [class*="col-"] > .card > .card-header h4,
.page-sifec-form > .row > [class*="col-"] > .card > .card-header h5 {
    color: var(--pu-ink);
    font-weight: 700;
    font-size: 1.15rem;
    letter-spacing: -0.02em;
    margin: 0;
}

.page-sifec-form > .row > [class*="col-"] > .card > .card-body {
    padding: 1.25rem 1.35rem 1.5rem;
}

.page-sifec-form > .row > [class*="col-"] > .card.wizard-content > .card-body {
    padding: 1.25rem 1.35rem 1.5rem;
}

CSS;

$out = __DIR__ . '/../public/css/sifec-crud-forms.css';
file_put_contents($out, "/**\n * Formulaires create / edit SIFEC (charte utilisateur)\n */\n" . trim($css) . $extra);
echo "Written $out\n";
