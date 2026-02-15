<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\UrlHelper;
use PHPUnit\Framework\TestCase;

final class UrlHelperTest extends TestCase
{
    private UrlHelper $helper;

    protected function setUp(): void
    {
        $this->helper = new UrlHelper('http://localhost');
    }

    public function testHome(): void
    {
        self::assertSame('http://localhost/', $this->helper->home());
    }

    public function testArticle(): void
    {
        self::assertSame('http://localhost/article/1', $this->helper->article(1));
    }

    public function testCategoryWithIdOnly(): void
    {
        self::assertSame('http://localhost/category/1', $this->helper->category(1));
    }

    public function testCategoryWithSortAndDir(): void
    {
        $url = $this->helper->category(1, 'date', 'desc', null);
        self::assertStringContainsString('?', $url, 'Query string must start with ?');
        self::assertStringContainsString('sort=date', $url);
        self::assertStringContainsString('dir=desc', $url);
        self::assertSame('http://localhost/category/1?sort=date&dir=desc', $url);
    }

    public function testCategoryWithPage(): void
    {
        $url = $this->helper->category(1, null, null, 2);
        self::assertStringContainsString('?', $url);
        self::assertStringContainsString('page=2', $url);
    }

    public function testAbsoluteImageWithRelativePath(): void
    {
        self::assertSame('http://localhost/images/foo.jpg', $this->helper->absoluteImage('images/foo.jpg'));
    }

    public function testAbsoluteImageWithAbsoluteUrl(): void
    {
        self::assertSame('https://cdn.example.com/img.jpg', $this->helper->absoluteImage('https://cdn.example.com/img.jpg'));
    }

    public function testAbsoluteImageWithEmptyString(): void
    {
        self::assertSame('', $this->helper->absoluteImage(''));
    }
}
