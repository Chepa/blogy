<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;

final class CategoryRepository
{
    public function __construct(
        private readonly Connection $db,
        private readonly int $perPage
    ) {
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /** @return array<int, array{id: int, name: string, description: string|null, articles: array}>
     * @throws Exception
     */
    public function findAllWithLatestArticles(int $articlesPerCategory = 3): array
    {
        $limit = $articlesPerCategory;
        $sql = "
            WITH ranked AS (
                SELECT a.id, a.image, a.title, a.description, a.views_count, a.published_at, ac.category_id,
                    ROW_NUMBER() OVER (PARTITION BY ac.category_id ORDER BY a.published_at DESC) AS rn
                FROM articles a
                INNER JOIN article_category ac ON ac.article_id = a.id
            )
            SELECT r.id, r.image, r.title, r.description, r.views_count, r.published_at, r.category_id
            FROM ranked r
            WHERE r.rn <= ?
        ";
        $rows = $this->db->fetchAllAssociative($sql, [$limit], [ParameterType::INTEGER]);

        $articlesByCategory = [];
        foreach ($rows as $row) {
            $cid = (int) $row['category_id'];
            unset($row['category_id']);
            $articlesByCategory[$cid][] = $row;
        }

        $qb = $this->db->createQueryBuilder();
        $qb->select('c.id', 'c.name', 'c.description')
            ->from('categories', 'c')
            ->innerJoin('c', 'article_category', 'ac', 'ac.category_id = c.id')
            ->groupBy('c.id')
            ->orderBy('c.name', 'ASC');
        $categories = $qb->fetchAllAssociative();

        $result = [];
        foreach ($categories as $cat) {
            $cat['articles'] = $articlesByCategory[(int) $cat['id']] ?? [];
            $result[] = $cat;
        }

        return $result;
    }

    /** @return array<int>
     * @throws Exception
     */
    public function findAllIds(): array
    {
        $qb = $this->db->createQueryBuilder();
        $qb->select('id')->from('categories');
        $rows = $qb->fetchAllAssociative();
        return array_map('intval', array_column($rows, 'id'));
    }

    /**
     * @throws Exception
     */
    public function findById(int $id): ?array
    {
        $qb = $this->db->createQueryBuilder();
        $qb->select('id', 'name', 'description')
            ->from('categories')
            ->where('id = :id')
            ->setParameter('id', $id, ParameterType::INTEGER);

        $row = $qb->fetchAssociative();
        return $row !== false ? $row : null;
    }

    /** @return array{0: array[], 1: int}
     * @throws Exception
     */
    public function findArticlesByCategory(int $categoryId, string $sortBy, string $sortDir, int $page): array
    {
        $validSort = match ($sortBy) {
            'views' => 'a.views_count',
            default => 'a.published_at',
        };
        $validDir = strtoupper($sortDir) === 'ASC' ? 'ASC' : 'DESC';

        $offset = ($page - 1) * $this->perPage;

        $qb = $this->db->createQueryBuilder();
        $qb->select('COUNT(*)')
            ->from('articles', 'a')
            ->innerJoin('a', 'article_category', 'ac', 'ac.article_id = a.id AND ac.category_id = :categoryId')
            ->setParameter('categoryId', $categoryId, ParameterType::INTEGER);
        $total = (int) $qb->fetchOne();

        $qb = $this->db->createQueryBuilder();
        $qb->select('a.id', 'a.image', 'a.title', 'a.description', 'a.views_count', 'a.published_at')
            ->from('articles', 'a')
            ->innerJoin('a', 'article_category', 'ac', 'ac.article_id = a.id AND ac.category_id = :categoryId')
            ->setParameter('categoryId', $categoryId, ParameterType::INTEGER)
            ->orderBy($validSort, $validDir)
            ->setFirstResult($offset)
            ->setMaxResults($this->perPage);

        $articles = $qb->fetchAllAssociative();

        return [$articles, $total];
    }
}
