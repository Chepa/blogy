<?php

declare(strict_types=1);

namespace App;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\DBAL\Exception;

final class SitemapGenerator
{
    public function __construct(
        private readonly ArticleRepository $articleRepo,
        private readonly CategoryRepository $categoryRepo,
        private readonly UrlHelper $urlHelper
    ) {
    }

    /**
     * @throws Exception
     */
    public function generate(): string
    {
        $articles = $this->articleRepo->findAllForSitemap();
        $categoryIds = $this->categoryRepo->findAllIds();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $xml .= "  <url><loc>" . htmlspecialchars($this->urlHelper->home()) . "</loc><changefreq>daily</changefreq><priority>1.0</priority></url>\n";

        foreach ($categoryIds as $id) {
            $loc = $this->urlHelper->category($id);
            $xml .= "  <url><loc>" . htmlspecialchars($loc) . "</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>\n";
        }

        foreach ($articles as $row) {
            $loc = $this->urlHelper->article((int) $row['id']);
            $lastmod = '';
            if (!empty($row['published_at'])) {
                $ts = strtotime($row['published_at']);
                if ($ts !== false) {
                    $lastmod = '<lastmod>' . date('Y-m-d', $ts) . '</lastmod>';
                }
            }
            $xml .= "  <url><loc>" . htmlspecialchars($loc) . "</loc>{$lastmod}<changefreq>monthly</changefreq><priority>0.6</priority></url>\n";
        }

        $xml .= '</urlset>';
        return $xml;
    }
}
