<?php

class AuthTest extends TestCase
{
    public function testLoginPage()
    {
        $this->visit('merchant/auth/login')
            ->see('login');
    }

    public function testVisitWithoutLogin()
    {
        $this->visit('merchant')
            ->dontSeeIsAuthenticated('merchant')
            ->seePageIs('merchant/auth/login');
    }

    public function testLogin()
    {
        $credentials = ['username' => 'merchant', 'password' => 'merchant'];

        $this->visit('merchant/auth/login')
            ->see('login')
            ->submitForm('Login', $credentials)
            ->see('dashboard')
            ->seeCredentials($credentials, 'merchant')
            ->seeIsAuthenticated('merchant')
            ->seePageIs('merchant')
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

        $this
            ->see('<span>Admin</span>')
            ->see('<span>Users</span>')
            ->see('<span>Roles</span>')
            ->see('<span>Permission</span>')
            ->see('<span>Operation log</span>')
            ->see('<span>Menu</span>');
    }

    public function testLogout()
    {
        $this->visit('merchant/auth/logout')
            ->seePageIs('merchant/auth/login')
            ->dontSeeIsAuthenticated('merchant');
    }
}
