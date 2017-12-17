# Router

The routing configuration file is `app/Admin/routes.php`:

```php
<?php

$router = app('merchant.router');

$router->get('/', 'HomeController@index');
```

`$router` is the instance object of the`ShaoZeMing\Merchant\Routing\Router` class and is used in the same way as `Illuminate\Routing\Router`.

`$router` add prefix to all controllers which configured in `config/merchant.php`. `$router` also adds namespaces to all configured controllers, such as the above` HomeController@index`. The `GET` request for url `http://localhost/merchant/ `will be handled by `index` method of controller `App\Admin\Controllers\HomeController`.
