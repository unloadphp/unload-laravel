<?php declare(strict_types=1);

namespace Unload\Laravel;

class Storage
{
    public static function create(): void
    {
        array_map(static function ($folder) {
            if (! is_dir($folder)) {
                mkdir($folder, 0755, true);
            }
        }, [
            '/tmp/storage/app',
            '/tmp/storage/logs',
            '/tmp/storage/framework/cache',
            '/tmp/storage/bootstrap/cache',
            '/tmp/storage/framework/views',
        ]);
    }
}
