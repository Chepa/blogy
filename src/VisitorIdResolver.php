<?php

namespace App;

final class VisitorIdResolver
{
    private const COOKIE_NAME = 'blogy_visitor';
    private const COOKIE_LIFETIME_DAYS = 365;

    public function resolve(): string
    {
        $visitorId = $_COOKIE[self::COOKIE_NAME] ?? null;

        if ($visitorId === null || !$this->isValidVisitorId($visitorId)) {
            $visitorId = $this->generateVisitorId();
            $this->setCookie($visitorId);
        }

        return $visitorId;
    }

    private function isValidVisitorId(string $id): bool
    {
        return strlen($id) === 32 && ctype_xdigit($id);
    }

    private function generateVisitorId(): string
    {
        return bin2hex(random_bytes(16));
    }

    private function isHttps(): bool
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            return true;
        }
        $forwardedProto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? $_SERVER['HTTP_X_FORWARDED_PROTOCOL'] ?? null;

        return $forwardedProto === 'https';
    }

    private function setCookie(string $visitorId): void
    {
        if (headers_sent()) {
            return;
        }

        setcookie(
            self::COOKIE_NAME,
            $visitorId,
            [
                'expires' => time() + (self::COOKIE_LIFETIME_DAYS * 86400),
                'path' => '/',
                'samesite' => 'Lax',
                'secure' => $this->isHttps(),
                'httponly' => true,
            ]
        );
    }
}
