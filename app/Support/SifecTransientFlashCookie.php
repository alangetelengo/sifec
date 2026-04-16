<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Flash court via cookie signé (HMAC) : visible au prochain GET même si la session
 * ne survit pas au redirect (hôte / chemin / cookie de session différent).
 */
final class SifecTransientFlashCookie
{
    public const NAME = 'sifec_tf';

    private const MAX_MESSAGE = 900;

    /**
     * @return array{type: 'success'|'error', message: string}|null
     */
    public static function read(Request $request): ?array
    {
        $raw = $request->cookies->get(self::NAME);
        if (! is_string($raw) || $raw === '' || ! str_contains($raw, '|')) {
            return null;
        }

        [$b64, $sig] = explode('|', $raw, 2);
        $payload = base64_decode($b64, true);
        if ($payload === false) {
            return null;
        }

        $expected = hash_hmac('sha256', $payload, self::key());
        if (! hash_equals($expected, $sig)) {
            return null;
        }

        try {
            $data = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return null;
        }

        if (! is_array($data)) {
            return null;
        }

        $type = $data['t'] ?? '';
        $message = $data['m'] ?? '';
        if (($type !== 'success' && $type !== 'error') || ! is_string($message) || $message === '') {
            return null;
        }

        return ['type' => $type, 'message' => $message];
    }

    public static function pack(string $type, string $message): string
    {
        if ($type !== 'success' && $type !== 'error') {
            $type = 'success';
        }
        if (function_exists('mb_strlen') && mb_strlen($message, 'UTF-8') > self::MAX_MESSAGE) {
            $message = mb_substr($message, 0, self::MAX_MESSAGE, 'UTF-8').'…';
        } elseif (strlen($message) > self::MAX_MESSAGE) {
            $message = substr($message, 0, self::MAX_MESSAGE).'…';
        }

        $payload = json_encode(['t' => $type, 'm' => $message], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        $sig = hash_hmac('sha256', $payload, self::key());

        return base64_encode($payload).'|'.$sig;
    }

    private static function key(): string
    {
        $key = (string) config('app.key', '');

        return $key !== '' ? $key : 'sifec-fallback-key-change-app-key';
    }
}
