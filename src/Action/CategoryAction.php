<?php

namespace App\Action;

use App\Repository\CategoryRepository;
use App\UrlHelper;
use Doctrine\DBAL\Exception;
use Smarty;
use SmartyException;

final class CategoryAction
{
    public function __construct(
        private readonly CategoryRepository $categoryRepo,
        private readonly Smarty $smarty,
        private readonly UrlHelper $urlHelper
    ) {
    }

    /**
     * @throws SmartyException
     * @throws Exception
     */
    public function run(int $categoryId): void
    {
        if ($categoryId <= 0) {
            $this->urlHelper->redirectTo($this->urlHelper->home());
            return;
        }

        $sortBy = $_GET['sort'] ?? 'date';
        $sortDir = $_GET['dir'] ?? 'desc';
        if (!in_array($sortBy, ['date', 'views'], true)) {
            $sortBy = 'date';
        }
        if (!in_array(strtolower($sortDir), ['asc', 'desc'], true)) {
            $sortDir = 'desc';
        }
        $page = max(1, (int) ($_GET['page'] ?? 1));

        $category = $this->categoryRepo->findById($categoryId);
        if (!$category) {
            $this->urlHelper->redirectTo($this->urlHelper->home());
            return;
        }

        $perPage = $this->categoryRepo->getPerPage();
        [$articles, $total] = $this->categoryRepo->findArticlesByCategory($categoryId, $sortBy, $sortDir, $page);
        $totalPages = (int) ceil($total / $perPage);

        if ($page > 1 && ($totalPages === 0 || $page > $totalPages)) {
            $this->urlHelper->redirectTo($this->urlHelper->category($categoryId, $sortBy, $sortDir, 1));
        }

        $canonicalUrl = $this->urlHelper->category($categoryId, $sortBy, $sortDir, $page > 1 ? $page : null);

        $this->smarty->assign('category', $category);
        $this->smarty->assign('articles', $articles);
        $this->smarty->assign('totalPages', $totalPages);
        $this->smarty->assign('currentPage', $page);
        $this->smarty->assign('sortBy', $sortBy);
        $this->smarty->assign('sortDir', $sortDir);
        $this->smarty->assign('pageTitle', $category['name'] . ' - Blogy.');
        $this->smarty->assign('pageDescription', $category['description'] ?? '');
        $this->smarty->assign('canonicalUrl', $canonicalUrl);
        $this->smarty->display('category.tpl');
    }
}
