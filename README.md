## LaraPush

this is a push library for laravel

## Version Compatibility

| PushProvider     | Enable |  Version  | Support |
|:-------:|:-------:|:-----:|:-------:|
| 极光 |  yes |  ^3.5   | [极光](https://www.jiguang.cn/) |
| more provider | coming soon | | |

Installation
------------

Install using composer:

```bash
composer require kubill/larapush
```

Laravel version < 5.5 (optional)
------------------

Add the service provider in `config/app.php`:

```php
\Kubill\LaraPush\PusherServiceProvider::class,
```

And add the Pusher alias to `config/app.php`:

```php
'Pusher' => \Kubill\LaraPush\Facades\Pusher::class,
```

Then run these commands to publish config：

```bash
php artisan vendor:publish --provider="Kubill\LaraPush\PusherServiceProvider"
```

Basic Usage
-----------

use the `Pusher` Facade:

```php
use \Kubill\LaraPush\Facades\Pusher;

Pusher::send('hello world', array('key' => 'value'));
```

## License

LaraPush is licensed under [The MIT License (MIT)](LICENSE).
