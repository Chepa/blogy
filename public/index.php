<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

$container = (new \DI\ContainerBuilder())
    ->addDefinitions(dirname(__DIR__) . '/config/di.php')
    ->build();

$dispatcher = \FastRoute\simpleDispatcher(
    require dirname(__DIR__) . '/config/routes.php'
);

$httpMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';
if (($pos = strpos($uri, '?')) !== false) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo '404 Not Found';
        break;

    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo '405 Method Not Allowed';
        break;

    case \FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        $handlers = [
            'index' => fn () => $container->get(\App\Action\IndexAction::class)->run(),
            'article' => fn () => $container->get(\App\Action\ArticleAction::class)->run((int) $vars['id']),
            'category' => fn () => $container->get(\App\Action\CategoryAction::class)->run((int) $vars['id']),
            'sitemap' => fn () => $container->get(\App\Action\SitemapAction::class)->run(),
        ];

        if (isset($handlers[$handler])) {
            try {
                $handlers[$handler]();
            } catch (\Throwable $e) {
                $logger = $container->get(\Psr\Log\LoggerInterface::class);
                $logger->error('Unhandled exception: ' . $e->getMessage(), [
                    'exception' => $e,
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
                http_response_code(500);
                echo '500 Internal Server Error';
            }
            break;
        }

        http_response_code(500);
        echo '500 Internal Server Error';
        break;

    default:
        http_response_code(500);
        echo '500 Internal Server Error';
}
