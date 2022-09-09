<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FunctionController extends AdminController
{
    public function statusColumn(Request $request)
    {
        $table = $request->table;
        $item = array();
        $data = DB::table($table)->where('id',$request->id)->first();
        $value = 0;
        if ($data) {
            switch ($request->column) {
                case 'show_index':
                    if ($data->show_index > 0) {
                        $value = 0;
                    } else {
                        $value = 1;
                    }
                    $data2 = DB::table('categories')->where('show_index',1)->update([
                        'show_index' => 0,
                    ]);
                    $item['show_index'] = $value;
                    break;
                case 'search':
                    if ($data->search > 0) {
                        $value = 0;
                    } else {
                        $value = 1;
                    }
                    $item['search'] = $value;
                    break;

                case 'noi_bat':
                    if ($data->noi_bat > 0) {
                        $value = 0;
                    } else {
                        $value = 1;
                    }
                    $item['noi_bat'] = $value;
                    break;
                default:
                    if ($data->status > 0) {
                        $value = 0;
                    } else {
                        $value = 1;
                    }
                    $item['status'] = $value;
                    break;
            }
            if(DB::table($table)->where('id',$request->id)->update($item)){
                return response()->json([
                    'status' => 1,
                    'msg' => 'Cập nhật thành công'
                ],201);
            }  
        }

        return response()->json([
            'status' => 0,
            'msg' => 'Cập nhật thất bại'
        ]);
    }
}