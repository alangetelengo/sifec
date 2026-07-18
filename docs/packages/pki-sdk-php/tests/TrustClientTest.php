<?php

declare(strict_types=1);

namespace PkiSdk\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use PkiSdk\NotFoundException;
use PkiSdk\RateLimitException;
use PkiSdk\ServiceUnavailableException;
use PkiSdk\TrustClient;
use PkiSdk\TrustException;
use PkiSdk\UnauthorizedException;

final class TrustClientTest extends TestCase
{
    private static string $baseUrl;
    private static string $routerFile;
    private static string $stateFile;

    /** @var resource|null */
    private static mixed $process = null;

    public static function setUpBeforeClass(): void
    {
        self::$routerFile = sys_get_temp_dir() . '/pki-sdk-router-' . bin2hex(random_bytes(4)) . '.php';
        self::$stateFile = sys_get_temp_dir() . '/pki-sdk-state-' . bin2hex(random_bytes(4)) . '.json';
        file_put_contents(self::$stateFile, json_encode([]));
        file_put_contents(self::$routerFile, self::routerSource());

        $port = random_int(19000, 24000);
        self::$baseUrl = 'http://127.0.0.1:' . $port;
        $command = PHP_BINARY . ' -S 127.0.0.1:' . $port . ' ' . escapeshellarg(self::$routerFile);

        self::$process = proc_open(
            $command,
            [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ],
            $pipes,
            null,
            ['PKI_SDK_TEST_STATE' => self::$stateFile]
        );

        if (! is_resource(self::$process)) {
            self::fail('Unable to start local PHP test server.');
        }

        $ready = false;
        for ($i = 0; $i < 50; $i++) {
            $client = new TrustClient(self::$baseUrl, '', 1, 1);
            try {
                $client->get('/ok');
                $ready = true;
                break;
            } catch (\Throwable) {
                usleep(100_000);
            }
        }

        if (! $ready) {
            self::fail('Local PHP test server did not become ready.');
        }
    }

    public static function tearDownAfterClass(): void
    {
        if (is_resource(self::$process)) {
            proc_terminate(self::$process);
        }
        @unlink(self::$routerFile);
        @unlink(self::$stateFile);
    }

    public function testPostSendsJsonHeadersApiKeyAndTimeout(): void
    {
        $client = new TrustClient(self::$baseUrl, 'test-key', 7, 1);

        $response = $client->post('/echo', ['hello' => 'world'], 4);

        self::assertSame('POST', $response['method']);
        self::assertSame('/echo', $response['path']);
        self::assertSame(['hello' => 'world'], $response['json']);
        self::assertSame('test-key', $response['headers']['X-Api-Key'] ?? null);
        self::assertStringContainsString('application/json', $response['headers']['Content-Type'] ?? '');
        self::assertStringContainsString('application/json', $response['headers']['Accept'] ?? '');
    }

    public function testGetReturnsDecodedJson(): void
    {
        $client = new TrustClient(self::$baseUrl, 'test-key', 7, 1);

        self::assertSame(['ok' => true], $client->get('/ok'));
    }

    public function testDoesNotForceCaInfoWhenCaBundleIsAbsent(): void
    {
        $client = new InspectableTrustClient(self::$baseUrl, '', 7, 1);

        self::assertSame(['ok' => true], $client->get('/ok'));

        self::assertArrayNotHasKey(CURLOPT_CAINFO, $client->lastCurlOptions);
        self::assertArrayNotHasKey(CURLOPT_SSL_VERIFYPEER, $client->lastCurlOptions);
        self::assertArrayNotHasKey(CURLOPT_SSL_VERIFYHOST, $client->lastCurlOptions);
    }

    public function testCaBundleConfiguresCurlCaInfoWithoutDisablingTls(): void
    {
        $caBundle = tempnam(sys_get_temp_dir(), 'pki-sdk-ca-');
        self::assertIsString($caBundle);
        file_put_contents($caBundle, "-----BEGIN CERTIFICATE-----\nMIIB\n-----END CERTIFICATE-----\n");

        try {
            $client = new InspectableTrustClient(self::$baseUrl, '', 7, 1, [0], caBundle: $caBundle);

            self::assertSame(['ok' => true], $client->get('/ok'));

            self::assertSame($caBundle, $client->lastCurlOptions[CURLOPT_CAINFO] ?? null);
            self::assertArrayNotHasKey(CURLOPT_SSL_VERIFYPEER, $client->lastCurlOptions);
            self::assertArrayNotHasKey(CURLOPT_SSL_VERIFYHOST, $client->lastCurlOptions);
        } finally {
            @unlink($caBundle);
        }
    }

    public function testUnreadableCaBundleIsRejected(): void
    {
        $missing = sys_get_temp_dir() . '/pki-sdk-missing-ca-' . bin2hex(random_bytes(4)) . '.pem';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('TLS CA bundle is not readable');

        new TrustClient(self::$baseUrl, '', 7, 1, caBundle: $missing);
    }

    public function testEmptyCaBundlePathIsRejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('TLS CA bundle path must not be empty');

