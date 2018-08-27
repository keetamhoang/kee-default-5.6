<?php

namespace App\Http\Controllers\Frontend;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function loginView() {
        if (auth('admin')->check()) {
            return redirect('admin');
        }

        return view('frontend.auth.login');
    }

    public function login(Request $request) {
        $username = $request->input('username');
        $password = $request->input('password');

        $account = User::where('username', $username)->first();

        if (!empty($account)) {
            $hashedPassword = $account->password;

            if (Hash::check($password, $hashedPassword)) {
                auth('admin')->login($account, true);

                return redirect()->intended()->with('success', 'Đăng nhập thành công');
            } else {
                return redirect()->back()->with('error', 'Sai mật khẩu');
            }
        }

        return redirect()->back()->with('error', 'Tài khoản không tồn tại');
    }

    public function logout() {
        if (!auth('admin')->check()) {
            return redirect('/');
        }

        auth('admin')->logout();

        session()->flush();

        return redirect(url('/'));
    }

    public function redirectToProvider(Request $request)
    {
        $type = $request->input('type');

        if(!in_array($type,[1,2])) {
            return redirect()->back()->with('error','Lỗi');
        }

        if($type == 1) {
            return Socialite::driver('facebook')->with(['state' => $type])->redirect();
        }

        return Socialite::driver('google')->with(['state' =>  $type])->redirect();
    }

    public function handleProviderCallback()
    {
        $type = \request('state');

        if($type == 1) {
            $info = Socialite::driver('facebook')->stateless()->user();
            $user = $info->user;

            $data['facebook_id'] = isset($user['id']) ? $user['id'] : '';
            $data['name'] = isset($user['name']) ? $user['name'] : '';
            $data['email'] = isset($user['email']) ? $user['email'] : '';
            $data['link_profile'] = isset($user['link']) ? $user['link'] : '';
            $data['avatar'] = isset($info->avatar) ? ($info->avatar.'&width=300&height=300') : '';

            $checkUser = Account::where('facebook_id', $data['facebook_id'])->first();

            if (!empty($checkUser)) {
                $checkUser->update([
                    'name' => $data['name'],
                    'username' => $data['name'],
                    'email' => $data['email'],
                    'avatar' => $data['avatar'],
                    'link_profile' => $data['link_profile'],
                ]);

                auth('caba')->login($checkUser, true);
            } else {
                $user = Account::create([
                    'name' => $data['name'],
                    'username' => $data['name'],
                    'email' => $data['email'],
                    'password' => '',
                    'phone' => '',
                    'type' => config('constant.USER'),
                    'status' => config('constant.ACTIVE'),
                    'facebook_id' => $data['facebook_id'],
                    'google_id' => '',
                    'avatar' => $data['avatar'],
                    'link_profile' => $data['link_profile'],
                ]);
                auth('caba')->login($user, true);
            }
        } else {
            $user = Account::driver('google')->stateless()->user();
            $data['name'] = (isset($user['name'])) ? $user['name']['givenName'].' '.$user['name']['familyName'] : '' ;
        }

        return redirect('/');

    }

    public function logoutSocial() {
        auth('caba')->logout();

        return redirect('/');
    }
}
