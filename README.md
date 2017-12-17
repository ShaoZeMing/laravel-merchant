laravel-merchant
=====

[![Build Status](https://travis-ci.org/z-song/laravel-merchant.svg?branch=master)](https://travis-ci.org/z-song/laravel-merchant)
[![StyleCI](https://styleci.io/repos/48796179/shield)](https://styleci.io/repos/48796179)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/z-song/laravel-merchant/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/z-song/laravel-merchant/?branch=master)
[![Packagist](https://img.shields.io/packagist/l/encore/laravel-merchant.svg?maxAge=2592000)](https://packagist.org/packages/encore/laravel-merchant)
[![Total Downloads](https://img.shields.io/packagist/dt/encore/laravel-merchant.svg?style=flat-square)](https://packagist.org/packages/encore/laravel-merchant)
[![Awesome Laravel](https://img.shields.io/badge/Awesome-Laravel-brightgreen.svg)](https://github.com/z-song/laravel-merchant)

`laravel-merchant` is merchantistrative interface builder for laravel which can help you build CRUD backends just with few lines of code.

[Demo](http://laravel-merchant.org/demo)

Inspired by [SleepingOwlAdmin](https://github.com/sleeping-owl/merchant) and [rapyd-laravel](https://github.com/zofe/rapyd-laravel).

[Documentation](http://laravel-merchant.org/docs) | [中文文档](http://laravel-merchant.org/docs/#/zh/)

[Extensions](https://github.com/laravel-merchant-extensions)

Screenshots
------------

![laravel-merchant](https://cloud.githubusercontent.com/assets/1479100/19625297/3b3deb64-9947-11e6-807c-cffa999004be.jpg)

Installation
------------

> This package requires PHP 7+ and Laravel 5.5, for old versions please refer to [1.4](http://laravel-merchant.org/docs/v1.4/#/) 

First, install laravel 5.5, and make sure that the database connection settings are correct.

```
composer require encore/laravel-merchant
```

Then run these commands to publish assets and config：

```
php artisan vendor:publish --provider="ShaoZeMing\Merchant\AdminServiceProvider"
```
After run command you can find config file in `config/merchant.php`, in this file you can change the install directory,db connection or table names.

At last run following command to finish install. 
```
php artisan merchant:install
```

Open `http://localhost/merchant/` in browser,use username `merchant` and password `merchant` to login.

Default Settings
------------
The file in `config/merchant.php` contains an array of settings, you can find the default settings in there.

Other
------------
`laravel-merchant` based on following plugins or services:

+ [Laravel](https://laravel.com/)
+ [AdminLTE](https://almsaeedstudio.com/)
+ [Datetimepicker](http://eonasdan.github.io/bootstrap-datetimepicker/)
+ [font-awesome](http://fontawesome.io)
+ [moment](http://momentjs.com/)
+ [Google map](https://www.google.com/maps)
+ [Tencent map](http://lbs.qq.com/)
+ [bootstrap-fileinput](https://github.com/kartik-v/bootstrap-fileinput)
+ [jquery-pjax](https://github.com/defunkt/jquery-pjax)
+ [Nestable](http://dbushell.github.io/Nestable/)
+ [toastr](http://codeseven.github.io/toastr/)
+ [X-editable](http://github.com/vitalets/x-editable)
+ [bootstrap-number-input](https://github.com/wpic/bootstrap-number-input)
+ [fontawesome-iconpicker](https://github.com/itsjavi/fontawesome-iconpicker)

License
------------
`laravel-merchant` is licensed under [The MIT License (MIT)](LICENSE).
