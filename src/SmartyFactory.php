<?php

namespace App;

use Smarty;
use SmartyException;

final class SmartyFactory
{
    /**
     * @throws SmartyException
     */
    public static function create(
        string $templatesDir,
        string $compileDir,
        string $configDir,
        string $cacheDir,
        ?UrlHelper $urlHelper = null
    ): Smarty {
        $smarty = new Smarty();
        $smarty->setTemplateDir($templatesDir);
        $smarty->setCompileDir($compileDir);
        $smarty->setConfigDir($configDir);
        $smarty->setCacheDir($cacheDir);
        $smarty->registerPlugin('modifier', 'date_format', fn($date, $format = 'F j, Y') =>
            $date ? date($format, strtotime($date)) : ''
        );

        if ($urlHelper !== null) {
            $smarty->registerPlugin('modifier', 'absolute_image', fn (?string $image): string => $urlHelper->absoluteImage($image ?? ''));
            $smarty->registerPlugin('function', 'url_article', function (array $params) use ($urlHelper): string {
                $id = (int) ($params['id'] ?? 0);
                return $urlHelper->article($id);
            });
            $smarty->registerPlugin('function', 'url_category', function (array $params) use ($urlHelper): string {
                $id = (int) ($params['id'] ?? 0);
                $sort = $params['sort'] ?? null;
                $dir = $params['dir'] ?? null;
                $page = isset($params['page']) ? (int) $params['page'] : null;
                return $urlHelper->category($id, $sort, $dir, $page);
            });
        }

        return $smarty;
    }
}
