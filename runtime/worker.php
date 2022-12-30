<?php declare(strict_types=1);

use Unload\Laravel\LaravelSqsHandler;
use Illuminate\Foundation\Application;

require __DIR__ . '/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Storage Configuration
|--------------------------------------------------------------------------
|
| On start up we need to create the application temporary storage since
| in lambda environment application folder is read only.
|
*/

Unload\Laravel\Storage::create();

/** @var Application $app */
$app = require __DIR__ . '/bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Cache Configuration
|--------------------------------------------------------------------------
|
| Once application is booted we need to cache the application configuration
| to get a speed boost on subsequent requests. The file will be generated
| and loaded only once for the same lambda instance.
|
*/

Unload\Laravel\Environment::boot($app);

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

return $app->makeWith(LaravelSqsHandler::class, [
    'connection' => 'sqs', // this is the Laravel Queue connection
    'queue' => getenv('SQS_WORKER_QUEUE'),
]);
