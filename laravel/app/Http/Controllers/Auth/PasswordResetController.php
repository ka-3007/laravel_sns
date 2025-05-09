<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;

class PasswordResetController extends Controller
{
    // パスワードリセットリンク要求フォームを表示
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    // パスワードリセットリンクをメールで送信
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $response = Password::sendResetLink(
            $request->only('email')
        );

        // リセットリンクが正常に送信された場合
        if ($response == Password::RESET_LINK_SENT) {
            return back()->with('status', __('passwords.sent'));
        }

        // ユーザーが見つからなかった場合
        if ($response == Password::INVALID_USER) {
            return back()->withErrors(['email' => __('passwords.user')]);
        }


        // 頻度制限に引っかかった場合（レスポンスが適切にエラーとして返される）
        if ($response === 'passwords.throttled') {
            return back()->withErrors(['email' => __('passwords.throttled')]);
        }


        return back()->withErrors(['email' => __('passwords.throttled')]);
    }

    // パスワードリセットフォームを表示
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset', ['token' => $token, 'email' => $request->email]);
    }

    // パスワードをリセット
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);

        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        return $response == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __('passwords.reset'))
            : back()->withErrors(['email' => __('passwords.token')]);
    }
}
