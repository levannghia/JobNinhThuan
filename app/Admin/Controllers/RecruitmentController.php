<?php

namespace App\Admin\Controllers;

use App\Models\Employer;
use App\Models\Recruitment;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class RecruitmentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Recruitment';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $states = [
            'on'  => ['value' => 1, 'text' => 'enable', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'disable', 'color' => 'danger'],
        ];
        $grid = new Grid(new Recruitment());

        $grid->column('stt', __('STT'));
        $grid->column('Employers.photo', __('Logo'))->image('',50,50);
        $grid->column('Employers.company_name', __('Đơn vị TT'));
        $grid->column('vi_tri', __('Vị trí'));
        $grid->column('so_luong', __('Số lượng'));
        $grid->column('han_nop', __('Hạn nộp'));
        $grid->column('view', __('View'));
        $grid->column('status', __('Status'))->switch($states);      
        $grid->column('created_at')->display(function(){
            $date = date("d-m-Y", strtotime($this->created_at));
            return $date;
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
        $show = new Show(Recruitment::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('employer_id', __('Employer id'));
        $show->field('vi_tri', __('Vi tri'));
        $show->field('slug', __('Slug'));
        $show->field('so_luong', __('So luong'));
        $show->field('han_nop', __('Han nop'));
        $show->field('description', __('Description'));
        $show->field('yeu_cau', __('Yeu cau'));
        $show->field('quyen_loi', __('Quyen loi'));
        $show->field('ho_so_gom', __('Ho so gom'));
        $show->field('hinh_thuc', __('Hinh thuc'));
        $show->field('hoa_hong_from', __('Hoa hong from'));
        $show->field('hoa_hong_to', __('Hoa hong to'));
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
        $form = new Form(new Recruitment());

        $form->column(1/2,function($form){
            $states = [
                'on'  => ['value' => 1, 'text' => 'enable', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => 'disable', 'color' => 'danger'],
            ];

            $form->multipleSelect('employers','Nhà tuyển dụng')->options(Employer::all()->pluck('company_name','id'));
            $form->text('vi_tri', __('Vị trí'))->attribute(['id' => 'convert_slug'])->rules('required|max:200');
            $form->text('slug', __('Slug'))->creationRules(['required', "unique:recruitments,slug"])
            ->updateRules(['required', "unique:recruitments,slug,{{id}}"]);
            $form->textarea('description', __('Description'));
            $form->textarea('yeu_cau', __('Yêu cầu'));
            $form->textarea('quyen_loi', __('Quyền lợi'));
            $form->switch('status', __('Status'))->states($states);
        });
        $form->column(1/2,function($form){

            $form->datetime('han_nop',__('Hạn nộp'))->format('DD/MM/YYYY HH:mm A')->rules('required|max:200');
            $form->textarea('ho_so_gom', __('Hồ sơ gồm'));
            $form->text('hinh_thuc', __('Hình thức'));
            $form->decimal('hoa_hong_from', __('Hoa hồng'));
            $form->decimal('hoa_hong_to', __('Hoa hong to'));
            $form->number('so_luong', __('Số lượng'));
            $form->number('view', __('View'));
            $form->number('stt', __('STT'))->default(1);
            
        });
        

        return $form;
    }
}
