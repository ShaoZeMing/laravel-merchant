<?php

namespace ShaoZeMing\Merchant\Controllers;

use ShaoZeMing\Merchant\Auth\Database\Administrator;
use ShaoZeMing\Merchant\Auth\Database\OperationLog;
use ShaoZeMing\Merchant\Facades\Merchant;
use ShaoZeMing\Merchant\Grid;
use ShaoZeMing\Merchant\Layout\Content;
use Illuminate\Routing\Controller;

class LogController extends Controller
{
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Merchant::content(function (Content $content) {
            $content->header(trans('merchant.operation_log'));
            $content->description(trans('merchant.list'));

            $grid = Merchant::grid(OperationLog::class, function (Grid $grid) {
                $grid->model()->orderBy('id', 'DESC');

                $grid->id('ID')->sortable();
                $grid->user()->name(trans('merchant.user'));
                $grid->method()->display(function ($method) {
                    $color = array_get(OperationLog::$methodColors, $method, 'grey');

                    return "<span class=\"badge bg-$color\">$method</span>";
                });
                $grid->path()->label('info');
                $grid->ip()->label('primary');
                $grid->input()->display(function ($input) {
                    $input = json_decode($input, true);
                    $input = array_except($input, ['_pjax', '_token', '_method', '_previous_']);
                    if (empty($input)) {
                        return '<code>{}</code>';
                    }

                    return '<pre>'.json_encode($input, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).'</pre>';
                });

                $grid->created_at(trans('merchant.created_at'));

                $grid->actions(function (Grid\Displayers\Actions $actions) {
                    $actions->disableEdit();
                });

                $grid->disableCreation();

                $grid->filter(function ($filter) {
                    $filter->equal('user_id', 'User')->select(Administrator::all()->pluck('name', 'id'));
                    $filter->equal('method')->select(array_combine(OperationLog::$methods, OperationLog::$methods));
                    $filter->like('path');
                    $filter->equal('ip');
                });
            });

            $content->body($grid);
        });
    }

    public function destroy($id)
    {
        $ids = explode(',', $id);

        if (OperationLog::destroy(array_filter($ids))) {
            return response()->json([
                'status'  => true,
                'message' => trans('merchant.delete_succeeded'),
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => trans('merchant.delete_failed'),
            ]);
        }
    }
}
