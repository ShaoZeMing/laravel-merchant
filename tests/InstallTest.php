<?php

/**
 * Created by PhpStorm.
 * User: encore
 * Date: 16/11/20
 * Time: 下午6:44.
 */
class InstallTest extends TestCase
{
    public function testInstalledDirectories()
    {
        $this->assertFileExists(merchant_path());

        $this->assertFileExists(merchant_path('Controllers'));

        $this->assertFileExists(merchant_path('routes.php'));

        $this->assertFileExists(merchant_path('bootstrap.php'));

        $this->assertFileExists(merchant_path('Controllers/HomeController.php'));

        $this->assertFileExists(merchant_path('Controllers/ExampleController.php'));

        $this->assertFileExists(config_path('merchant.php'));

        $this->assertFileExists(public_path('vendor/laravel-merchant'));
    }
}
