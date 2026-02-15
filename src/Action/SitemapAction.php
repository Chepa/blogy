<?php

namespace App\Action;

use App\SitemapGenerator;
use Doctrine\DBAL\Exception;

final class SitemapAction
{
    public function __construct(
        private readonly SitemapGenerator $sitemapGenerator
    ) {
    }

    /**
     * @throws Exception
     */
    public function run(): void
    {
        header('Content-Type: application/xml; charset=utf-8');
        echo $this->sitemapGenerator->generate();
    }
}
