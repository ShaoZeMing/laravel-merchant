<?php

use ShaoZeMing\Merchant\Auth\Database\Administrator;
use ShaoZeMing\Merchant\Auth\Database\Menu;

class MenuTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->be(Administrator::first(), 'merchant');
    }

    public function testMenuIndex()
    {
        $this->visit('merchant/auth/menu')
            ->see('Menu')
            ->see('Index')
            ->see('Auth')
            ->see('Users')
            ->see('Roles')
            ->see('Permission')
            ->see('Menu');
    }

    public function testAddMenu()
    {
        $item = ['parent_id' => '0', 'title' => 'Test', 'uri' => 'test'];

        $this->visit('merchant/auth/menu')
            ->seePageIs('merchant/auth/menu')
            ->see('Menu')
            ->submitForm('Submit', $item)
            ->seePageIs('merchant/auth/menu')
            ->seeInDatabase(config('merchant.database.menu_table'), $item)
            ->assertEquals(8, Menu::count());

        $this->expectException(\Laravel\BrowserKitTesting\HttpException::class);

        $this->visit('merchant')
            ->see('Test')
            ->click('Test');
    }

    public function testDeleteMenu()
    {
        $this->delete('merchant/auth/menu/8')
            ->assertEquals(7, Menu::count());
    }

    public function testEditMenu()
    {
        $this->visit('merchant/auth/menu/1/edit')
            ->see('Menu')
            ->submitForm('Submit', ['title' => 'blablabla'])
            ->seePageIs('merchant/auth/menu')
            ->seeInDatabase(config('merchant.database.menu_table'), ['title' => 'blablabla'])
            ->assertEquals(7, Menu::count());
    }

    public function testShowPage()
    {
        $this->visit('merchant/auth/menu/1')
            ->seePageIs('merchant/auth/menu/1/edit');
    }

    public function testEditMenuParent()
    {
        $this->expectException(\Laravel\BrowserKitTesting\HttpException::class);

        $this->visit('merchant/auth/menu/5/edit')
            ->see('Menu')
            ->submitForm('Submit', ['parent_id' => 5]);
    }
}
