<?php

namespace App\Action;

use App\Repository\ArticleRepository;
use App\UrlHelper;
use App\VisitorIdResolver;
use Doctrine\DBAL\Exception;
use Smarty;

final class ArticleAction
{
    public function __construct(
        private readonly ArticleRepository $articleRepo,
        private readonly Smarty $smarty,
        private readonly UrlHelper $urlHelper,
        private readonly VisitorIdResolver $visitorIdResolver
    ) {
    }

    /**
     * @throws \SmartyException
     * @throws Exception
     */
    public function run(int $articleId): void
    {
        if ($articleId <= 0) {
            $this->urlHelper->redirectTo($this->urlHelper->home());
            return;
        }

        $article = $this->articleRepo->findById($articleId);
        if (!$article) {
            $this->urlHelper->redirectTo($this->urlHelper->home());
            return;
        }

        $visitorId = $this->visitorIdResolver->resolve();
        $viewsCount = $this->articleRepo->incrementViewsIfUnique($articleId, $visitorId);

        $categoryIds = $this->articleRepo->getCategoryIdsForArticle($articleId);
        $similar = $this->articleRepo->findSimilar($articleId, $categoryIds, 3);

        $ogImage = $this->urlHelper->absoluteImage($article['image'] ?? '');

        $this->smarty->assign('article', $article);
        $this->smarty->assign('viewsCount', $viewsCount);
        $this->smarty->assign('similar', $similar);
        $this->smarty->assign('pageTitle', $article['title'] . ' - Blogy.');
        $this->smarty->assign('pageDescription', $article['description'] ?? '');
        $this->smarty->assign('canonicalUrl', $this->urlHelper->article($articleId));
        $this->smarty->assign('ogImage', $ogImage);
        $this->smarty->assign('ogType', 'article');
        $this->smarty->assign('pageSchema', json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $article['title'],
            'description' => $article['description'] ?? '',
            'image' => $ogImage,
            'datePublished' => $article['published_at'],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG));
        $this->smarty->display('article.tpl');
    }
}
