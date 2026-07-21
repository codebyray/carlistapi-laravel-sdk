<?php

namespace CodebyRay\CarListApi;

use Composer\InstalledVersions;
use Throwable;

final class Package
{
    public const NAME = 'codebyray/carlistapi-laravel-sdk';

    public static function version(): string
    {
        if (! class_exists(InstalledVersions::class)) {
            return 'dev';
        }

        try {
            $version = InstalledVersions::getPrettyVersion(self::NAME)
                ?? InstalledVersions::getVersion(self::NAME);
        } catch (Throwable) {
            return 'dev';
        }

        if (! is_string($version) || trim($version) === '') {
            return 'dev';
        }

        return ltrim(trim($version), 'v');
    }

    public static function userAgent(): string
    {
        return self::NAME.'/'.self::version();
    }
}
