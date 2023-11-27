<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Recruitment;
use Illuminate\Http\Request;
use App\Models\Category;

class HomeSiteController extends Controller
{
    public function index()
    {
        $category_noibat = Category::with('informations')->where('noi_bat', 1)->where('status', 1)->where('type', '!=', 1)->orderBy('order', 'ASC')->get();
        $hot_recruitment = Recruitment::with('Employers','provinces','informations')->where('hot',1)->where('status',1)->orderBy('stt','ASC')->paginate(5);
        $urgent_recruitment = Recruitment::with('Employers','provinces','informations')->where('urgent',1)->where('status',1)->orderBy('stt','ASC')->paginate(5);
        $recruitment = Recruitment::with('Employers','provinces','informations')->where('status',1)->orderBy('stt','ASC')->paginate(5);
        return view('site.home.index',compact('hot_recruitment','urgent_recruitment','recruitment','category_noibat'));
    }
}
