<?php

use ShaoZeMing\Merchant\Auth\Database\Administrator;

class UsersTest extends TestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = Administrator::first();

        $this->be($this->user, 'merchant');
    }

    public function testUsersIndexPage()
    {
        $this->visit('merchant/auth/users')
            ->see('Administrator');
    }

    public function testCreateUser()
    {
        $user = [
            'username'              => 'Test',
            'name'                  => 'Name',
            'password'              => '123456',
            'password_confirmation' => '123456',
        ];

        // create user
        $this->visit('merchant/auth/users/create')
            ->see('Create')
            ->submitForm('Submit', $user)
            ->seePageIs('merchant/auth/users')
            ->seeInDatabase(config('merchant.database.users_table'), ['username' => 'Test']);

        // assign role to user
        $this->visit('merchant/auth/users/2/edit')
            ->see('Edit')
            ->submitForm('Submit', ['roles' => [1]])
            ->seePageIs('merchant/auth/users')
            ->seeInDatabase(config('merchant.database.role_users_table'), ['user_id' => 2, 'role_id' => 1]);

        $this->visit('merchant/auth/logout')
            ->dontSeeIsAuthenticated('merchant')
            ->seePageIs('merchant/auth/login')
            ->submitForm('Login', ['username' => $user['username'], 'password' => $user['password']])
            ->see('dashboard')
            ->seeIsAuthenticated('merchant')
            ->seePageIs('merchant');

        $this->assertTrue($this->app['auth']->guard('merchant')->getUser()->isAdministrator());

        $this->see('<span>Users</span>')
            ->see('<span>Roles</span>')
            ->see('<span>Permission</span>')
            ->see('<span>Operation log</span>')
            ->see('<span>Menu</span>');
    }

    public function testUpdateUser()
    {
        $this->visit('merchant/auth/users/'.$this->user->id.'/edit')
            ->see('Create')
            ->submitForm('Submit', ['name' => 'test', 'roles' => [1]])
            ->seePageIs('merchant/auth/users')
            ->seeInDatabase(config('merchant.database.users_table'), ['name' => 'test']);
    }

    public function testResetPassword()
    {
        $password = 'odjwyufkglte';

        $data = [
            'password'              => $password,
            'password_confirmation' => $password,
            'roles'                 => [1],
        ];

        $this->visit('merchant/auth/users/'.$this->user->id.'/edit')
            ->see('Create')
            ->submitForm('Submit', $data)
            ->seePageIs('merchant/auth/users')
            ->visit('merchant/auth/logout')
            ->dontSeeIsAuthenticated('merchant')
            ->seePageIs('merchant/auth/login')
            ->submitForm('Login', ['username' => $this->user->username, 'password' => $password])
            ->see('dashboard')
            ->seeIsAuthenticated('merchant')
            ->seePageIs('merchant');
    }
}
