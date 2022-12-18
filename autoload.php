<?php

use Unload\Laravel\Environment;
use Unload\Laravel\Storage;

ini_set('display_errors', '1');

error_reporting(E_ALL);

if (! file_exists('/tmp/opcache')) {
    mkdir('/tmp/opcache');
}

require __DIR__.'/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Storage Configuration
|--------------------------------------------------------------------------
|
| On start up we need to create the application temporary storage since
| in lambda environment application folder is read only.
|
*/

Storage::create();

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/bootstrap/app.php';

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

Environment::boot($app);
