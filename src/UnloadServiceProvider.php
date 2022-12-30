<?php declare(strict_types=1);

namespace Unload\Laravel;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class UnloadServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../runtime/worker.php' => $this->app->basePath('worker.php'),
                __DIR__ . '/../runtime/cli.php' => $this->app->basePath('cli.php'),
                __DIR__ . '/../runtime/web.php' => $this->app->basePath('web.php'),
            ], 'unload');
        }
    }

    public function register(): void
    {
        $isRunningInLambda = isset($_SERVER['LAMBDA_TASK_ROOT']);

        // Laravel Mix URL for assets stored on S3
        $mixAssetUrl = $_SERVER['MIX_ASSET_URL'] ?? null;
        if ($mixAssetUrl) {
            Config::set('app.mix_url', $mixAssetUrl);
        }

        // The rest below is specific to AWS Lambda
        if (! $isRunningInLambda) {
            return;
        }

        // We change Laravel's default log destination to stderr
        $logDriver = Config::get('logging.default');
        if ($logDriver === 'stack') {
            Config::set('logging.default', 'stderr');
        }

        // Store compiled views in `/tmp` because they are generated at runtime
        // and `/tmp` is the only writable directory on Lambda
        Config::set('view.compiled', '/tmp/storage/framework/views');

        // Allow all proxies because AWS Lambda runs behind API Gateway
        // See https://github.com/fideloper/TrustedProxy/issues/115#issuecomment-503596621
        Config::set('trustedproxy.proxies', ['0.0.0.0/0', '2000:0:0:0:0:0:0:0/3']);

        // Sessions cannot be stored to files, so we use cookies by default instead
        $sessionDriver = Config::get('session.driver');
        if ($sessionDriver === 'file') {
            Config::set('session.driver', 'cookie');
        }

        // The native Laravel storage directory is read-only, we move the cache to /tmp
        // to avoid errors. If you want to actively use the cache, it will be best to use
        // the dynamodb driver instead.
        Config::set('cache.stores.file.path', '/tmp/storage/framework/cache');
    }
}
