<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ThongTinTuyenDung;
use Illuminate\Http\Request;

class ThongTinTuyenDungController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $thong_tin_TD_list = ThongTinTuyenDung::get();
        return view('dashboard.thongtintuyendung.index',compact('thong_tin_TD_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.thongtintuyendung.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'type' => 'required',
        ]);

        $status = 0;
        if($request->status  == 'on'){
            $status = 1;
        }
        $thong_tin_TD = ThongTinTuyenDung::create([
            'name' => $request->name,
            'type' => $request->type,
            'status' => $status
        ]);

        dd("thanh cong");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $thong_tin_TD = ThongTinTuyenDung::findOrFail($id);

        return view('dashboard.thongtintuyendung.edit',compact('thong_tin_TD'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $thong_tin_TD = ThongTinTuyenDung::find($id);

        $this->validate($request, [
            'name' => 'required|max:255',
            'type' => 'required',
        ]);

        $status = 0;
        if($request->status  == 'on'){
            $status = 1;
        }

        $thong_tin_TD->name = $request->name;
        $thong_tin_TD->type = $request->type;
        $thong_tin_TD->status = $status;

        if($thong_tin_TD->save()){
            dd("thanh cong");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
