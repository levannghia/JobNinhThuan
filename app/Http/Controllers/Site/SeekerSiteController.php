<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HoSoXinViec;
use App\Models\Province;
use App\Models\Recruitment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Social;
use Laravel\Socialite\Facades\Socialite;
use Helper;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class SeekerSiteController extends Controller
{
    public function manageWishList()
    {
        $user_wishlist = User::with('recruitmentWishlist')->get();
        return view('site.seeker.manage_wishlist', compact('user_wishlist'));
    }

    public function manageApply()
    {
        $user_apply = User::with('recruitmentApply')->get();
        return view('site.seeker.manage_apply', compact('user_apply'));
    }

    public function manageProfile()
    {
        $hoSoXinViec_list = HoSoXinViec::where('user_id', auth()->id())->where('status', '!=', 2)->get();
        return view('site.seeker.manage_profile', compact('hoSoXinViec_list'));
    }

    public function createProfile()
    {
        $category_list = Category::with('informations')->where('status', 1)->where('type', 1)->orWhere('type', 0)->orderBy('stt', 'ASC')->get();
        $province_list = Province::get();
        $setting = Helper::settings();
        return view('site.seeker.create_profile', compact('category_list', 'province_list', 'setting'));
    }
    public function getProfileUser()
    {
        $setting = Helper::settings();
        $province_list = Province::get();
        $id = auth()->guard('web')->id();
        $user = User::find($id);
        return view('site.seeker.user', compact('user', 'setting', 'province_list'));
    }

    public function updateProfileUser(Request $request, $id)
    {
        $setting = Helper::settings();
        $user = User::find($id);
        $rules = [
            "name" => "required|max:250",
            "email" => "email|unique:users,email,'.$user->id.'",
            "address" => "required|max:250",
            "date_of_birth" => "required|max:80",
            "address" => "max:250",
            "province_matp" => "required|numeric",
            "phone" => "required|digits:10",
            'photo' => 'mimes:jpg,png,jpeg,gif|max:5400'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->with(["type" => "danger", "flash_message" => "Lỗi nhập liệu"]);
        }
        if ($request->old_password != '' || $request->new_password != '' || $request->re_password != '') {
            $rules2 = [
                'old_password' => 'required',
                'new_password' => 'required|min:8',
                're_password' => 'required|same:new_password',
            ];
            $validator2 = Validator::make($request->all(), $rules2);
            if ($validator2->fails()) {
                return back()->with(["type" => "danger", "flash_message" => "Mật khẩu không giống nhau"]);
                // return response()->json([
                //     'status' => 0,
                //     'code' => 500,
                //     'msg' => $validator2->errors()->messages(),
                // ]);
            }
            $current_password = auth()->guard('web')->user()->password;
            if (Hash::check($request->old_password, $current_password)) {
                $user->password = Hash::make($request->new_password);
            } else {
                return back()->with(["type" => "danger", "flash_message" => "Mật khẩu không chính xác"]);
            }
        }

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->hon_nhan = $request->hon_nhan;
        $user->province_matp = $request->province_matp;
        $user->gender = $request->gender;
        $user->date_of_birth = $request->date_of_birth;

        if ($request->hasFile('photo')) {
            $file = $request->photo;
            $file_name = Str::slug($file->getClientOriginalName(), "-") . "-" . time() . "." . $file->getClientOriginalExtension();
            //resize file befor to upload large
            if ($file->getClientOriginalExtension() != "svg") {
                $image_resize = Image::make($file->getRealPath());
                $thumb_size = json_decode($setting["THUMB_SIZE_USER"]);
                $image_resize->resize($thumb_size->width, $thumb_size->height);
                $image_resize->save('upload/images/seeker/thumb/' . $file_name);
            }
            // close upload image
            $file->move("upload/images/seeker/large/", $file_name);

            $user->photo = $file_name;
        }


        if ($user->save()) {
            return back()->with(["type" => "success", "flash_message" => "Cập nhật thông tin thành công"]);
        }
    }

    public function storeProfile(Request $request)
    {
        $setting = Helper::settings();

        $rules = [
            "vitri" => "required|max:250",
            'information_id' => "required",
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 500,
                'msg' => "Có lỗi xãy ra",
            ]);
        }

        try {
            DB::beginTransaction();
            $hoSoXinViec = new HoSoXinViec;
            $hoSoXinViec->user_id = auth()->guard('web')->id();
            $hoSoXinViec->vi_tri = $request->vitri;
            $hoSoXinViec->status = 1;
            $hoSoXinViec->slug = Str::slug($request->vitri);
            $hoSoXinViec->description = $request->description;
            $hoSoXinViec->kinh_nghiem = !empty($request->data['kinh_nghiem']) ? json_encode($request->data['kinh_nghiem']) : '';
            $hoSoXinViec->ngoai_ngu = !empty($request->data['ngoai_ngu']) ? json_encode($request->data['ngoai_ngu']) : '';
            $hoSoXinViec->tin_hoc = !empty($request->tin_hoc) ? json_encode($request->tin_hoc) : '';

            $bang_cap = !empty($request->data['bang_cap']) ? $request->data['bang_cap'] : '';
            $bc_arr = array();
            if (!empty($bang_cap) && is_array($bang_cap)) {
                foreach ($bang_cap as $key => $value) {

                    $bc_arr[$key]['name'] = $value['name'];
                    $bc_arr[$key]['don_vi'] = $value['don_vi'];
                    $bc_arr[$key]['chuyen_nganh'] = $value['chuyen_nganh'];
                    $bc_arr[$key]['loai_tot_nghiep'] = $value['loai'];
                    $bc_arr[$key]['thoi_gian'] = $value['thoi_gian'];

                    if (isset($value['photo']) && !empty($value['photo'])) {
                        $file = $value['photo'];
                        // dd($file->getSize());
                        //jpg,jpeg,png,svg,gif
                        switch ($file->getClientOriginalExtension()) {
                            case 'jpg':
                            case 'jpeg':
                            case 'png':
                            case 'svg':
                            case 'gif':
                                if ($file->getSize() <= 7340032) {
                                    $file_name = Str::slug($file->getClientOriginalName(), "-") . "-" . time() . "." . $file->getClientOriginalExtension();
                                    //resize file befor to upload large
                                    if ($file->getClientOriginalExtension() != "svg") {
                                        $image_resize = Image::make($file->getRealPath());
                                        $thumb_size = json_decode($setting["THUMB_SIZE_BANG_CAP"]);
                                        $image_resize->resize($thumb_size->width, $thumb_size->height);
                                        $image_resize->save('upload/images/hosoxinviec/thumb/' . $file_name);
                                    }
                                    // close upload image
                                    $file->move("upload/images/hosoxinviec/large/", $file_name);

                                    // $options = json_encode([
                                    //     "name" => $file_name,
                                    //     "mimeType" => "image/" . $file->getClientOriginalExtension(),
                                    //     "width" => $thumb_size->width,
                                    //     "height" => $thumb_size->height,
                                    // ]);


                                    $bc_arr[$key]['photo'] = $file_name;
                                } else {
                                    return back()->with(["type" => "danger", "flash_message" => "Chỉ được up file có kích thước nhỏ hơn 7mb"]);
                                }
                                break;

                            default:
                                return back()->with(["type" => "danger", "flash_message" => "Tệp tải lên phải là hình ảnh"]);
                                break;
                        }
                    }
                }

                $hoSoXinViec->bang_cap = json_encode($bc_arr);
            }
            $hoSoXinViec->save();
            $hoSoXinViec->informations()->attach($request->information_id);
            $hoSoXinViec->provinces()->attach($request->province_matp);
            DB::commit();
            return back()->with(["type" => "success", "flash_message" => "Tạo hồ sơ thành công"]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("message: " . $e->getMessage() . ' ---- line: ' . $e->getLine());
        }
    }

    public function editProfile($slug, $id)
    {
        $setting = Helper::settings();
        $category_list = Category::with('informations')->where('status', 1)->where('type', 1)->orWhere('type', 0)->orderBy('stt', 'ASC')->get();
        $province_list = Province::get();
        $hoSoXinViec = HoSoXinViec::with('provinces', 'informations')->where('slug', $slug)->where('id', $id)->first();

        if (!empty($hoSoXinViec)) {
            $tin_hoc = json_decode($hoSoXinViec->tin_hoc);
            $bang_cap = json_decode($hoSoXinViec->bang_cap);
            $ngoai_ngu = json_decode($hoSoXinViec->ngoai_ngu);
            $kinh_nghiem = json_decode($hoSoXinViec->kinh_nghiem);

            return view('site.seeker.edit_profile', compact('hoSoXinViec', 'setting', 'province_list', 'category_list', 'tin_hoc', 'bang_cap', 'ngoai_ngu', 'kinh_nghiem'));
        } else {
            abort(404);
        }
    }

    public function updateProfile(Request $request, $id)
    {
        $setting = Helper::settings();
        $rules = [
            "vitri" => "required|max:250",
            "information_id" => "required",
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->with(["type" => "danger", "flash_message" => "Có lỗi xãy ra"]);
        }

        $hoSoXinViec = HoSoXinViec::find($id);
        try {
            DB::beginTransaction();
            $hoSoXinViec->vi_tri = $request->vitri;
            $hoSoXinViec->slug = Str::slug($request->vitri);
            $hoSoXinViec->description = $request->description;
            $hoSoXinViec->updated_at = Carbon::now();
            $hoSoXinViec->kinh_nghiem = !empty($request->data['kinh_nghiem']) ? json_encode($request->data['kinh_nghiem']) : '';
            $hoSoXinViec->ngoai_ngu = !empty($request->data['ngoai_ngu']) ? json_encode($request->data['ngoai_ngu']) : '';
            $hoSoXinViec->tin_hoc = !empty($request->tin_hoc) ? json_encode($request->tin_hoc) : '';
            if ($hoSoXinViec->bang_cap != '') {
                $data_bc = json_decode($hoSoXinViec->bang_cap);
            }
            
            $bang_cap = !empty($request->data['bang_cap']) ? $request->data['bang_cap'] : '';
            $bc_arr = array();
            if (!empty($bang_cap) && is_array($bang_cap)) {
                foreach ($bang_cap as $key => $value) {
                    $bc_arr[$key]['name'] = $value['name'];
                    $bc_arr[$key]['don_vi'] = $value['don_vi'];
                    $bc_arr[$key]['chuyen_nganh'] = $value['chuyen_nganh'];
                    $bc_arr[$key]['loai_tot_nghiep'] = $value['loai'];
                    $bc_arr[$key]['thoi_gian'] = $value['thoi_gian'];

                    if (isset($value['photo']) && !empty($value['photo'])) {

                        if (isset($data_bc) && isset($data_bc[$key]->photo)) {
                            $thumb_img = 'upload/images/hosoxinviec/thumb/' . $data_bc[$key]->photo;
                            if (file_exists($thumb_img)) {
                                unlink($thumb_img);
                            }

                            $large_img = 'upload/images/hosoxinviec/large/' . $data_bc[$key]->photo;
                            if (file_exists($large_img)) {
                                unlink($large_img);
                            }
                        }

                        $file = $value['photo'];
                        // dd($file->getSize());
                        //jpg,jpeg,png,svg,gif
                        switch ($file->getClientOriginalExtension()) {
                            case 'jpg':
                            case 'jpeg':
                            case 'png':
                            case 'svg':
                            case 'gif':
                                if ($file->getSize() <= 7340032) {
                                    $file_name = Str::slug($file->getClientOriginalName(), "-") . "-" . time() . "." . $file->getClientOriginalExtension();
                                    //resize file befor to upload large
                                    if ($file->getClientOriginalExtension() != "svg") {
                                        $image_resize = Image::make($file->getRealPath());
                                        $thumb_size = json_decode($setting["THUMB_SIZE_BANG_CAP"]);
                                        $image_resize->resize($thumb_size->width, $thumb_size->height);
                                        $image_resize->save('upload/images/hosoxinviec/thumb/' . $file_name);
                                    }
                                    // close upload image
                                    $file->move("upload/images/hosoxinviec/large/", $file_name);
                                    $bc_arr[$key]['photo'] = $file_name;
                                } else {
                                    return back()->with(["type" => "danger", "flash_message" => "Chỉ được up file có kích thước nhỏ hơn 7mb"]);
                                }
                                break;

                            default:
                                return back()->with(["type" => "danger", "flash_message" => "Tệp tải lên phải là hình ảnh"]);
                                break;
                        }
                    } else {
                        $bc_arr[$key]['photo'] = $value['photo_temp'];
                    }
                }

                $hoSoXinViec->bang_cap = json_encode($bc_arr);
            }

            $hoSoXinViec->save();

            $hoSoXinViec->informations()->sync($request->information_id);
            $hoSoXinViec->provinces()->sync($request->province_matp);
            DB::commit();
            return redirect()->route('seeker.profile.edit.profile', ['slug' => $hoSoXinViec->slug, 'id' => $hoSoXinViec->id])->with(["type" => "success", "flash_message" => "Cập nhật hồ sơ thành công"]);;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("message: " . $e->getMessage() . '---- line: ' . $e->getLine());
        }
    }

    public function updateStatus(Request $request)
    {
        $id = $request->id;
        $hoSoXinViec = HoSoXinViec::find($id);
        if ($hoSoXinViec) {
            $hoSoXinViec->status = $request->status;
            $hoSoXinViec->updated_at = Carbon::now();

            $hoSoXinViec->save();

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

    public function deleteProfile(Request $request)
    {
        $id = $request->id;
        $hoSoXinViec = HoSoXinViec::find($id);
        if ($hoSoXinViec) {
            $hoSoXinViec->status = $request->status;
            $hoSoXinViec->updated_at = Carbon::now();
            $hoSoXinViec->save();

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

    public function loginGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle()
    {
        $google_user = Socialite::driver('google')->stateless()->user();
        $social = Social::where('provider', 'google')->where('provider_id', $google_user->id)->first();

        if ($social) {
            $user = User::where('id', $social->user_id)->first();
            if ($user) {
                $social->update([
                    'token' => $google_user->token,
                    'refresh_token' => $google_user->refreshToken,
                ]);
                Auth::guard('web')->login($user);

                return redirect()->route('home');
            }
        } else {
            $check_user = User::where('email', $google_user->getEmail())->first();
            if (!$check_user) {
                $user = User::create([
                    'name' => $google_user->getName(),
                    'email' => $google_user->getEmail(),
                    'photo' => $google_user->getAvatar(),
                    'type' => 1,
                    'status' => 1
                ]);

                $user_id = $user->id;
            }

            $socialGoogle = Social::create([
                'provider_id' => $google_user->getId(),
                'token' => $google_user->token,
                'refresh_token' => $google_user->refreshToken,
                'provider' => 'google',
                'user_id' => $user_id
            ]);

            if (!$check_user) {
                Auth::guard('web')->login($user);
            } else {
                Auth::guard('web')->login($check_user);
            }
            return redirect()->route('home');
        }
    }

    public function loginFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function callbackFacebook()
    {
        $provider =  Socialite::driver('facebook')->user();
        $social = Social::where('provider', 'facebook')->where('provider_id', $provider->getId())->first();

        if ($social) {
            $user = User::where('id', $social->user_id)->first();

            if ($user) {
                $social->update([
                    'token' => $provider->token,
                    'refresh_token' => $provider->refreshToken,
                ]);
                Auth::guard('web')->login($user);

                return redirect()->route('home');
            }
        } else {

            $social = new Social([
                "provider" => "facebook",
                "provider_id" => $provider->getId(),
                "token" => $provider->token,
                "refresh_token" => $provider->refreshToken,
            ]);

            $user = User::where('email', $provider->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $provider->getName(),
                    'email' => $provider->getEmail(),
                    'type' => 1,
                    'status' => 1,
                    'photo' => $provider->getAvatar()
                ]);
            }
            $social->users()->associate($user);
            $social->save();
            Auth::guard('web')->login($user);
            return redirect()->route('home');
        }
    }

    public function login()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('home');
        }
        return view('site.seeker.login');
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
            'type' => 1,
        );
        $remember = false;
        if ($request->remember != '') {
            $remember = true;
        }

        if (Auth::guard('web')->attempt($auth, $remember)) {
            $user = User::where('email', $auth['email'])->first();

            if ($user->email_verified_at == '') {
                $token = Str::random(30);
                Cookie::queue('token_seeker', $token, 3);
                $link_register = url("/seeker/confirm-acount?email=" . $user->email . "&token=" . $token);
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
        return view('site.seeker.register');
    }

    public function postRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|max:40',
            'lastName' => 'required|max:40',
            'password' => 'required|min:8',
            'email' => 'required|email|unique:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $token = Str::random(30);
            Cookie::queue('token_seeker', $token, 3);

            $user = User::create([
                'name' => $request->firstName . ' ' . $request->lastName,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'type' => 1,
                'status' => 0,
            ]);

            if ($user) {
                $link_register = url("/seeker/confirm-acount?email=" . $user->email . "&token=" . $token);
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
                    // $mail->attach('/path/to/file');
                });
                return response()->json([
                    'status' => 1,
                    'msg' => 'Đăng ký thành công'
                ], 200);
            }
        }
    }

    public function confirmAccount(Request $request)
    {
        $token = $request->token;
        $email = $request->email;

        $user = User::where("email", $email)->first();

        if (!empty($user) && $token == Cookie::get('token_seeker')) {
            $user->email_verified_at = Carbon::now();
            $user->status = 1;
            if ($user->save()) {
                // Cookie::forget('token_seeker');
                return redirect()->route('seeker.login')->with(["success" => "success", "flash_message" => "Xác thực email thành công"]);
            }
        }
        return redirect()->route('seeker.login')->with(["danger" => "danger", "flash_message" => "Token đã hết hạn"]);
    }

    public function logout()
    {
        Auth::guard('web')->logout();

        if (!auth()->guard('web')->check()) {
            return redirect()->route('home');
        }
    }
}
