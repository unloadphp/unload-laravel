<?php declare(strict_types=1);

namespace Unload\Laravel;

use App\Console\Kernel;
use Dotenv\Dotenv;
use Dotenv\Loader\Loader;
use Dotenv\Parser\Parser;
use Dotenv\Repository\Adapter\EnvConstAdapter;
use Dotenv\Repository\RepositoryBuilder;
use Dotenv\Store\StringStore;
use Illuminate\Encryption\Encrypter;

class Environment
{
    public static function boot($app): void
    {
        if (!file_exists($_ENV['APP_CONFIG_CACHE'])) {
            $stderr = fopen('php://stderr', 'w');
            fwrite($stderr, 'Preparing to extract encrypted environment to runtime'.PHP_EOL);

            $encrypter = new Encrypter(base64_decode(getenv('CI_SECRET')), 'aes-256-cbc');
            $repository = RepositoryBuilder::createWithNoAdapters()->addAdapter(EnvConstAdapter::class)->make();
            $env = $encrypter->decrypt(file_get_contents(base_path('.env.unload')));
            $phpdotenv = new Dotenv(new StringStore($env), new Parser(), new Loader(), $repository);
            $phpdotenv->load();

            $app->make(Kernel::class)->call('config:cache');

            fwrite($stderr, 'Environment has been extracted and cached'.PHP_EOL);
        }
    }
}
