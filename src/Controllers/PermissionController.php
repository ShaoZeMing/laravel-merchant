<?php

namespace ShaoZeMing\Merchant\Controllers;

use ShaoZeMing\Merchant\Auth\Database\Permission;
use ShaoZeMing\Merchant\Facades\Merchant;
use ShaoZeMing\Merchant\Form;
use ShaoZeMing\Merchant\Grid;
use ShaoZeMing\Merchant\Layout\Content;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class PermissionController extends Controller
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
            $content->header(trans('merchant.permissions'));
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
            $content->header(trans('merchant.permissions'));
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
            $content->header(trans('merchant.permissions'));
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
        return Merchant::grid(Permission::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->slug(trans('merchant.slug'));
            $grid->name(trans('merchant.name'));

            $grid->http_path(trans('merchant.route'))->display(function ($path) {
                return collect(explode("\r\n", $path))->map(function ($path) {
                    $method = $this->http_method ?: ['ANY'];

                    if (Str::contains($path, ':')) {
                        list($method, $path) = explode(':', $path);
                        $method = explode(',', $method);
                    }

                    $method = collect($method)->map(function ($name) {
                        return strtoupper($name);
                    })->map(function ($name) {
                        return "<span class='label label-primary'>{$name}</span>";
                    })->implode('&nbsp;');

                    $path = '/'.trim(config('merchant.route.prefix'), '/').$path;

                    return "<div style='margin-bottom: 5px;'>$method<code>$path</code></div>";
                })->implode('');
            });

            $grid->created_at(trans('merchant.created_at'));
            $grid->updated_at(trans('merchant.updated_at'));

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
        return Merchant::form(Permission::class, function (Form $form) {
            $form->display('id', 'ID');

            $form->text('slug', trans('merchant.slug'))->rules('required');
            $form->text('name', trans('merchant.name'))->rules('required');

            $form->multipleSelect('http_method', trans('merchant.http.method'))
                ->options($this->getHttpMethodsOptions())
                ->help(trans('merchant.all_methods_if_empty'));
            $form->textarea('http_path', trans('merchant.http.path'));

            $form->display('created_at', trans('merchant.created_at'));
            $form->display('updated_at', trans('merchant.updated_at'));
        });
    }

    /**
     * Get options of HTTP methods select field.
     *
     * @return array
     */
    protected function getHttpMethodsOptions()
    {
        return array_combine(Permission::$httpMethods, Permission::$httpMethods);
    }
}
