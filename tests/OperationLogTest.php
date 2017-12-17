<?php

use ShaoZeMing\Merchant\Auth\Database\Administrator;
use ShaoZeMing\Merchant\Auth\Database\OperationLog;

class OperationLogTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->be(Administrator::first(), 'merchant');
    }

    public function testOperationLogIndex()
    {
        $this->visit('merchant/auth/logs')
            ->see('Operation log')
            ->see('List')
            ->see('GET')
            ->see('merchant/auth/logs');
    }

    public function testGenerateLogs()
    {
        $table = config('merchant.database.operation_log_table');

        $this->visit('merchant/auth/menu')
            ->seePageIs('merchant/auth/menu')
            ->visit('merchant/auth/users')
            ->seePageIs('merchant/auth/users')
            ->visit('merchant/auth/permissions')
            ->seePageIs('merchant/auth/permissions')
            ->visit('merchant/auth/roles')
            ->seePageIs('merchant/auth/roles')
            ->visit('merchant/auth/logs')
            ->seePageIs('merchant/auth/logs')
            ->seeInDatabase($table, ['path' => 'merchant/auth/menu', 'method' => 'GET'])
            ->seeInDatabase($table, ['path' => 'merchant/auth/users', 'method' => 'GET'])
            ->seeInDatabase($table, ['path' => 'merchant/auth/permissions', 'method' => 'GET'])
            ->seeInDatabase($table, ['path' => 'merchant/auth/roles', 'method' => 'GET']);

        $this->assertEquals(4, OperationLog::count());
    }

    public function testDeleteLogs()
    {
        $table = config('merchant.database.operation_log_table');

        $this->visit('merchant/auth/logs')
            ->seePageIs('merchant/auth/logs')
            ->assertEquals(0, OperationLog::count());

        $this->visit('merchant/auth/users');

        $this->seeInDatabase($table, ['path' => 'merchant/auth/users', 'method' => 'GET']);

        $this->delete('merchant/auth/logs/1')
            ->assertEquals(0, OperationLog::count());
    }

    public function testDeleteMultipleLogs()
    {
        $table = config('merchant.database.operation_log_table');

        $this->visit('merchant/auth/menu')
            ->visit('merchant/auth/users')
            ->visit('merchant/auth/permissions')
            ->visit('merchant/auth/roles')
            ->seeInDatabase($table, ['path' => 'merchant/auth/menu', 'method' => 'GET'])
            ->seeInDatabase($table, ['path' => 'merchant/auth/users', 'method' => 'GET'])
            ->seeInDatabase($table, ['path' => 'merchant/auth/permissions', 'method' => 'GET'])
            ->seeInDatabase($table, ['path' => 'merchant/auth/roles', 'method' => 'GET'])
            ->assertEquals(4, OperationLog::count());

        $this->delete('merchant/auth/logs/1,2,3,4')
            ->notSeeInDatabase($table, ['path' => 'merchant/auth/menu', 'method' => 'GET'])
            ->notSeeInDatabase($table, ['path' => 'merchant/auth/users', 'method' => 'GET'])
            ->notSeeInDatabase($table, ['path' => 'merchant/auth/permissions', 'method' => 'GET'])
            ->notSeeInDatabase($table, ['path' => 'merchant/auth/roles', 'method' => 'GET'])

            ->assertEquals(0, OperationLog::count());
    }
}
