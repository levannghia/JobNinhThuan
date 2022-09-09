<?php

namespace App\Admin\Controllers;

use App\Models\HoSoXinViec;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Helper;

class HoSoXinViecController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Hồ sơ xin việc';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $states = [
            'on' => ['value' => 1, 'text' => 'enable', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => 'disable', 'color' => 'default'],
        ];
        $grid = new Grid(new HoSoXinViec());

        $grid->column('stt', __('Stt'));
        $grid->column('users.photo', __('Photo'))->image('',50,50);
        $grid->column('users.name', __('Họ và tên'));
        $grid->column('vi_tri', __('Vi tri'));
        $grid->column('description', __('Description'));
        $grid->column('view', __('View'));
        $grid->column('status', __('Status'))->switch($states);;
        $grid->column('created_at')->display(function(){
            $date = date("d-m-Y", strtotime($this->created_at));
            return $date;
        });
        $grid->column('updated_at')->display(function(){
            $date = date("d-m-Y", strtotime($this->updated_at));
            return $date;
        });

        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(HoSoXinViec::findOrFail($id));

        $show->field('user_id', __('User id'));
        $show->field('vi_tri', __('Vi tri'));
        $show->field('slug', __('Slug'));
        $show->field('bang_cap', __('Bang cap'));
        $show->field('description', __('Description'));
        $show->field('kinh_nghiem', __('Kinh nghiem'));
        $show->field('ngoai_ngu', __('Ngoai ngu'));
        $show->field('tin_hoc', __('Tin hoc'));
        $show->field('view', __('View'));
        $show->field('status', __('Status'));
        $show->field('stt', __('Stt'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new HoSoXinViec());
        
        $form->column(1/2,function($form){
            $states = [
                'on'  => ['value' => 1, 'text' => 'enable', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => 'disable', 'color' => 'danger'],
            ];
            $form->text('vi_tri', __('Vi tri'))->attribute(['id' => 'convert_slug'])->rules('required|max:200');
            $form->text('slug', __('Slug'))->creationRules(['required', "unique:hosoxinviec,slug"])
            ->updateRules(['required', "unique:hosoxinviec,slug,{{id}}"]);
            $form->textarea('description', __('Description'));
            $form->switch('status', __('Status'))->states($states);
            
        });
       
        $form->column(1/2,function($form){
            $form->table('ngoai_ngu','Ngoại ngữ', function ($table) {
                $data = array();
                foreach (config('thongtintuyendung.trinhdo') as $key => $value) {
                    $data[$value['value']] = $value['name'];
                }
                $table->text('ten_ngoai_ngu','Ngoại ngữ');
                $table->select('trinh_do','Trình độ')->options($data);
            });
            $form->number('view', __('View'));
            $form->number('stt', __('STT'))->default(1);
            // $form->embeds('tin_hoc','Tin học', function ($form) {

            //     $form->text('phan_mem_khac','Phần mềm khác');
            //     // $form->textarea('trinh_do');
            //     $form->keyValue('trinh_do',('Trình độ'));
                
            // });
            
            // $form->textarea('tin_hoc', __('Tin hoc'));             
        });
        
        $form->column(12,function($form){
            $form->table('bang_cap','Bằng cấp', function ($table) {
                $setting = Helper::settings();
                $thumb_size = json_decode($setting["THUMB_SIZE_BANG_CAP"]);
                $table->imageNoEdit('photo','Photo');
                $table->textarea('name','Tên Bằng');
                $table->textarea('don_vi','Nơi cấp');
                $table->textarea('chuyen_nganh','Chuyên ngành');
                $table->textarea('loai_tot_nghiep','Loại tốt nghiệp');
                $table->textarea('thoi_gian','Thời gian');
               
            });
        });
        $form->disableViewCheck();
        return $form;
    }
}
