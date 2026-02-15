<?php

namespace App\Repository;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;

final class ArticleRepository
{
    public function __construct(private readonly Connection $db)
    {
    }

    /**
     * @throws Exception
     */
    public function findById(int $id): ?array
    {
        $qb = $this->db->createQueryBuilder();
        $qb->select('id', 'image', 'title', 'description', 'text', 'views_count', 'published_at')
            ->from('articles')
            ->where('id = :id')
            ->setParameter('id', $id, ParameterType::INTEGER);

        $row = $qb->fetchAssociative();
        return $row !== false ? $row : null;
    }

    /** @return array<int>
     * @throws Exception
     */
    public function getCategoryIdsForArticle(int $articleId): array
    {
        $qb = $this->db->createQueryBuilder();
        $qb->select('category_id')
            ->from('article_category')
            ->where('article_id = :articleId')
            ->setParameter('articleId', $articleId, ParameterType::INTEGER);

        $rows = $qb->fetchAllAssociative();
        return array_map('intval', array_column($rows, 'category_id'));
    }

    /**
     * Увеличивает счётчик просмотров только при первом просмотре статьи уникальным посетителем.
     *
     * @throws Exception
     */
    public function incrementViewsIfUnique(int $articleId, string $visitorId): int
    {
        $sql = 'INSERT IGNORE INTO article_views (article_id, visitor_id) VALUES (:articleId, :visitorId)';
        $affected = $this->db->executeStatement($sql, [
            'articleId' => $articleId,
            'visitorId' => $visitorId,
        ], [
            'articleId' => ParameterType::INTEGER,
            'visitorId' => ParameterType::STRING,
        ]);

        if ($affected > 0) {
            $qb = $this->db->createQueryBuilder();
            $qb->update('articles')
                ->set('views_count', 'views_count + 1')
                ->where('id = :id')
                ->setParameter('id', $articleId, ParameterType::INTEGER);
            $qb->executeStatement();
        }

        $qb = $this->db->createQueryBuilder();
        $qb->select('views_count')
            ->from('articles')
            ->where('id = :id')
            ->setParameter('id', $articleId, ParameterType::INTEGER);

        return (int) $qb->fetchOne();
    }

    /**
     * @param array<int> $categoryIds
     * @return array<int, array>
     * @throws Exception
     */
    public function findSimilar(int $articleId, array $categoryIds, int $limit = 3): array
    {
        if (empty($categoryIds)) {
            return $this->findLatestExcluding($articleId, $limit);
        }

        $qb = $this->db->createQueryBuilder();
        $qb->select('a.id', 'a.image', 'a.title', 'a.views_count', 'a.published_at')
            ->from('articles', 'a')
            ->innerJoin('a', 'article_category', 'ac', 'ac.article_id = a.id')
            ->where('ac.category_id IN (:categoryIds)')
            ->andWhere('a.id != :excludeId')
            ->setParameter('categoryIds', $categoryIds, ArrayParameterType::INTEGER)
            ->setParameter('excludeId', $articleId, ParameterType::INTEGER)
            ->groupBy('a.id')
            ->orderBy('MAX(a.published_at)', 'DESC')
            ->setMaxResults($limit);

        return $qb->fetchAllAssociative();
    }

    /** @return array<int, array{id: int, published_at: string}>
     * @throws Exception
     */
    public function findAllForSitemap(): array
    {
        $qb = $this->db->createQueryBuilder();
        $qb->select('id', 'published_at')
            ->from('articles')
            ->orderBy('published_at', 'DESC');

        return $qb->fetchAllAssociative();
    }

    /** @return array<int, array>
     * @throws Exception
     */
    private function findLatestExcluding(int $excludeId, int $limit): array
    {
        $qb = $this->db->createQueryBuilder();
        $qb->select('id', 'image', 'title', 'views_count', 'published_at')
            ->from('articles')
            ->where('id != :excludeId')
            ->setParameter('excludeId', $excludeId, ParameterType::INTEGER)
            ->orderBy('published_at', 'DESC')
            ->setMaxResults($limit);

        return $qb->fetchAllAssociative();
    }
}
