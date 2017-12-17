<?php

use ShaoZeMing\Merchant\Auth\Database\Administrator;
use ShaoZeMing\Merchant\Auth\Database\Permission;
use ShaoZeMing\Merchant\Auth\Database\Role;

class PermissionsTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->be(Administrator::first(), 'merchant');
    }

    public function testPermissionsIndex()
    {
        $this->assertTrue(Administrator::first()->isAdministrator());

        $this->visit('merchant/auth/permissions')
            ->see('Permissions');
    }

    public function testAddAndDeletePermissions()
    {
        $this->visit('merchant/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-edit', 'name' => 'Can edit', 'http_path' => 'users/1/edit', 'http_method' => ['GET']])
            ->seePageIs('merchant/auth/permissions')
            ->visit('merchant/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-delete', 'name' => 'Can delete', 'http_path' => 'users/1', 'http_method' => ['DELETE']])
            ->seePageIs('merchant/auth/permissions')
            ->seeInDatabase(config('merchant.database.permissions_table'), ['slug' => 'can-edit', 'name' => 'Can edit', 'http_path' => 'users/1/edit', 'http_method' => 'GET'])
            ->seeInDatabase(config('merchant.database.permissions_table'), ['slug' => 'can-delete', 'name' => 'Can delete', 'http_path' => 'users/1', 'http_method' => 'DELETE'])
            ->assertEquals(7, Permission::count());

        $this->assertTrue(Administrator::first()->can('can-edit'));
        $this->assertTrue(Administrator::first()->can('can-delete'));

        $this->delete('merchant/auth/permissions/6')
            ->assertEquals(6, Permission::count());

        $this->delete('merchant/auth/permissions/7')
            ->assertEquals(5, Permission::count());
    }

    public function testAddPermissionToRole()
    {
        $this->visit('merchant/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-create', 'name' => 'Can Create', 'http_path' => 'users/create', 'http_method' => ['GET']])
            ->seePageIs('merchant/auth/permissions');

        $this->assertEquals(6, Permission::count());

        $this->visit('merchant/auth/roles/1/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => [1]])
            ->seePageIs('merchant/auth/roles')
            ->seeInDatabase(config('merchant.database.role_permissions_table'), ['role_id' => 1, 'permission_id' => 1]);
    }

    public function testAddPermissionToUser()
    {
        $this->visit('merchant/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-create', 'name' => 'Can Create', 'http_path' => 'users/create', 'http_method' => ['GET']])
            ->seePageIs('merchant/auth/permissions');

        $this->assertEquals(6, Permission::count());

        $this->visit('merchant/auth/users/1/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => [1], 'roles' => [1]])
            ->seePageIs('merchant/auth/users')
            ->seeInDatabase(config('merchant.database.user_permissions_table'), ['user_id' => 1, 'permission_id' => 1])
            ->seeInDatabase(config('merchant.database.role_users_table'), ['user_id' => 1, 'role_id' => 1]);
    }

    public function testAddUserAndAssignPermission()
    {
        $user = [
            'username'              => 'Test',
            'name'                  => 'Name',
            'password'              => '123456',
            'password_confirmation' => '123456',
        ];

        $this->visit('merchant/auth/users/create')
            ->see('Create')
            ->submitForm('Submit', $user)
            ->seePageIs('merchant/auth/users')
            ->seeInDatabase(config('merchant.database.users_table'), ['username' => 'Test']);

        $this->assertFalse(Administrator::find(2)->isAdministrator());

        $this->visit('merchant/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-update', 'name' => 'Can Update', 'http_path' => 'users/*/edit', 'http_method' => ['GET']])
            ->seePageIs('merchant/auth/permissions');

        $this->assertEquals(6, Permission::count());

        $this->visit('merchant/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-remove', 'name' => 'Can Remove', 'http_path' => 'users/*', 'http_method' => ['DELETE']])
            ->seePageIs('merchant/auth/permissions');

        $this->assertEquals(7, Permission::count());

        $this->visit('merchant/auth/users/2/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => [6]])
            ->seePageIs('merchant/auth/users')
            ->seeInDatabase(config('merchant.database.user_permissions_table'), ['user_id' => 2, 'permission_id' => 6]);

        $this->assertTrue(Administrator::find(2)->can('can-update'));
        $this->assertTrue(Administrator::find(2)->cannot('can-remove'));

        $this->visit('merchant/auth/users/2/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => [7]])
            ->seePageIs('merchant/auth/users')
            ->seeInDatabase(config('merchant.database.user_permissions_table'), ['user_id' => 2, 'permission_id' => 7]);

        $this->assertTrue(Administrator::find(2)->can('can-remove'));

        $this->visit('merchant/auth/users/2/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => []])
            ->seePageIs('merchant/auth/users')
            ->missingFromDatabase(config('merchant.database.user_permissions_table'), ['user_id' => 2, 'permission_id' => 6])
            ->missingFromDatabase(config('merchant.database.user_permissions_table'), ['user_id' => 2, 'permission_id' => 7]);

        $this->assertTrue(Administrator::find(2)->cannot('can-update'));
        $this->assertTrue(Administrator::find(2)->cannot('can-remove'));
    }

    public function testPermissionThroughRole()
    {
        $user = [
            'username'              => 'Test',
            'name'                  => 'Name',
            'password'              => '123456',
            'password_confirmation' => '123456',
        ];

        // 1.add a user
        $this->visit('merchant/auth/users/create')
            ->see('Create')
            ->submitForm('Submit', $user)
            ->seePageIs('merchant/auth/users')
            ->seeInDatabase(config('merchant.database.users_table'), ['username' => 'Test']);

        $this->assertFalse(Administrator::find(2)->isAdministrator());

        // 2.add a role
        $this->visit('merchant/auth/roles/create')
            ->see('Roles')
            ->submitForm('Submit', ['slug' => 'developer', 'name' => 'Developer...'])
            ->seePageIs('merchant/auth/roles')
            ->seeInDatabase(config('merchant.database.roles_table'), ['slug' => 'developer', 'name' => 'Developer...'])
            ->assertEquals(2, Role::count());

        $this->assertFalse(Administrator::find(2)->isRole('developer'));

        // 3.assign role to user
        $this->visit('merchant/auth/users/2/edit')
            ->see('Edit')
            ->submitForm('Submit', ['roles' => [2]])
            ->seePageIs('merchant/auth/users')
            ->seeInDatabase(config('merchant.database.role_users_table'), ['user_id' => 2, 'role_id' => 2]);

        $this->assertTrue(Administrator::find(2)->isRole('developer'));

        //  4.add a permission
        $this->visit('merchant/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-remove', 'name' => 'Can Remove', 'http_path' => 'users/*', 'http_method' => ['DELETE']])
            ->seePageIs('merchant/auth/permissions');

        $this->assertEquals(6, Permission::count());

        $this->assertTrue(Administrator::find(2)->cannot('can-remove'));

        // 5.assign permission to role
        $this->visit('merchant/auth/roles/2/edit')
            ->see('Edit')
            ->submitForm('Submit', ['permissions' => [6]])
            ->seePageIs('merchant/auth/roles')
            ->seeInDatabase(config('merchant.database.role_permissions_table'), ['role_id' => 2, 'permission_id' => 6]);

        $this->assertTrue(Administrator::find(2)->can('can-remove'));
    }

    public function testEditPermission()
    {
        $this->visit('merchant/auth/permissions/create')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-edit', 'name' => 'Can edit', 'http_path' => 'users/1/edit', 'http_method' => ['GET']])
            ->seePageIs('merchant/auth/permissions')
            ->seeInDatabase(config('merchant.database.permissions_table'), ['slug' => 'can-edit'])
            ->seeInDatabase(config('merchant.database.permissions_table'), ['name' => 'Can edit'])
            ->assertEquals(6, Permission::count());

        $this->visit('merchant/auth/permissions/1/edit')
            ->see('Permissions')
            ->submitForm('Submit', ['slug' => 'can-delete'])
            ->seePageIs('merchant/auth/permissions')
            ->seeInDatabase(config('merchant.database.permissions_table'), ['slug' => 'can-delete'])
            ->assertEquals(6, Permission::count());
    }
}
