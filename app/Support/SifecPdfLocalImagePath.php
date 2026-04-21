<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Chemins fichier locaux pour Html2Pdf (Spipu) : sous Windows, {@see getimagesize()}
 * sur un chemin fichier échoue souvent (« Unable to get the size of the image »)
 * alors que le fichier est valide. Html2Pdf gère mieux les data-URI (getimagesizefromstring).
 */
final class SifecPdfLocalImagePath
{
    /**
     * @param  string|null  $relativePath  Chemin relatif sous public/app/ (ex. signature/xxx.png)
     * @return string|null Chemin absolu avec slash « / », ou null si fichier absent
     */
    public static function forHtml2Pdf(?string $relativePath): ?string
    {
        $resolved = self::resolveExistingFile($relativePath);

        return $resolved === null ? null : str_replace('\\', '/', $resolved);
    }

    /**
     * Image en data-URI pour Html2Pdf (recommandé pour &lt;img src&gt;).
     *
     * @return string|null data:image/png;base64,... ou null
     */
    public static function dataUriForHtml2Pdf(?string $relativePath): ?string
    {
        $path = self::resolveExistingFile($relativePath);
        if ($path === null) {
            return null;
        }

        $content = @file_get_contents($path);
        if ($content === false || $content === '') {
            return null;
        }

        $binary = self::binaryRenderableForHtml2Pdf($content);
        if ($binary === null) {
            return null;
        }

        return 'data:image/png;base64,'.base64_encode($binary);
    }

    /**
     * Pour &lt;img src="..."&gt; : data-URI en priorité (évite getimagesize chemin sous Windows).
     */
    public static function imgSrcForHtml2Pdf(?string $relativePath): ?string
    {
        $data = self::dataUriForHtml2Pdf($relativePath);
        if ($data !== null) {
            return $data;
        }

        return self::forHtml2Pdf($relativePath);
    }

    /**
     * Binaire image lisible par Html2Pdf (getimagesizefromstring).
     */
    private static function binaryRenderableForHtml2Pdf(string $raw): ?string
    {
        $info = @getimagesizefromstring($raw);
        if (is_array($info) && ($info[0] ?? 0) > 0 && ($info[1] ?? 0) > 0) {
            return $raw;
        }

        if (! function_exists('imagecreatefromstring')) {
            return null;
        }

        $img = @imagecreatefromstring($raw);
        if ($img === false) {
            return null;
        }

        imagealphablending($img, false);
        imagesavealpha($img, true);

        ob_start();
        $ok = @imagepng($img);
        $png = ob_get_clean();
        imagedestroy($img);

        if (! $ok || $png === false || $png === '') {
            return null;
        }

        return $png;
    }

    private static function resolveExistingFile(?string $relativePath): ?string
    {
        if ($relativePath === null) {
            return null;
        }

        $relativePath = trim(str_replace('\\', '/', $relativePath));
        if ($relativePath === '') {
            return null;
        }

        $candidates = [];

        if (str_starts_with($relativePath, '/') || preg_match('#^[a-zA-Z]:/#', $relativePath) === 1) {
            $candidates[] = $relativePath;
        }

        $rel = ltrim($relativePath, '/');
        if (str_starts_with($rel, 'app/')) {
            $candidates[] = public_path($rel);
        }
        $candidates[] = public_path('app/'.$rel);
        $candidates[] = base_path('public/app/'.$rel);

        foreach ($candidates as $path) {
            $normalized = str_replace('\\', '/', $path);
            $try = self::firstExistingFile($normalized);

            if ($try !== null) {
                return $try;
            }
        }

        return null;
    }

    private static function firstExistingFile(string $path): ?string
    {
        $real = realpath($path);
        if ($real !== false && is_file($real)) {
            return $real;
        }

        if (is_file($path)) {
            return $path;
        }

        return null;
    }
}
