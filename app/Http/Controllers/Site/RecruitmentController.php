<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Employer;
use Illuminate\Support\Facades\Mail;
use App\Models\HoSoXinViec;
use App\Models\Recruitment;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RecruitmentController extends Controller
{
    public function index()
    {
        $category_search = Category::where('search', 1)->where('status', 1)->where('type', '!=', 1)->orderBy('order', 'ASC')->get();
        $category_noibat = Category::where('noi_bat', 1)->where('status', 1)->where('type', '!=', 1)->orderBy('order', 'ASC')->get();
        $recruiment_list = Recruitment::where('status', 1)->orderBy('stt', 'ASC')->get();
        return view('site.job.index', compact('category_search', 'recruiment_list', 'category_noibat'));
    }

    public function jobDetail($slug, $id)
    {
        $category_list = Category::with('informations')->where('status', 1)->whereIn('type', [0, 2])->orderBy('order', 'ASC')->get();
        $recruitment = Recruitment::where('slug', $slug)->where('id', $id)->first();
        if ($recruitment) {
            $view = array();
            if (auth()->guard('web')->check() && auth()->guard('web')->user()->type == 1) {
                $check_wishlist = DB::table('user_recruitment')->where('recruitment_id', $recruitment->id)->where('user_id', auth()->guard('web')->user()->id)->first();
                if (!session()->has('viewer') || session('viewer') != $recruitment->id) {
                    $recruitment->view = $recruitment->view + 1;
                    $recruitment->save();
                    session(['viewer' => $recruitment->id]);
                }

                $hoso_list = HoSoXinViec::where('user_id', auth()->guard('web')->user()->id)->whereIn('status', [0, 1])->get();
                if (isset($check_wishlist)) {
                    $view = [
                        'recruitment' => $recruitment,
                        'check_wishlist' => $check_wishlist,
                        'category_list' => $category_list,
                        'hoso_list' => $hoso_list
                    ];
                } else {
                    $view = [
                        'recruitment' => $recruitment,
                        'category_list' => $category_list,
                        'hoso_list' => $hoso_list
                    ];
                }
            } else {
                $view = [
                    'recruitment' => $recruitment,
                    'category_list' => $category_list,
                ];
            }
            return view('site.job.job_detail', $view);
        } else {
            abort(404);
        }
    }

    public function applyForEmail(Request $request, $recruitment_id)
    {

        $rules = [
            "name" => "required",
            "email" => "email|required",
            'file' => 'required|mimes:jpg,png,jpeg,gif,pdf,doc,docx,xlsx|max:5400'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->with(["type" => "danger", "flash_message" =>  "Định dạng file không được hỗ trợ"]);
        }

        $recruitment = Recruitment::find($recruitment_id);
        $employer_email = $recruitment->Employers->email;
        $employer_name = $recruitment->Employers->name;


        if ($request->hasFile('file')) {
            $file = $request->file;
            $file_name = Str::slug($file->getClientOriginalName(), "-") . "-" . time() . "." . $file->getClientOriginalExtension();
            $path =  $file->move("upload/file/cv/", $file_name);
        }

        $data = array(
            "name" => $request->name,
            "content" => $request->content,
            "email" => $request->email,
            "portfolio" => $request->portfolio,
            "path" => $path,
            "employer_email" => $employer_email,
            "employer_name" => $employer_name,
            "title" => config('app.name') . " - Hồ Sơ Ứng Tuyển"
        );
        //Gui mail 
        $send = Mail::send('dashboard.page.email_apply_recruitment', compact('data'), function ($mail) use ($data, $file) {
            $mail->subject($data['title']);
            $mail->to($data['employer_email'], $data['employer_name']);
            $mail->from(config('mail.from.address'), config('mail.from.name'));
            $mail->attach($data['path'], [
                'as' => $file->getClientOriginalName(),
            ]);
        });

        if (file_exists($path)) {
            unlink($path);
            return back()->with(["type" => "success", "flash_message" => "Ứng tuyển thành công!"]);
        }
    }

    public function apply(Request $request, $recruitment_id)
    {
        if (auth()->guard('web')->check()) {
            if (auth()->guard('web')->user()->type == 1) {

                $check_apply = DB::table('user_recruitment')->where('recruitment_id', $recruitment_id)->where('user_id', auth()->guard('web')->user()->id)->first();
                if ($check_apply && $check_apply->hoso_id == null) {
                    $update_apply = DB::table('user_recruitment')->where('recruitment_id', $recruitment_id)->where('user_id', auth()->guard('web')->user()->id)->update([
                        'hoso_id' => $request->hoso_id,
                    ]);

                    if ($update_apply) {
                        return response()->json([
                            'status' => 1,
                            'msg' => 'Ứng tuyển thành công'
                        ], 201);
                    }
                } elseif ($check_apply && $check_apply->hoso_id != null) {
                    return response()->json([
                        'status' => 0,
                        'msg' => 'Bạn đã ứng tuyển công việc này'
                    ], 203);
                } else {
                    $apply =  DB::table('user_recruitment')->insert([
                        'recruitment_id' => $recruitment_id,
                        'hoso_id' => $request->hoso_id,
                        'user_id' => auth()->guard('web')->user()->id,
                        'date_apply' =>  Carbon::now(),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    return response()->json([
                        'status' => 1,
                        'msg' => 'Ứng tuyển thành công'
                    ], 201);
                }
            } else {
                return response()->json([
                    'status' => 0,
                    'msg' => 'Chức năng này chỉ dành cho người tìm việc'
                ], 203);
            }
        } else {
            return response()->json([
                'status' => 0,
                'msg' => 'Đăng nhập để tiếp tục'
            ], 503);
        }
    }

    public function searchInformation(Request $request)
    {
        $data = '';
        $category_noibat = Category::where('noi_bat', 1)->where('status', 1)->where('type', '!=', 1)->orderBy('order', 'ASC')->get();
        if ($request->information != null && is_array($request->information)) {
            $recruiment_list = DB::table('recruitments')
                ->select('recruitments.*', 'employers.company_name', 'employers.photo AS logo')
                ->join('recruitment_information', 'recruitments.id', '=', 'recruitment_information.recruitment_id')
                ->join('employers', 'recruitments.employer_id', '=', 'employers.id')
                ->whereIn('recruitment_information.information_id', $request->information)->get();

            // dd($recruiment_list);

            foreach ($recruiment_list as $item) {
                $data .=           '<div class="job-item p-4 mb-4">
                                        <div class="row g-4">
                                            <div class="col-sm-12 col-md-8 d-flex align-items-center">
                                                <img class="flex-shrink-0 img-fluid border rounded"
                                                    src="/upload/images/employer/thumb/' . $item->logo . '"
                                                    alt="" style="width: 80px; height: 80px;">
                                                <div class="text-start ps-4">
                                                    <a href="' . route('recruitment.job.detail', ['slug' => $item->slug, 'id' => $item->id]) . '" title="' . $item->vi_tri . '"><h5 class="mb-3">' . $item->vi_tri . '</h5></a>
                                                    <span class="text-truncate me-3"><i
                                                            class="fa fa-map-marker-alt text-primary me-2"></i>';
                $check_province = DB::table('recruitment_province')->select('provinces.*')
                    ->join('provinces', 'recruitment_province.province_matp', '=', 'provinces.matp')
                    ->where('recruitment_id', $item->id)->get();
                foreach ($check_province as $stt => $province) {
                    $data .= $province->name;
                    if (count($check_province) != $stt + 1) {
                        $data .= ', ';
                    }
                }
                $check_information = DB::table('recruitment_information')->where('recruitment_id', $item->id)->get();
                $data .= '</span>';
                foreach ($category_noibat as $category) {
                    foreach ($category->informations as $info) {


                        foreach ($check_information as $value) {
                            if ($value->information_id == $info->id) {
                                $data .= '<span class="text-truncate me-3"><i
                                                                            class="fas fa-dot-circle text-primary me-2"></i>' . $info->name . '</span>';
                            }
                        }
                    }
                }
                $data .= '</div>
                                            </div>
                                            <div
                                                class="col-sm-12 col-md-4 d-flex flex-column align-items-start align-items-md-end justify-content-center">
                                                <div class="d-flex mb-3">';

                if (auth()->check() && auth()->user()->type == 1) {
                    $id_user = auth()->user()->id;
                    $check_wishlist = DB::table('user_recruitment')
                        ->where('recruitment_id', $item->id)
                        ->where('user_id', $id_user)
                        ->first();
                }
                if (isset($check_wishlist) && $check_wishlist->wishlist == 1) {
                    $data .= '<a class="fas btn btn-light btn-square me-3"
                                                            data-employer-name="' . $item->company_name . '"
                                                            data-add-wishlist="' . $item->id . '"
                                                            data-vitri="' . $item->vi_tri . '" href="" id="btn_wishlist_' . $item->id . '">
                                                            <i id="icon_wishlist_' . $item->id . '"
                                                                class="far fa-heart text-primary"></i></a>';
                } else {
                    $data .= '<a class="far btn btn-light btn-square me-3"
                                                            data-employer-name="' . $item->company_name . '"
                                                            data-add-wishlist="' . $item->id . '"
                                                            data-vitri="' . $item->vi_tri . '" href="" id="btn_wishlist_' . $item->id . '">
                                                            <i id="icon_wishlist_' . $item->id . '"
                                                                class="far fa-heart text-primary"></i></a>';
                }

                $data .= '<a class="btn btn-primary" href="">Apply Now</a>
                                                </div>
                                                <small class="text-truncate"><i
                                                        class="far fa-calendar-alt text-primary me-2"></i>Date Line:
                                                    ' . $item->han_nop . '</small>
                                            </div>
                                        </div>
                                    </div>';
            }
        } else {
            $recruiment_list = Recruitment::where('status', 1)->orderBy('stt', 'ASC')->get();
            foreach ($recruiment_list as $item) {
                $data .=           '<div class="job-item p-4 mb-4">
                                        <div class="row g-4">
                                            <div class="col-sm-12 col-md-8 d-flex align-items-center">
                                                <img class="flex-shrink-0 img-fluid border rounded"
                                                    src="/upload/images/employer/thumb/' . $item->Employers->photo . '"
                                                    alt="" style="width: 80px; height: 80px;">
                                                <div class="text-start ps-4">
                                                    <a href="' . route('recruitment.job.detail', ['slug' => $item->slug, 'id' => $item->id]) . '" title="' . $item->vi_tri . '">
                                                        <h5 class="mb-3">' . $item->vi_tri . '</h5>
                                                    </a>
                                                    <span class="text-truncate me-3"><i
                                                            class="fa fa-map-marker-alt text-primary me-2"></i>';
                foreach ($item->provinces as $stt => $province) {
                    $data .= $province->name;
                    if (count($item->provinces) != $stt + 1) {
                        $data .= ', ';
                    }
                }
                $data .= '</span>';
                foreach ($category_noibat as $category) {
                    foreach ($category->informations as $info) {
                        if ($item->informations->contains('id', $info->id)) {
                            $data .= '<span class="text-truncate me-3"><i
                                                                        class="fas fa-dot-circle text-primary me-2"></i>' . $info->name . '</span>';
                        }
                    }
                }
                $data .= '</div>
                                            </div>
                                            <div
                                                class="col-sm-12 col-md-4 d-flex flex-column align-items-start align-items-md-end justify-content-center">
                                                <div class="d-flex mb-3">';

                if (auth()->check() && auth()->user()->type == 1) {
                    $id_user = auth()->user()->id;
                    $check_wishlist = DB::table('user_recruitment')
                        ->where('recruitment_id', $item->id)
                        ->where('user_id', $id_user)
                        ->first();
                }

                if (isset($check_wishlist) && $check_wishlist->wishlist == 1) {
                    $data .= '<a class="fas btn btn-light btn-square me-3"
                                                            data-employer-name="' . $item->Employers->company_name . '"
                                                            data-add-wishlist="' . $item->id . '"
                                                            data-vitri="' . $item->vi_tri . '" href=""
                                                            id="btn_wishlist_' . $item->id . '">
                                                            <i id="icon_wishlist_' . $item->id . '"
                                                                class="fas fa-heart text-primary"></i></a>
                                                        <a class="btn btn-primary" href="">Apply Now</a>';
                } else {
                    $data .= '<a class="far btn btn-light btn-square me-3"
                                                            data-employer-name="' . $item->Employers->company_name . '"
                                                            data-add-wishlist="' . $item->id . '"
                                                            data-vitri="' . $item->vi_tri . '" href=""
                                                            id="btn_wishlist_' . $item->id . '">
                                                            <i id="icon_wishlist_' . $item->id . '"
                                                                class="far fa-heart text-primary"></i></a>
                                                        <a class="btn btn-primary" href="">Apply Now</a>';
                }


                $data .= '</div>
                                                <small class="text-truncate"><i
                                                        class="far fa-calendar-alt text-primary me-2"></i>Date Line:
                                                    ' . $item->han_nop . '</small>
                                            </div>
                                        </div>
                                    </div>';
            }
        }
        return $data;
    }

    public function updateApply(Request $request)
    {
        if (auth()->guard('web')->check()) {
            if (auth()->guard('web')->user()->type == 1) {
                $user_id = auth()->guard('web')->user()->id;
                $apply = DB::table('user_recruitment')->where('user_id', $user_id)->where('recruitment_id', $request->id)->update([
                    'hoso_id' => 0,
                    'updated_at' => Carbon::now(),
                ]);

                return response()->json([
                    'status' => 1,
                    'msg' => 'Đã hủy ứng tuyển việc làm'
                ], 200);
            }
        } else {
            return response()->json([
                'status' => 0,
                'msg' => 'Bạn không có quyền'
            ], 403);
        }
    }

    public function wishList(Request $request)
    {

        if (auth()->guard('web')->check()) {
            if (auth()->guard('web')->user()->type == 1) {
                $user_id = auth()->guard('web')->user()->id;
                $wishlist = DB::table('user_recruitment')->where('user_id', $user_id)->where('recruitment_id', $request->id)->first();
                if (!$wishlist) {
                    $wishlist = DB::table('user_recruitment')->insert([
                        'user_id' => $user_id,
                        'recruitment_id' => $request->id,
                        'wishlist' => $request->wishlist,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                } else {
                    $wishlist = DB::table('user_recruitment')->where('user_id', $user_id)->where('recruitment_id', $request->id)->update([
                        'wishlist' => $request->wishlist,
                        'updated_at' => Carbon::now()
                    ]);
                }


                if ($wishlist) {
                    return response()->json([
                        'status' => 1,
                        'msg' => 'Cập nhật việc làm thành công'
                    ], 201);
                } else {
                    return response()->json([
                        'status' => 0,
                        'msg' => 'Lưu việc làm thất bại'
                    ], 500);
                }
            } else {
                return response()->json([
                    'status' => 0,
                    'msg' => 'Chức năng này chỉ dành cho người tìm việc'
                ], 203);
            }
        } else {
            return response()->json([
                'status' => 2,
                'msg' => 'Vui lòng đăng nhập để tiếp tục'
            ]);
        }
    }
}
