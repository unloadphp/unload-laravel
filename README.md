# Laravel bridge for AWS Lambda runtime with [Unload](https://unload.sh/).

This package provides the following benefits:

- it configures Laravel for AWS Lambda environment
- it provides a bridge to run Laravel Queues worker on AWS Lambda

This is extension of default [bref laravel bridge](https://github.com/brefphp/laravel-bridge) package. 

## Installation

```bash
composer require unload/unload-laravel --update-with-dependencies
```

The application is now ready to be deployed:

```bash
unload deploy
```

## Documentation

For full documentation, visit [unload.sh](https://unload.sh/).

## License

This is an open-source software licensed under the MIT license.
