<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Employer;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Helper;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Log;
use App\Models\Category;
use App\Models\Recruitment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EmployerSiteController extends Controller
{
    public function profileEmployer()
    {
        $id = auth()->guard('web')->id();
        $setting = Helper::settings();
        $user = User::find($id);
        $employer = Employer::where('user_id', $id)->first();
        $province_list = Province::all();
        return view('site.employer.profile', compact('user', 'employer', 'province_list', 'setting'));
    }

    public function manageApply()
    {
        $user_apply = User::with('recruitmentApply')->get();
        return view('site.employer.manage_apply', compact('user_apply'));
    }

    public function updateProfileEmployer(Request $request, $id)
    {
        $setting = Helper::settings();
        $employer = Employer::where('user_id', $id)->first();
        $user = User::find($id);
        $rules = [
            "name" => "required|max:250",
            "email" => "email|unique:users,email,'.$user->id.'",
            "company_name" => "required|max:250",
            "phone_name" => "required|max:40",
            "address" => "max:250",
            "email_lien_he" => "required|max:191",
            "phone_lien_he" => "required|digits:10",
            'photo' => 'mimes:jpg,png,jpeg,gif|max:5400'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'code' => 500,
                'msg' => $validator->errors()->messages(),
            ]);
        }

        if ($request->old_password != '' || $request->new_password != '' || $request->re_password != '') {
            $rules2 = [
                'old_password' => 'required',
                'new_password' => 'required|min:8',
                're_password' => 'required|same:new_password',
            ];
            $validator2 = Validator::make($request->all(), $rules2);
            if ($validator2->fails()) {
                return response()->json([
                    'status' => 0,
                    'code' => 500,
                    'msg' => $validator2->errors()->messages(),
                ]);
            }
            $current_password = auth()->guard('web')->user()->password;
            if (Hash::check($request->old_password, $current_password)) {
                $user->password = Hash::make($request->new_password);
            } else {
                return response()->json([
                    'status' => 0,
                    'code' => 403,
                    'msg' => 'Mật khẩu không chính xác',
                ]);
            }
        }

        $user->name = $request->name;
        $user->phone = $request->phone;

        if ($employer) {
            $employer->address = $request->address;
            $employer->phone = $request->phone_lien_he;
            $employer->email = $request->email_lien_he;
            $employer->name = $request->phone_name;
            $employer->maso_thue = $request->maso_thue;
            $employer->company_phone = $request->company_phone;
            $employer->description = $request->description;
            $employer->quy_mo = $request->quy_mo;
            $employer->province_matp = $request->province_matp;
            $employer->company_name = $request->company_name;

            if ($request->hasFile('photo')) {
                $file = $request->photo;
                $file_name = Str::slug($file->getClientOriginalName(), "-") . "-" . time() . "." . $file->getClientOriginalExtension();
                //resize file befor to upload large
                if ($file->getClientOriginalExtension() != "svg") {
                    $image_resize = Image::make($file->getRealPath());
                    $thumb_size = json_decode($setting["THUMB_SIZE_LOGO_EMPLOYER"]);
                    $image_resize->resize($thumb_size->width, $thumb_size->height);
                    $image_resize->save('upload/images/employer/thumb/' . $file_name);
                }
                // close upload image
                $file->move("upload/images/employer/large/", $file_name);

                $employer->photo = $file_name;
            }
        }

        if ($user->save() && $employer->save()) {
            return response()->json([
                'status' => 1,
                'code' => 200,
                'msg' => 'Cập nhật thông tin tài khoản thành công',
            ]);
        }
    }

    public function manageJob()
    {
        $recruitment_list = Recruitment::where('user_id', auth()->id())->where('status', '!=', 2)->get();
        return view('site.employer.manage_job', compact('recruitment_list'));
    }

    public function createJob()
    {
        $category_list = Category::with('informations')->where('status', 1)->where('type', 2)->orWhere('type', 0)->orderBy('stt', 'ASC')->get();
        $province_list = Province::get();
        $setting = Helper::settings();
        $employer = Employer::where('user_id', auth()->guard('web')->id())->first();
        return view('site.employer.create_job', compact('category_list', 'province_list', 'setting', 'employer'));
    }

    public function storeJob(Request $request)
    {
        $setting = Helper::settings();
        $employer = Employer::where('user_id', auth()->id())->first();
        $rules = [
            "vitri" => "required|max:250",
            "information_id" => "required",
            "hannop" => "required|max:100",
            "soluong" => "required|numeric",
            "phone_name" => "required|max:40",
            "address" => "max:250",
            "email_lien_he" => "required|max:191",
            "phone_lien_he" => "required|digits:10"
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->with(["type" => "danger", "flash_message" => "Có lỗi xãy ra"]);
        }

        try {
            DB::beginTransaction();

            if ($employer) {
                $employer->address = $request->address;
                $employer->phone = $request->phone_lien_he;
                $employer->email = $request->email_lien_he;
                $employer->name = $request->phone_name;
                $employer->save();

                $recruitment = new Recruitment;
                $recruitment->user_id = auth()->guard('web')->id();
                $recruitment->employer_id = $employer->id;
                $recruitment->vi_tri = $request->vitri;
                $recruitment->status = 1;
                $recruitment->slug = Str::slug($request->vitri);
                $recruitment->description = $request->description;
                $recruitment->so_luong = $request->soluong;
                $recruitment->han_nop = $request->hannop;
                $recruitment->yeu_cau = $request->yeucau;
                $recruitment->quyen_loi = $request->quyenloi;
                $recruitment->hinh_thuc = $request->hinhthuc;
                $recruitment->ho_so_gom = $request->hosogom;
                $recruitment->hoa_hong_from = $request->hoahong_from;
                $recruitment->hoa_hong_to = $request->hoahong_to;
                $recruitment->save();
            } else {
                return back()->with(["type" => "danger", "flash_message" => "Có lỗi xảy ra"]);
            }

            $recruitment->informations()->attach($request->information_id);
            $recruitment->provinces()->attach($request->province_matp);
            DB::commit();
            return back()->with(["type" => "success", "flash_message" => "Tạo tin tuyển dụng thành công"]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("message: " . $e->getMessage() . ' ---- line: ' . $e->getLine());
        }
    }

    public function editJob($slug, $id)
    {
        $setting = Helper::settings();
        $category_list = Category::with('informations')->where('status', 1)->where('type', 2)->orWhere('type', 0)->orderBy('stt', 'ASC')->get();
        $province_list = Province::get();
        $recruitment = Recruitment::with('provinces', 'informations')->where('slug', $slug)->where('id', $id)->first();
        $employer = Employer::where('user_id', auth()->id())->first();
        if ($recruitment) {
            return view('site.employer.edit_job', compact('recruitment', 'employer', 'setting', 'province_list', 'category_list'));
        } else {
            abort(404);
        }
    }

    public function updateJob(Request $request, $id)
    {
        $recruitment = Recruitment::find($id);
        $employer = Employer::where('user_id', auth()->id())->first();
        $rules = [
            "vitri" => "required|max:250",
            "information_id" => "required",
            "hannop" => "required|max:100",
            "soluong" => "required|numeric",
            "phone_name" => "required|max:40",
            "address" => "max:250",
            "email_lien_he" => "required|max:191",
            "phone_lien_he" => "required|digits:10"
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->with(["type" => "danger", "flash_message" => "Có lỗi xãy ra"]);
        }

        try {
            DB::beginTransaction();

            $recruitment->vi_tri = $request->vitri;
            $recruitment->slug = Str::slug($request->vitri);
            $recruitment->description = $request->description;
            $recruitment->so_luong = $request->soluong;
            $recruitment->han_nop = $request->hannop;
            $recruitment->yeu_cau = $request->yeucau;
            $recruitment->quyen_loi = $request->quyenloi;
            $recruitment->hinh_thuc = $request->hinhthuc;
            $recruitment->ho_so_gom = $request->hosogom;
            $recruitment->hoa_hong_from = $request->hoahong_from;
            $recruitment->hoa_hong_to = $request->hoahong_to;
            $recruitment->save();

            if ($employer) {
                $employer->address = $request->address;
                $employer->phone = $request->phone_lien_he;
                $employer->email = $request->email_lien_he;
                $employer->name = $request->phone_name;
                $employer->save();
            } else {
                return back()->with(["type" => "danger", "flash_message" => "Có lỗi xãy ra"]);
            }

            $recruitment->informations()->sync($request->information_id);
            $recruitment->provinces()->sync($request->province_matp);
            DB::commit();
            return redirect()->route('employer.job.edit', ['slug' => $recruitment->slug, 'id' => $recruitment->id])->with(["type" => "success", "flash_message" => "Cập nhật tin tuyển dụng thành công"]);;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("message: " . $e->getMessage() . ' ---- line: ' . $e->getLine());
        }
    }

    public function updateStatus(Request $request)
    {
        $id = $request->id;
        $data = Recruitment::find($id);
        if ($data) {
            $data->status = $request->status;
            $data->updated_at = Carbon::now();

            $data->save();

            return response()->json([
                'status' => 1,
                'code' => 201,
                'msg' => "Cập nhật thành công",
            ]);
        }

        return response()->json([
            'status' => 0,
            'code' => 500,
            'msg' => "Cập nhật thất bại",
        ]);
    }

    public function deleteJob(Request $request)
    {
        $id = $request->id;
        $data = Recruitment::find($id);
        if ($data) {
            $data->status = $request->status;
            $data->updated_at = Carbon::now();
            $data->save();

            return response()->json([
                'status' => 1,
                'code' => 201,
                'msg' => "Xóa hồ sơ thành công",
            ]);
        }

        return response()->json([
            'status' => 0,
            'code' => 500,
            'msg' => "Xóa hồ sơ thất bại",
        ]);
    }

    public function login()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('home');
        }
        return view('site.employer.login');
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
            'type' => 2,
        );
        $remember = false;
        if ($request->remember != '') {
            $remember = true;
        }

        if (Auth::guard('web')->attempt($auth, $remember)) {
            $user = User::where('email', $auth['email'])->first();

            if ($user->email_verified_at == '') {
                $token = Str::random(30);
                Cookie::queue('token_employer', $token, 3);
                $link_register = url("/employer/confirm-acount?email=" . $user->email . "&token=" . $token);
                $data = array(
                    "name" => $user->name,
                    "email" => $user->email,
                    "link" => $link_register,
                );
                //Gui mail 
                Mail::send('dashboard.page.confirm_account', compact('data'), function ($mail) use ($data) {
                    $mail->subject(config('app.name') . " - Xác nhận tài khoản");
                    $mail->to($data['email'], $data['name']);
                    $mail->from(config('mail.from.address'), config('mail.from.name'));
                });
                auth()->guard('web')->logout();
                return back()->with(["success" => "success", "flash_message" => "Bạn cần xác thực tài khoản, chúng tôi đã gửi mã xác thực vào email của bạn."]);
            }

            return redirect()->route('home');
        } else {
            return back()->with(["danger" => "danger", "flash_message" => "Email hoặc mật khẩu không chính xác!"]);
        }
    }

    public function register()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('home');
        }
        $province = Province::get();
        return view('site.employer.register', compact('province'));
    }

    public function postRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:40',
            'phone' => 'required|digits:10',
            'password' => 'required|min:8',
            'email' => 'required|email|unique:users,email',
        ]);

        if (!$validator->passes()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $token = Str::random(30);
            Cookie::queue('token_employer', $token, 3);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'type' => 2,
                'status' => 0,
            ]);

            if ($user) {

                $employer = Employer::create([
                    'name' => $request->phone_name,
                    'email' => $request->email_lien_he,
                    'phone' => $request->phone_lien_he,
                    'company_phone' => $request->phone_co_dinh,
                    'company_name' => $request->company_name,
                    'province_matp' => $request->province_id,
                    'quy_mo' => $request->quy_mo,
                    'user_id' => $user->id,
                ]);

                $link_register = url("/employer/confirm-acount?email=" . $user->email . "&token=" . $token);
                $data = array(
                    "name" => $user->name,
                    "email" => $user->email,
                    "link" => $link_register,
                );

                if ($employer) {
                    //Gui mail 
                    Mail::send('dashboard.page.confirm_account', compact('data'), function ($mail) use ($data) {
                        $mail->subject(config('app.name') . " - Xác nhận tài khoản");
                        $mail->to($data['email'], $data['name']);
                        $mail->from(config('mail.from.address'), config('mail.from.name'));
                    });

                    return response()->json([
                        'status' => 1,
                        'msg' => 'Đăng ký thành công'
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 0,
                        'msg' => 'Đăng ký thất bại'
                    ], 500);
                }
            }
        }
    }

    public function confirmAccount(Request $request)
    {
        $token = $request->token;
        $email = $request->email;

        $user = User::where("email", $email)->first();

        if (!empty($user) && $token == Cookie::get('token_employer')) {
            $user->email_verified_at = Carbon::now();
            $user->status = 1;
            if ($user->save()) {
                return redirect()->route('employer.login')->with(["success" => "success", "flash_message" => "Xác thực email thành công"]);
            }
        }
        return redirect()->route('employer.login')->with(["danger" => "danger", "flash_message" => "Token đã hết hạn"]);
    }

    public function logout()
    {
        Auth::guard('web')->logout();

        if (!auth()->guard('web')->check()) {
            return redirect()->route('home');
        }
    }
}
