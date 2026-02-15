<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/config.php';
$db = App\DatabaseFactory::create($config['db']);

$categories = [
    ['name' => 'Category 1', 'description' => 'Описание категории 1. Статьи о путешествиях и образе жизни.'],
    ['name' => 'Category 2', 'description' => 'Описание категории 2. Статьи о фотографии и творчестве.'],
    ['name' => 'Category 3', 'description' => 'Описание категории 3. Статьи о кулинарии и кофе.'],
    ['name' => 'Category 4', 'description' => 'Описание категории 4. Статьи о технологиях и дизайне.'],
];

$articles = [
    [
        'image' => 'images/article-1.jpg',
        'title' => 'Lorem ipsum dolor sit amet',
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        'text' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\nDuis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
        'categories' => [1],
        'published_at' => '2019-07-16 10:00:00',
    ],
    [
        'image' => 'images/article-2.jpg',
        'title' => 'Consectetur adipiscing elit',
        'description' => 'Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        'text' => "Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.\n\nDuis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.",
        'categories' => [1, 2],
        'published_at' => '2019-07-17 14:30:00',
    ],
    [
        'image' => 'images/article-3.jpg',
        'title' => 'Sed do eiusmod tempor',
        'description' => 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.',
        'text' => "Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.\n\nDuis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident.",
        'categories' => [1, 3],
        'published_at' => '2019-07-18 09:15:00',
    ],
    [
        'image' => 'images/article-4.jpg',
        'title' => 'Ut labore et dolore magna',
        'description' => 'Ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.',
        'text' => "Ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit.\n\nIn voluptate velit esse cillum dolore eu fugiat nulla pariatur.",
        'categories' => [2],
        'published_at' => '2019-07-19 16:00:00',
    ],
    [
        'image' => 'images/article-5.jpg',
        'title' => 'Quis nostrud exercitation',
        'description' => 'Quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
        'text' => "Quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.\n\nExcepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
        'categories' => [2, 4],
        'published_at' => '2019-07-20 11:45:00',
    ],
    [
        'image' => 'images/article-6.jpg',
        'title' => 'Duis aute irure dolor',
        'description' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat.',
        'text' => "Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident.\n\nSunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
        'categories' => [3],
        'published_at' => '2019-07-21 08:30:00',
    ],
    [
        'image' => 'images/article-7.jpg',
        'title' => 'In voluptate velit esse',
        'description' => 'In voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat.',
        'text' => "In voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
        'categories' => [3, 4],
        'published_at' => '2019-07-22 13:20:00',
    ],
    [
        'image' => 'images/article-8.jpg',
        'title' => 'Excepteur sint occaecat',
        'description' => 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit.',
        'text' => "Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit.\n\nSed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.",
        'categories' => [4],
        'published_at' => '2019-07-23 17:00:00',
    ],
    [
        'image' => 'images/article-9.jpg',
        'title' => 'Sunt in culpa qui officia',
        'description' => 'Sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet.',
        'text' => "Sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.\n\nUt enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
        'categories' => [1, 2, 4],
        'published_at' => '2019-07-24 10:10:00',
    ],
];

try {
    $db->executeStatement('SET FOREIGN_KEY_CHECKS = 0');
    $db->executeStatement('DELETE FROM article_category');
    $db->executeStatement('DELETE FROM articles');
    $db->executeStatement('DELETE FROM categories');
    $db->executeStatement('ALTER TABLE categories AUTO_INCREMENT = 1');
    $db->executeStatement('ALTER TABLE articles AUTO_INCREMENT = 1');
    $db->executeStatement('SET FOREIGN_KEY_CHECKS = 1');

    $db->beginTransaction();

    foreach ($categories as $cat) {
        $db->executeStatement(
            'INSERT INTO categories (name, description) VALUES (?, ?)',
            [$cat['name'], $cat['description']]
        );
    }

    foreach ($articles as $article) {
        $viewsCount = rand(0, 500);
        $db->executeStatement(
            'INSERT INTO articles (image, title, description, text, views_count, published_at) VALUES (?, ?, ?, ?, ?, ?)',
            [
                $article['image'],
                $article['title'],
                $article['description'],
                $article['text'],
                $viewsCount,
                $article['published_at'],
            ]
        );
        $articleId = (int) $db->lastInsertId();

        foreach ($article['categories'] as $catId) {
            $db->executeStatement(
                'INSERT INTO article_category (article_id, category_id) VALUES (?, ?)',
                [$articleId, $catId]
            );
        }
    }

    $db->commit();
    echo "Сидер выполнен успешно. Добавлено " . count($categories) . " категорий и " . count($articles) . " статей.\n";
} catch (Throwable $e) {
    if ($db->isTransactionActive()) {
        $db->rollBack();
    }
    throw $e;
}
