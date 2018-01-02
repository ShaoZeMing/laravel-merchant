<?php

namespace ShaoZeMing\Merchant\Controllers;

use Illuminate\Routing\Controller;
use ShaoZeMing\Merchant\Auth\Database\Administrator;
use ShaoZeMing\Merchant\Auth\Database\Permission;
use ShaoZeMing\Merchant\Auth\Database\Role;
use ShaoZeMing\Merchant\Facades\Merchant;
use ShaoZeMing\Merchant\Form;
use ShaoZeMing\Merchant\Grid;
use ShaoZeMing\Merchant\Layout\Content;

class UserController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Merchant::content(function (Content $content) {
            $content->header(trans('merchant.administrator'));
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
        return Merchant::content(function (Content $content) use ($id) {
            $content->header(trans('merchant.administrator'));
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
        return Merchant::content(function (Content $content) {
            $content->header(trans('merchant.administrator'));
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
        return Administrator::grid(function (Grid $grid) {
            $grid->model()->where('merchant_id', auth('merchant')->user()->merchant_id);
            $grid->mobile(trans('merchant.mobile'));
            $grid->name(trans('merchant.name'));
            $grid->roles(trans('merchant.roles'))->pluck('name')->label();
            $grid->created_at(trans('merchant.created_at'));
            $grid->updated_at(trans('merchant.updated_at'));

            $grid->actions(function (Grid\Displayers\Actions $actions)use($grid) {
                if (Administrator::find($actions->getKey())->user_type) {
                    $actions->disableDelete();}
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
        return Administrator::form(function (Form $form) {
            $form->mobile('mobile', trans('merchant.mobile'))->rules('required');
            $form->email('email', trans('merchant.email'))->default('');
            $form->text('name', trans('merchant.name'))->rules('required');
            $form->image('avatar', trans('merchant.avatar'));
            $form->password('password', trans('merchant.password'))->rules('required|confirmed');
            $form->password('password_confirmation', trans('merchant.password_confirmation'))->rules('required')
                ->default(function ($form) {
                    return $form->model()->password;
                });

            $form->ignore(['password_confirmation']);
            $form->multipleSelect('roles', trans('merchant.roles'))->options(Role::all()->pluck('name', 'id'));
            $form->multipleSelect('permissions', trans('merchant.permissions'))->options(Permission::all()->pluck('name', 'id'));
            $form->display('created_at', trans('merchant.created_at'));
            $form->display('updated_at', trans('merchant.updated_at'));
            $form->hidden('merchant_id');

            $form->saving(function (Form $form) {
                $form->merchant_id = auth('merchant')->user()->merchant_id;
                if ($form->password && $form->model()->password != $form->password) {
                    $form->password = bcrypt($form->password);
                }
            });
        });
    }
}
