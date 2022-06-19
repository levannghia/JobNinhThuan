<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HoSoXinViec;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Helper;

class HoSoSiteController extends Controller
{
    public function index()
    {
        $category_search = Category::where('search', 1)->where('status', 1)->where('type', '!=', 2)->orderBy('stt', 'ASC')->get();
        $category_noibat = Category::where('noi_bat', 1)->where('status', 1)->where('type', '!=', 2)->orderBy('stt', 'ASC')->get();
        $hoso_list = HoSoXinViec::where('status', 1)->orderBy('stt', 'ASC')->get();
        return view('site.hoso.index', compact('category_search', 'hoso_list', 'category_noibat'));
    }

    public function hoSoDetail($slug, $id)
    {
        $category_list = Category::with('informations')->where('status', 1)->where('type', 1)->orWhere('type', 0)->orderBy('stt', 'ASC')->get();
        $hoSo = HoSoXinViec::where('slug', $slug)->where('id', $id)->first();

        if ($hoSo) {
            $view = array();
            if (auth()->guard('web')->check() && auth()->guard('web')->user()->type == 1) {
                $check_flow = DB::table('user_recruitment')->where('ho_id', $hoSo->id)->where('user_id', auth()->guard('web')->user()->id)->first();
                if (!session()->has('view_emloyer') || session('view_emloyer') != $hoSo->id) {
                    $hoSo->view = $hoSo->view + 1;
                    $hoSo->save();
                    session(['view_emloyer' => $hoSo->id]);
                }


                if (isset($check_flow)) {
                    $view = [
                        'hoSo' => $hoSo,
                        'check_flow' => $check_flow,
                        'category_list' => $category_list,
                    ];
                }
            } else {
                $view = [
                    'hoSo' => $hoSo,
                    'category_list' => $category_list,
                ];
            }
            return view('site.hoso.hoso_detail', $view);
        } else {
            abort(404);
        }
    }

    public function searchInformation(Request $request)
    {
        $data = '';
        $category_noibat = Category::where('noi_bat', 1)->where('status', 1)->where('type', '!=', 2)->orderBy('stt', 'ASC')->get();

        if ($request->information != null && is_array($request->information)) {
            $hoso_list = HoSoXinViec::where('hosoxinviec.status', 1)->distinct()->select('hosoxinviec.*')
                ->join('hosoxinviec_information', 'hosoxinviec.id', 'hosoxinviec_information.hosoxinviec_id')
                ->whereIn('hosoxinviec_information.information_id', $request->information)
                ->orderBy('hosoxinviec.stt', 'ASC')->get();


            foreach ($hoso_list as $key => $hoso) {
                if (auth()->check() && auth()->user()->type == 2) {
                    $id_user = auth()->user()->id;
                    $check_flow = DB::table('user_recruitment')
                        ->where('hoso_id', $hoso->id)
                        ->where('user_id', $id_user)
                        ->first();
                }
                $data .= '<tr>';
                if (isset($check_flow) && $check_flow->flow_user == 1) {
                    $data .= ' <th scope="row"><i data-add-flow="' . $hoso->id . '"
                   data-vitri="' . $hoso->vi_tri . '" id="star_' . $hoso->id . '" class="fas fa-star"></i></th>';
                } else {
                    $data .= ' <th scope="row"><i data-add-flow="' . $hoso->id . '"
                    data-vitri="' . $hoso->vi_tri . '" id="star_' . $hoso->id . '" class="far fa-star"></i></th>';
                }
                $data .= '<td>
                    <a class="name-hoso" href="'.route('hoso.detail',['slug'=>$hoso->slug,'id'=>$hoso->id]).'">' . $hoso->vi_tri . '</a>
                    <div class="info-more">
                        <span>- Update: ' . Helper::formatDate($hoso->updated_at) . '</span>
                        <span>- View: ' . $hoso->view . '</span>
                    </div>
                </td>
                <td>';
                foreach ($hoso->provinces as $stt => $province) {
                    $data .= $province->name;
                    if (count($hoso->provinces) != $stt + 1) {
                        $data .= ', ';
                    }
                }
                $data .= '</td>';
                foreach ($category_noibat as $category) {
                    foreach ($category->informations as $info) {
                        if ($hoso->informations->contains('id', $info->id)) {
                            $data .= '<td>' . $info->name . '</td>';
                        }
                    }
                }

                $data .= '</tr>';
            }
        } else {
            $hoso_list = HoSoXinViec::where('status', 1)->orderBy('stt', 'ASC')->get();


            foreach ($hoso_list as $key => $hoso) {
                if (auth()->check() && auth()->user()->type == 2) {
                    $id_user = auth()->user()->id;
                    $check_flow = DB::table('user_recruitment')
                        ->where('hoso_id', $hoso->id)
                        ->where('user_id', $id_user)
                        ->first();
                }
                $data .= '<tr>';
                if (isset($check_flow) && $check_flow->flow_user == 1) {
                    $data .= ' <th scope="row"><i data-add-flow="' . $hoso->id . '"
                    data-vitri="' . $hoso->vi_tri . '" id="star_' . $hoso->id . '" class="fas fa-star"></i></th>';
                } else {
                    $data .= ' <th scope="row"><i data-add-flow="' . $hoso->id . '"
                     data-vitri="' . $hoso->vi_tri . '" id="star_' . $hoso->id . '" class="far fa-star"></i></th>';
                }
                $data .= '<td>
                <a class="name-hoso" href="'.route('hoso.detail',['slug'=>$hoso->slug,'id'=>$hoso->id]).'">' . $hoso->vi_tri . '</a>
                <div class="info-more">
                    <span>- Update: ' . Helper::formatDate($hoso->updated_at) . '</span>
                    <span>- View: ' . $hoso->view . '</span>
                </div>
            </td>
            <td>';
                foreach ($hoso->provinces as $stt => $province) {
                    $data .= $province->name;
                    if (count($hoso->provinces) != $stt + 1) {
                        $data .= ', ';
                    }
                }
                $data .= '</td>';
                foreach ($category_noibat as $category) {
                    foreach ($category->informations as $info) {
                        if ($hoso->informations->contains('id', $info->id)) {
                            $data .= '<td>' . $info->name . '</td>';
                        }
                    }
                }

                $data .= '</tr>';
            }
        }
        return $data;
    }

    public function flowUser(Request $request)
    {

        if (auth()->guard('web')->check()) {
            if (auth()->guard('web')->user()->type == 2) {
                $user_id = auth()->guard('web')->user()->id;
                $flow_user = DB::table('user_recruitment')->where('user_id', $user_id)->where('hoso_id', $request->id)->first();
                if (!$flow_user) {
                    $flow_user = DB::table('user_recruitment')->insert([
                        'user_id' => $user_id,
                        'hoso_id' => $request->id,
                        'flow_user' => $request->flow_user,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                } else {
                    $flow_user = DB::table('user_recruitment')->where('user_id', $user_id)->where('hoso_id', $request->id)->update([
                        'flow_user' => $request->flow_user,
                        'updated_at' => Carbon::now()
                    ]);
                }


                if ($flow_user) {
                    return response()->json([
                        'status' => 1,
                        'msg' => 'Lưu hồ sơ thành công'
                    ], 201);
                } else {
                    return response()->json([
                        'status' => 0,
                        'msg' => 'Lưu hồ sơ thất bại'
                    ], 500);
                }
            } else {
                return response()->json([
                    'status' => 0,
                    'msg' => 'Chức năng này chỉ dành cho nhà tuyển dụng'
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
