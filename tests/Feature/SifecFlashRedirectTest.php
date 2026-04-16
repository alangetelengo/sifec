<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Vérifie que le middleware AppendSifecFlashQueryToRedirects ajoute bien le flash sur les 302.
 * Les routes sont déclarées dans routes/web.php lorsque app()->runningUnitTests() est vrai.
 */
final class SifecFlashRedirectTest extends TestCase
{
    public function test_redirect_location_contains_sifec_inline(): void
    {
        $first = $this->get('/__sifec_flash_src');

        $first->assertStatus(302);
        $location = (string) $first->headers->get('Location', '');
        $this->assertNotSame('', $location, 'Le header Location doit être présent sur la 302.');

        $decoded = rawurldecode($location);
        $this->assertStringContainsString(
            'sifec_inline=',
            $decoded,
            'AppendSifecFlashQueryToRedirects doit ajouter sifec_inline. Location='.$location
        );
    }

    public function test_page_after_redirect_shows_flash_text(): void
    {
        $this->followingRedirects()
            ->get('/__sifec_flash_src')
            ->assertOk()
            ->assertSee('Flash ok', false);
    }
}
