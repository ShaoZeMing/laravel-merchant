<?php

use ShaoZeMing\Merchant\Auth\Database\Administrator;

class IndexTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->be(Administrator::first(), 'merchant');
    }

    public function testIndex()
    {
        $this->visit('merchant/')
            ->see('Dashboard')
            ->see('Description...')

            ->see('Environment')
            ->see('PHP version')
            ->see('Laravel version')

            ->see('Available extensions')
            ->seeLink('laravel-merchant-ext/helpers', 'https://github.com/laravel-merchant-extensions/helpers')
            ->seeLink('laravel-merchant-ext/backup', 'https://github.com/laravel-merchant-extensions/backup')
            ->seeLink('laravel-merchant-ext/media-manager', 'https://github.com/laravel-merchant-extensions/media-manager')

            ->see('Dependencies')
            ->see('php')
            ->see('>=7.0.0')
            ->see('laravel/framework');
    }

    public function testClickMenu()
    {
        $this->visit('merchant/')
            ->click('Users')
            ->seePageis('merchant/auth/users')
            ->click('Roles')
            ->seePageis('merchant/auth/roles')
            ->click('Permission')
            ->seePageis('merchant/auth/permissions')
            ->click('Menu')
            ->seePageis('merchant/auth/menu')
            ->click('Operation log')
            ->seePageis('merchant/auth/logs');
    }
}
