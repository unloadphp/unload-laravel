<?php declare(strict_types=1);

namespace Unload\Laravel;

class Storage
{
    public static function create(): void
    {
        if (is_dir('/tmp/storage/app')) {
            return;
        }

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

        exec('cd /tmp/storage/framework/views; cp -s /var/task/storage/framework/views/* .');
    }
}
