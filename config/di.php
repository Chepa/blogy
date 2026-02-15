<?php

declare(strict_types=1);

use App\Action\ArticleAction;
use App\Action\CategoryAction;
use App\VisitorIdResolver;
use App\Action\IndexAction;
use App\Action\SitemapAction;
use App\DatabaseFactory;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\SmartyFactory;
use App\SitemapGenerator;
use App\UrlHelper;
use Doctrine\DBAL\Connection;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use function DI\create;
use function DI\get;
use function DI\factory;

return [
    LoggerInterface::class => factory(function () {
        $logDir = dirname(__DIR__) . '/var/log';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        $logger = new Logger('app');
        $logger->pushHandler(new StreamHandler($logDir . '/app.log', Level::Warning));
        return $logger;
    }),

    'config' => factory(function () {
        static $config = null;
        if ($config === null) {
            $config = require __DIR__ . '/config.php';
        }
        return $config;
    }),

    Connection::class => factory(function ($container) {
        $config = $container->get('config');
        return DatabaseFactory::create($config['db']);
    }),

    Smarty::class => factory(function ($container) {
        $config = $container->get('config');
        $baseUrl = rtrim($config['base_url'], '/');
        $basePath = dirname(__DIR__);
        $smarty = SmartyFactory::create(
            $basePath . '/templates',
            $basePath . '/var/compile',
            $basePath . '/templates/config',
            $basePath . '/var/cache',
            $container->get(UrlHelper::class)
        );
        $smarty->assign('baseUrl', $baseUrl);
        return $smarty;
    }),

    UrlHelper::class => factory(function ($container) {
        $config = $container->get('config');
        return new UrlHelper(rtrim($config['base_url'], '/'));
    }),

    ArticleRepository::class => create()->constructor(get(Connection::class)),
    CategoryRepository::class => factory(function ($container) {
        $config = $container->get('config');
        return new CategoryRepository($container->get(Connection::class), (int) $config['per_page']);
    }),

    IndexAction::class => create()->constructor(
        get(CategoryRepository::class),
        get(Smarty::class),
        get(UrlHelper::class)
    ),
    VisitorIdResolver::class => create(),

    ArticleAction::class => create()->constructor(
        get(ArticleRepository::class),
        get(Smarty::class),
        get(UrlHelper::class),
        get(VisitorIdResolver::class)
    ),
    CategoryAction::class => create()->constructor(
        get(CategoryRepository::class),
        get(Smarty::class),
        get(UrlHelper::class)
    ),
    SitemapGenerator::class => create()->constructor(
        get(ArticleRepository::class),
        get(CategoryRepository::class),
        get(UrlHelper::class)
    ),
    SitemapAction::class => create()->constructor(get(SitemapGenerator::class)),
];