        new TrustClient(self::$baseUrl, '', 7, 1, caBundle: '   ');
    }

    public function testInvalidJsonThrowsTrustException(): void
    {
        $this->expectException(TrustException::class);
        $this->expectExceptionMessage('Invalid JSON response');

        (new TrustClient(self::$baseUrl, '', 7, 1))->get('/invalid-json');
    }

    /** @param class-string<\Throwable> $expected */
    #[DataProvider('errorMappingProvider')]
    public function testHttpErrorsMapToTypedExceptions(int $status, string $expected): void
    {
        $this->expectException($expected);

        (new TrustClient(self::$baseUrl, '', 7, 1))->get('/status/' . $status);
    }

    /**
     * @return iterable<string, array{int, class-string<\Throwable>}>
     */
    public static function errorMappingProvider(): iterable
    {
        yield '401' => [401, UnauthorizedException::class];
        yield '403' => [403, UnauthorizedException::class];
        yield '404' => [404, NotFoundException::class];
        yield '429' => [429, RateLimitException::class];
        yield '503' => [503, ServiceUnavailableException::class];
        yield '504' => [504, ServiceUnavailableException::class];
        yield '422' => [422, TrustException::class];
    }

    /**
     * @return iterable<string, array{int, class-string<\Throwable>}>
     */
    public static function nonRetryableStatusProvider(): iterable
    {
        yield '401' => [401, UnauthorizedException::class];
        yield '403' => [403, UnauthorizedException::class];
        yield '404' => [404, NotFoundException::class];
        yield '429' => [429, RateLimitException::class];
    }

    public function testRetriesOnlyServiceUnavailableResponses(): void
    {
        $this->resetState();
        $client = new TrustClient(self::$baseUrl, '', 7, 3, [0, 0, 0]);

        $response = $client->get('/retry-then-ok');

        self::assertSame(3, $response['attempt']);
    }

    public function testRetriesGatewayTimeoutResponses(): void
    {
        $this->resetState();
        $client = new TrustClient(self::$baseUrl, '', 7, 3, [0, 0, 0]);

        $response = $client->get('/retry-504-then-ok');

        self::assertSame(3, $response['attempt']);
    }

    /**
     * @param class-string<\Throwable> $expected
     */
    #[DataProvider('nonRetryableStatusProvider')]
    public function testDoesNotRetryClientErrorResponses(int $status, string $expected): void
    {
        $this->resetState();

        try {
            (new TrustClient(self::$baseUrl, '', 7, 3, [0, 0, 0]))->get('/always-status/' . $status);
            self::fail('Expected ' . $expected . '.');
        } catch (\Throwable $e) {
            self::assertInstanceOf($expected, $e);
            $state = $this->readState();
            self::assertSame(1, $state['alwaysStatus'][$status] ?? null);
        }
    }

    public function testCurlFailureThrowsServiceUnavailableException(): void
    {
        $this->expectException(ServiceUnavailableException::class);

        (new TrustClient('http://127.0.0.1:9', '', 1, 1))->get('/unreachable');
    }

    private function resetState(): void
    {
        file_put_contents(self::$stateFile, json_encode([]));
    }

    /**
     * @return array<string, mixed>
     */
    private function readState(): array
    {
        $decoded = json_decode((string) file_get_contents(self::$stateFile), true);

        return is_array($decoded) ? $decoded : [];
    }

    private static function routerSource(): string
    {
        return <<<'PHP'
<?php
$stateFile = getenv('PKI_SDK_TEST_STATE');
$state = is_file($stateFile) ? json_decode((string) file_get_contents($stateFile), true) : [];
if (!is_array($state)) {
    $state = [];
}

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

function send_json(int $status, array $payload): void {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($payload);
}

if ($path === '/invalid-json') {
    http_response_code(200);
    header('Content-Type: application/json');
    echo '{invalid';
    return;
}

if ($path === '/ok') {
    send_json(200, ['ok' => true]);
    return;
}

if (preg_match('#^/status/(\d+)$#', $path, $matches)) {
    send_json((int) $matches[1], ['error' => 'status ' . $matches[1]]);
    return;
}

if ($path === '/retry-then-ok') {
    $state['retry'] = ($state['retry'] ?? 0) + 1;
    file_put_contents($stateFile, json_encode($state));
    if ($state['retry'] < 3) {
        send_json(503, ['error' => 'try again']);
        return;
    }
    send_json(200, ['attempt' => $state['retry']]);
    return;
}

if ($path === '/retry-504-then-ok') {
    $state['retry504'] = ($state['retry504'] ?? 0) + 1;
    file_put_contents($stateFile, json_encode($state));
    if ($state['retry504'] < 3) {
        send_json(504, ['error' => 'gateway timeout']);
        return;
    }
    send_json(200, ['attempt' => $state['retry504']]);
    return;
}

if (preg_match('#^/always-status/(\d+)$#', $path, $matches)) {
    $status = (int) $matches[1];
    $state['alwaysStatus'] = $state['alwaysStatus'] ?? [];
    $state['alwaysStatus'][$status] = ($state['alwaysStatus'][$status] ?? 0) + 1;
    file_put_contents($stateFile, json_encode($state));
    send_json($status, ['error' => 'status ' . $status]);
    return;
}

$headers = function_exists('getallheaders') ? getallheaders() : [];
$raw = file_get_contents('php://input');
send_json(200, [
    'ok' => true,
    'method' => $_SERVER['REQUEST_METHOD'],
    'path' => $path,
    'headers' => $headers,
    'json' => $raw !== '' ? json_decode($raw, true) : null,
]);
PHP;
    }
}

final class InspectableTrustClient extends TrustClient
{
    /** @var array<int, mixed> */
    public array $lastCurlOptions = [];

    /**
     * @param array<int, mixed> $options
     */
    protected function configureCurl(mixed $ch, array $options): void
    {
        $this->lastCurlOptions = $options;

        parent::configureCurl($ch, $options);
    }
}
