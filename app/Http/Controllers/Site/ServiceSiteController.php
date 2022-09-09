<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class ServiceSiteController extends Controller
{
    public function index()
    {
        $package = Package::where('status',1)->get();
        return view('site.service.index',compact('package'));
    }
}
