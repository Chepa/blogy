<?php

use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    $r->addRoute('GET', '/', 'index');
    $r->addRoute('GET', '/article/{id:\d+}', 'article');
    $r->addRoute('GET', '/category/{id:\d+}', 'category');
    $r->addRoute('GET', '/sitemap.xml', 'sitemap');

    // Backwards compatibility: /index.php → главная
    $r->addRoute('GET', '/index.php', 'index');
};
