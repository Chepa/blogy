<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\VisitorIdResolver;
use PHPUnit\Framework\TestCase;

final class VisitorIdResolverTest extends TestCase
{
    private VisitorIdResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resolver = new VisitorIdResolver();
    }

    protected function tearDown(): void
    {
        unset($_COOKIE['blogy_visitor']);
        parent::tearDown();
    }

    public function testResolveReturnsExistingValidCookie(): void
    {
        $validId = 'a1b2c3d4e5f6789012345678abcdef01';
        $_COOKIE['blogy_visitor'] = $validId;

        $result = $this->resolver->resolve();

        self::assertSame($validId, $result);
    }

    public function testResolveGeneratesNewIdWhenCookieMissing(): void
    {
        unset($_COOKIE['blogy_visitor']);

        $result = $this->resolver->resolve();

        self::assertSame(32, strlen($result));
        self::assertTrue(ctype_xdigit($result));
    }

    public function testResolveGeneratesNewIdWhenCookieInvalid(): void
    {
        $_COOKIE['blogy_visitor'] = 'short';

        $result = $this->resolver->resolve();

        self::assertSame(32, strlen($result));
        self::assertTrue(ctype_xdigit($result));
    }
}
