<?php

namespace ShaoZeMing\Merchant\Controllers;

use ShaoZeMing\Merchant\Auth\Database\Menu;
use ShaoZeMing\Merchant\Auth\Database\Role;
use ShaoZeMing\Merchant\Facades\Admin;
use ShaoZeMing\Merchant\Form;
use ShaoZeMing\Merchant\Layout\Column;
use ShaoZeMing\Merchant\Layout\Content;
use ShaoZeMing\Merchant\Layout\Row;
use ShaoZeMing\Merchant\Tree;
use ShaoZeMing\Merchant\Widgets\Box;
use Illuminate\Routing\Controller;

class MenuController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('merchant.menu'));
            $content->description(trans('merchant.list'));

            $content->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) {
                    $form = new \ShaoZeMing\Merchant\Widgets\Form();
                    $form->action(merchant_base_path('auth/menu'));

                    $form->select('parent_id', trans('merchant.parent_id'))->options(Menu::selectOptions());
                    $form->text('title', trans('merchant.title'))->rules('required');
                    $form->icon('icon', trans('merchant.icon'))->default('fa-bars')->rules('required')->help($this->iconHelp());
                    $form->text('uri', trans('merchant.uri'));
                    $form->multipleSelect('roles', trans('merchant.roles'))->options(Role::all()->pluck('name', 'id'));

                    $column->append((new Box(trans('merchant.new'), $form))->style('success'));
                });
            });
        });
    }

    /**
     * Redirect to edit page.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        return redirect()->route('menu.edit', ['id' => $id]);
    }

    /**
     * @return \ShaoZeMing\Merchant\Tree
     */
    protected function treeView()
    {
        return Menu::tree(function (Tree $tree) {
            $tree->disableCreate();

            $tree->branch(function ($branch) {
                $payload = "<i class='fa {$branch['icon']}'></i>&nbsp;<strong>{$branch['title']}</strong>";

                if (!isset($branch['children'])) {
                    if (url()->isValidUrl($branch['uri'])) {
                        $uri = $branch['uri'];
                    } else {
                        $uri = merchant_base_path($branch['uri']);
                    }

                    $payload .= "&nbsp;&nbsp;&nbsp;<a href=\"$uri\" class=\"dd-nodrag\">$uri</a>";
                }

                return $payload;
            });
        });
    }

    /**
     * Edit interface.
     *
     * @param string $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header(trans('merchant.menu'));
            $content->description(trans('merchant.edit'));

            $content->row($this->form()->edit($id));
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Menu::form(function (Form $form) {
            $form->display('id', 'ID');

            $form->select('parent_id', trans('merchant.parent_id'))->options(Menu::selectOptions());
            $form->text('title', trans('merchant.title'))->rules('required');
            $form->icon('icon', trans('merchant.icon'))->default('fa-bars')->rules('required')->help($this->iconHelp());
            $form->text('uri', trans('merchant.uri'));
            $form->multipleSelect('roles', trans('merchant.roles'))->options(Role::all()->pluck('name', 'id'));

            $form->display('created_at', trans('merchant.created_at'));
            $form->display('updated_at', trans('merchant.updated_at'));
        });
    }

    /**
     * Help message for icon field.
     *
     * @return string
     */
    protected function iconHelp()
    {
        return 'For more icons please see <a href="http://fontawesome.io/icons/" target="_blank">http://fontawesome.io/icons/</a>';
    }
}
