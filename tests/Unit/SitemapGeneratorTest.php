<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\SitemapGenerator;
use App\UrlHelper;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;

final class SitemapGeneratorTest extends TestCase
{
    public function testGenerateContainsHomeAndUrls(): void
    {
        $qbArticles = $this->createMock(QueryBuilder::class);
        $qbArticles->method('select')->willReturnSelf();
        $qbArticles->method('from')->willReturnSelf();
        $qbArticles->method('orderBy')->willReturnSelf();
        $qbArticles->method('fetchAllAssociative')->willReturn([
            ['id' => 1, 'published_at' => '2024-01-15 12:00:00'],
        ]);

        $qbCategories = $this->createMock(QueryBuilder::class);
        $qbCategories->method('select')->willReturnSelf();
        $qbCategories->method('from')->willReturnSelf();
        $qbCategories->method('fetchAllAssociative')->willReturn([
            ['id' => 1],
            ['id' => 2],
        ]);

        $connection = $this->createMock(Connection::class);
        $connection->method('createQueryBuilder')->willReturnOnConsecutiveCalls($qbArticles, $qbCategories);

        $articleRepo = new ArticleRepository($connection);
        $categoryRepo = new CategoryRepository($connection, 6);
        $urlHelper = new UrlHelper('https://example.com');

        $generator = new SitemapGenerator($articleRepo, $categoryRepo, $urlHelper);
        $xml = $generator->generate();

        self::assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $xml);
        self::assertStringContainsString('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', $xml);
        self::assertStringContainsString('https://example.com/', $xml);
        self::assertStringContainsString('https://example.com/article/1', $xml);
        self::assertStringContainsString('https://example.com/category/1', $xml);
        self::assertStringContainsString('https://example.com/category/2', $xml);
        self::assertStringContainsString('<lastmod>2024-01-15</lastmod>', $xml);
        self::assertStringEndsWith('</urlset>', trim($xml));
    }
}
