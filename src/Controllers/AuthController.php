<?php

namespace ShaoZeMing\Merchant\Controllers;

use ShaoZeMing\Merchant\Auth\Database\Administrator;
use ShaoZeMing\Merchant\Facades\Merchant;
use ShaoZeMing\Merchant\Form;
use ShaoZeMing\Merchant\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{


    /**
     * Register page.
     *
     * @return \Illuminate\Contracts\View\Factory|Redirect|\Illuminate\View\View
     */
    public function getRegister()
    {
//        if (!Auth::guard('merchant')->guest()) {
//            return redirect(config('merchant.route.prefix'));
//        }

        return view('merchant::register');
    }


    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function postRegister(Request $request)
    {
        $credentials = $request->only(['mobile', 'password']);

        $validator = Validator::make($credentials, [
            'mobile' => 'required', 'password' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }


        //这儿需要重写

        return Redirect::back()->withInput()->withErrors(['mobile' => $this->getFailedLoginMessage()]);
    }


    /**
     * Forget page.
     *
     * @return \Illuminate\Contracts\View\Factory|Redirect|\Illuminate\View\View
     */
    public function getForget()
    {
//        if (!Auth::guard('merchant')->guest()) {
//            return redirect(config('merchant.route.prefix'));
//        }

        return view('merchant::forget');
    }


    /**
     *
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function postForget(Request $request)
    {
        $credentials = $request->only(['mobile', 'password']);

        $validator = Validator::make($credentials, [
            'mobile' => 'required', 'password' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }


        //这儿需要重写

        return Redirect::back()->withInput()->withErrors(['mobile' => $this->getFailedLoginMessage()]);
    }



    /**
     * Login page.
     *
     * @return \Illuminate\Contracts\View\Factory|Redirect|\Illuminate\View\View
     */
    public function getLogin()
    {
        if (!Auth::guard('merchant')->guest()) {
            return redirect(config('merchant.route.prefix'));
        }

        return view('merchant::login');
    }




    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function postLogin(Request $request)
    {
        $credentials = $request->only(['mobile', 'password']);

        $validator = Validator::make($credentials, [
            'mobile' => 'required', 'password' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        if (Auth::guard('merchant')->attempt($credentials)) {
            merchant_toastr(trans('merchant.login_successful'));

            return redirect()->intended(config('merchant.route.prefix'));
        }

        return Redirect::back()->withInput()->withErrors(['mobile' => $this->getFailedLoginMessage()]);
    }

    /**
     * User logout.
     *
     * @return Redirect
     */
    public function getLogout()
    {
        Auth::guard('merchant')->logout();

        session()->forget('url.intented');

        return redirect(config('merchant.route.prefix'));
    }

    /**
     * User setting page.
     *
     * @return mixed
     */
    public function getSetting()
    {
        return Merchant::content(function (Content $content) {
            $content->header(trans('merchant.user_setting'));
            $form = $this->settingForm();
            $form->tools(
                function (Form\Tools $tools) {
                    $tools->disableBackButton();
                    $tools->disableListButton();
                }
            );
            $content->body($form->edit(Merchant::user()->id));
        });
    }

    /**
     * Update user setting.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function putSetting()
    {
        return $this->settingForm()->update(Merchant::user()->id);
    }

    /**
     * Model-form for user setting.
     *
     * @return Form
     */
    protected function settingForm()
    {
        return Administrator::form(function (Form $form) {
            $form->display('mobile', trans('merchant.mobile'))->rules('required');
            $form->email('email', trans('merchant.email'))->rules('required');
            $form->text('name', trans('merchant.name'))->rules('required');
            $form->image('avatar', trans('merchant.avatar'));
            $form->password('password', trans('merchant.password'))->rules('confirmed|required');
            $form->password('password_confirmation', trans('merchant.password_confirmation'))->rules('required')
                ->default(function ($form) {
                    return $form->model()->password;
                });

            $form->setAction(merchant_base_path('auth/setting'));
            $form->ignore(['password_confirmation']);

            $form->saving(function (Form $form) {
                if ($form->password && $form->model()->password != $form->password) {
                    $form->password = bcrypt($form->password);
                }
            });

            $form->saved(function () {
                merchant_toastr(trans('merchant.update_succeeded'));

                return redirect(merchant_base_path('auth/setting'));
            });
        });
    }

    /**
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    protected function getFailedLoginMessage()
    {
        return Lang::has('auth.failed')
            ? trans('auth.failed')
            : 'These credentials do not match our records.';
    }
}
