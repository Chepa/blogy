<?php

namespace App\Action;

use App\Repository\CategoryRepository;
use App\UrlHelper;
use Doctrine\DBAL\Exception;
use Smarty;
use SmartyException;

final class IndexAction
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
    public function run(): void
    {
        $categories = $this->categoryRepo->findAllWithLatestArticles();

        $this->smarty->assign('categories', $categories);
        $this->smarty->assign('pageTitle', 'Blogy. - Home');
        $this->smarty->assign('pageDescription', 'Blogy. — блог о путешествиях, фотографии, кулинарии и технологиях.');
        $this->smarty->assign('canonicalUrl', $this->urlHelper->home());
        $this->smarty->display('index.tpl');
    }
}
