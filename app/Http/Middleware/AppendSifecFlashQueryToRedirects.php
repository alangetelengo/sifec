<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\SifecTransientFlashCookie;
use Closure;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie as SymfonyCookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ajoute ?sifec_inline= ou ?sifec_err_inline= aux redirections internes lorsqu’un flash
 * success/error est présent, pour afficher le message même si la session ne survit pas au redirect.
 */
final class AppendSifecFlashQueryToRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->hasSession() || ! $response->isRedirection()) {
            return $response;
        }

        $url = $response->headers->get('Location');
        if (! is_string($url) || $url === '') {
            $url = $response instanceof RedirectResponse ? $response->getTargetUrl() : '';
        }
        if ($url === '' || $this->urlAlreadyHasSifecFlash($url)) {
            return $response;
        }

        if ($this->isExternalRedirect($request, $url)) {
            return $response;
        }

        $session = $request->session();
        $maxLen = (int) config('sifec_notices.max_inline_length', 220);

        $successMsg = $session->get('success');
        $errorMsg = $session->get('error');

        if ($this->normalizeFlashMessage($successMsg) !== null) {
            $param = $this->buildInlineParam($successMsg, $maxLen);
            if ($param !== null) {
                $url = $this->appendQuery($url, 'sifec_inline', $param);
                $this->setRedirectLocation($response, $url);
            }
            $plain = $this->normalizeFlashMessage($successMsg);
            if ($plain !== null) {
                $this->attachTransientFlashCookie($request, $response, SifecTransientFlashCookie::pack('success', $plain));
            }
        } elseif ($this->normalizeFlashMessage($errorMsg) !== null) {
            $param = $this->buildInlineParam($errorMsg, $maxLen);
            if ($param !== null) {
                $url = $this->appendQuery($url, 'sifec_err_inline', $param);
                $this->setRedirectLocation($response, $url);
            }
            $plain = $this->normalizeFlashMessage($errorMsg);
            if ($plain !== null) {
                $this->attachTransientFlashCookie($request, $response, SifecTransientFlashCookie::pack('error', $plain));
            }
        }

        return $response;
    }

    private function setRedirectLocation(Response $response, string $url): void
    {
        if ($response instanceof RedirectResponse) {
            $response->setTargetUrl($url);

            return;
        }

        $response->headers->set('Location', $url);
    }

    /**
     * Cookie posé sur la réponse 302 (en plus du query string) : évite de dépendre uniquement de la file
     * {@see AddQueuedCookiesToResponse} si l’ordre des middlewares change.
     */
    private function attachTransientFlashCookie(Request $request, Response $response, string $packed): void
    {
        $raw = strtolower((string) (config('session.same_site') ?? 'lax'));
        $sameSite = match ($raw) {
            'none' => SymfonyCookie::SAMESITE_NONE,
            'strict' => SymfonyCookie::SAMESITE_STRICT,
            default => SymfonyCookie::SAMESITE_LAX,
        };

        $secure = $request->isSecure() || config('session.secure') === true;

        $response->headers->setCookie(SymfonyCookie::create(
            SifecTransientFlashCookie::NAME,
            $packed,
            time() + 300,
            '/',
            null,
            $secure,
            true,
            false,
            $sameSite
        ));
    }

    private function urlAlreadyHasSifecFlash(string $url): bool
    {
        return str_contains($url, 'sifec_inline=')
            || str_contains($url, 'sifec_err_inline=')
            || str_contains($url, 'sifec_notice=');
    }

    /**
     * Redirection vers un autre domaine : on n’ajoute pas de query / cookie.
     *
     * On compare uniquement les **noms d’hôte** (pas schéma ni port) : un 302 en https://hôte/…
     * alors que la requête courante est en http://hôte/… (Laragon, reverse-proxy, etc.) doit rester « interne ».
     * Sinon 80 vs 443 bloquait toute propagation (symptôme « aucun message »).
     */
    private function isExternalRedirect(Request $request, string $url): bool
    {
        if (! str_starts_with($url, 'http://') && ! str_starts_with($url, 'https://')) {
            return false;
        }

        if (! (bool) config('sifec_notices.strict_flash_redirect_host_check', false)) {
            return false;
        }

        $targetHost = strtolower((string) (parse_url($url, PHP_URL_HOST) ?: ''));
        if ($targetHost === '') {
            return true;
        }

        $hosts = [
            strtolower((string) (parse_url($request->root(), PHP_URL_HOST) ?: '')),
            strtolower($request->getHost()),
        ];

        $serverName = $request->server('SERVER_NAME');
        if (is_string($serverName) && $serverName !== '') {
            $hosts[] = strtolower($serverName);
        }

        $xff = $request->headers->get('X-Forwarded-Host');
        if (is_string($xff) && $xff !== '') {
            foreach (explode(',', $xff) as $part) {
                $h = strtolower(trim((string) preg_replace('/:\d+$/', '', trim($part))));
                if ($h !== '') {
                    $hosts[] = $h;
                }
            }
        }

        $appUrlHost = parse_url((string) config('app.url', ''), PHP_URL_HOST);
        if (is_string($appUrlHost) && $appUrlHost !== '') {
            $hosts[] = strtolower($appUrlHost);
        }

        $extra = config('sifec_notices.internal_redirect_hosts', []);
        if (is_array($extra)) {
            foreach ($extra as $h) {
                if (is_string($h) && $h !== '') {
                    $hosts[] = strtolower($h);
                }
            }
        }

        $hosts = array_values(array_unique(array_filter($hosts)));

        return ! in_array($targetHost, $hosts, true);
    }

    private function normalizeFlashMessage(mixed $message): ?string
    {
        if ($message instanceof MessageBag) {
            $message = $message->first();
        }
        if (is_array($message)) {
            $message = json_encode($message, JSON_UNESCAPED_UNICODE);
        }
        if (! is_string($message) || $message === '') {
            return null;
        }

        return $message;
    }

    private function buildInlineParam(mixed $message, int $maxLen): ?string
    {
        $message = $this->normalizeFlashMessage($message);
        if ($message === null) {
            return null;
        }
        if (function_exists('mb_strlen') && mb_strlen($message, 'UTF-8') > $maxLen) {
            $message = mb_substr($message, 0, $maxLen, 'UTF-8').'…';
        } elseif (strlen($message) > $maxLen) {
            $message = substr($message, 0, $maxLen).'…';
        }

        return $message;
    }

    private function appendQuery(string $url, string $key, string $value): string
    {
        $sep = str_contains($url, '?') ? '&' : '?';

        return $url.$sep.$key.'='.rawurlencode($value);
    }
}
