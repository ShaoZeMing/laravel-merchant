<?php

namespace ShaoZeMing\Merchant\Controllers;

use ShaoZeMing\Merchant\Auth\Database\Permission;
use ShaoZeMing\Merchant\Auth\Database\Role;
use ShaoZeMing\Merchant\Facades\Admin;
use ShaoZeMing\Merchant\Form;
use ShaoZeMing\Merchant\Grid;
use ShaoZeMing\Merchant\Layout\Content;
use Illuminate\Routing\Controller;

class RoleController extends Controller
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
            $content->header(trans('merchant.roles'));
            $content->description(trans('merchant.list'));
            $content->body($this->grid()->render());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header(trans('merchant.roles'));
            $content->description(trans('merchant.edit'));
            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('merchant.roles'));
            $content->description(trans('merchant.create'));
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Role::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->slug(trans('merchant.slug'));
            $grid->name(trans('merchant.name'));

            $grid->permissions(trans('merchant.permission'))->pluck('name')->label();

            $grid->created_at(trans('merchant.created_at'));
            $grid->updated_at(trans('merchant.updated_at'));

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                if ($actions->row->slug == 'administrator') {
                    $actions->disableDelete();
                }
            });

            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Admin::form(Role::class, function (Form $form) {
            $form->display('id', 'ID');

            $form->text('slug', trans('merchant.slug'))->rules('required');
            $form->text('name', trans('merchant.name'))->rules('required');
            $form->listbox('permissions', trans('merchant.permissions'))->options(Permission::all()->pluck('name', 'id'));

            $form->display('created_at', trans('merchant.created_at'));
            $form->display('updated_at', trans('merchant.updated_at'));
        });
    }
}
