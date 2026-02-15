<?php

namespace App;

final class UrlHelper
{
    public function __construct(private readonly string $baseUrl)
    {
    }

    public function home(): string
    {
        return $this->baseUrl . '/';
    }

    public function article(int $id): string
    {
        return rtrim($this->baseUrl, '/') . '/article/' . $id;
    }

    public function category(int $id, ?string $sort = null, ?string $dir = null, ?int $page = null): string
    {
        $url = rtrim($this->baseUrl, '/') . '/category/' . $id;
        $params = [];
        if ($sort !== null && $dir !== null) {
            $params['sort'] = $sort;
            $params['dir'] = $dir;
        }
        if ($page !== null && $page > 1) {
            $params['page'] = (string) $page;
        }
        if ($params !== []) {
            $url .= '?' . http_build_query($params);
        }
        return $url;
    }

    public function absoluteImage(string $image): string
    {
        if ($image === '') {
            return '';
        }
        if (str_starts_with($image, 'http')) {
            return $image;
        }
        return $this->baseUrl . '/' . ltrim($image, '/');
    }

    public function redirectTo(string $url): void
    {
        if (!headers_sent()) {
            header('Location: ' . $url);
            exit;
        }
        echo '<!DOCTYPE html><html><head><meta http-equiv="Refresh" content="0;url=' . htmlspecialchars($url) . '"></head><body><a href="' . htmlspecialchars($url) . '">Перейти</a></body></html>';
        exit;
    }
}
