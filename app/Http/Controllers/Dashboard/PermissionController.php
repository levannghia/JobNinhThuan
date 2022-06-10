<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Helper\Recusive;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        $recusive = new Recusive($permission);
        $htmlOption = $recusive->dataRecusive($parent_id = '');
        return view('dashboard.permission.add', compact('htmlOption'));
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
            'name' => 'required|max:40',
            'parent_id' => 'required|numeric',
        ]);
        $permission = new Permission;

        try {
            DB::beginTransaction();
            $permission->name = $request->name;
            $permission->display_name = $request->display_name;
            $permission->parent_id = $request->parent_id;
            $permission->save();

            foreach ($request->module_childrent as $key => $value) {
                Permission::create([
                    'name' => $value,
                    'display_name' => $permission->display_name . ' ' . $value,
                    'parent_id' => $permission->id,
                    'key_code' => $value . '_' . $request->name
                ]);
            }
            DB::commit();

            dd("thanh cong");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("message: ".$e->getMessage(). '---- line: '.$e->getLine());
        }
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
        //
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
        //
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
