<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Repository\ArticleRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Result;
use PHPUnit\Framework\TestCase;

final class ArticleRepositoryTest extends TestCase
{
    public function testFindByIdReturnsNullForNonExistent(): void
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchAssociative')->willReturn(false);

        $qb = $this->createMock(QueryBuilder::class);
        $qb->method('setParameter')->willReturnSelf();
        $qb->method('executeQuery')->willReturn($result);
        $qb->method('fetchAssociative')->willReturnCallback(fn () => $result->fetchAssociative());

        $connection = $this->createMock(Connection::class);
        $connection->method('createQueryBuilder')->willReturn($qb);

        $repo = new ArticleRepository($connection);

        self::assertNull($repo->findById(999));
    }
}
