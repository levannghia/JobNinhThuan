<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::guard("admin")->check()) {
            return redirect()->route("dashboard.index");
        }
    
        return view('dashboard.page.login');
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            "email" => "required",
            "password" => "required",
        ], [
            "email.required" => "Vui lòng nhập email",
            "password.required" => "Vui lòng nhập mật khẩu",
        ]);
        $auth = array(
            'email' => $request->email,
            'password' => $request->password,
        );
        $remember = false;
        if ($request->remember != '') {
            $remember = true;
        }
        
        if (Auth::guard('admin')->attempt($auth, $remember)) {
            $admin = Admin::where('email', $auth['email'])->first();
            
            if ($admin->email_verified_at == '') {
                $token = Str::random(30);
                Cookie::queue('token', $token, 3);
                $link_register = url("/dashboard/confirm-acount?email=" . $admin->email . "&token=" . $token);
                $data = array(
                    "name" => $admin->name,
                    "email" => $admin->email,
                    "link" => $link_register,
                );
                //Gui mail 
                Mail::send('dashboard.page.confirm_account', compact('data'), function ($mail) use ($data) {
                    $mail->subject(config('app.name') . " - Xác nhận tài khoản");
                    $mail->to($data['email'], $data['name']);
                    $mail->from(config('mail.from.address'), config('mail.from.name'));
                });
                auth()->guard('admin')->logout();
                return back()->with(["type" => "success", "flash_message" => "Bạn cần xác thực tài khoản, chúng tôi đã gửi mã xác thực vào email của bạn, hãy kiểm tra và làm theo hướng dẫn."]);
            }

            return redirect()->route('dashboard.index');
        }
    }
    public function register()
    {
        return view('dashboard.page.register');
    }

    public function postRegister(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:40',
            'password' => 'required|min:8',
            're_password' => 'required|same:password',
            'email' => 'required|email|unique:admins,email',
        ]);

        $token = Str::random(30);
        Cookie::queue('token', $token, 3);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 0,
        ]);

        if ($admin) {
            $link_register = url("/dashboard/confirm-acount?email=" . $admin->email . "&token=" . $token);
            $data = array(
                "name" => $admin->name,
                "email" => $admin->email,
                "link" => $link_register,
            );
            //Gui mail 
            Mail::send('dashboard.page.confirm_account', compact('data'), function ($mail) use ($data) {
                $mail->subject(config('app.name') . " - Xác nhận tài khoản");
                $mail->to($data['email'], $data['name']);
                $mail->from(config('mail.from.address'), config('mail.from.name'));
            });
            return back()->with(["type" => "success", "flash_message" => "Vui lòng xác nhận mail để hoàn thành đăng ký"]);
        }

        return back()->with(["type" => "danger", "flash_message" => "Email hoặc username đã tồn tại"]);
    }

    public function confirmAccount(Request $request)
    {
        $token = $request->token;
        $email = $request->email;

        $admin = Admin::where("email", $email)->first();
        // dd($admin);
        if (!empty($admin) && $token == Cookie::get('token')) {
            $admin->email_verified_at = Carbon::now();
            $admin->status = 1;
            if ($admin->save()) {
                return redirect()->route('dashboard.login')->with(["type" => "success", "flash_message" => "Xác thực email thành công"]);
            }
        }
        return redirect()->route('dashboard.login')->with(["type" => "danger", "flash_message" => "Token đã hết hạn"]);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        if(!auth()->guard('admin')->check()){
            return redirect()->route('dashboard.login');
        }
    }
}
