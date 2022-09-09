<?php

namespace App\Admin\Controllers;

use App\Models\Employer;
use App\Models\Province;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Helper;

class EmployerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Employer';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Employer());

        $grid->column('user_id', __('User id'));
        $grid->column('company_name', __('Tên doanh nghiệp'));
        $grid->column('photo', __('Photo'))->image('',50,50);
        $grid->column('provinceAdmin.name', __('Tỉnh/TP'));
        $grid->column('company_phone', __('Company phone'));

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
        $show = new Show(Employer::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('company_name', __('Company name'));
        $show->field('photo', __('Photo'));
        $show->field('maso_thue', __('Maso thue'));
        $show->field('province_matp', __('Province matp'));
        $show->field('quy_mo', __('Quy mo'));
        $show->field('company_phone', __('Company phone'));
        $show->field('description', __('Description'));
        $show->field('address', __('Address'));
        $show->field('name', __('Name'));
        $show->field('phone', __('Phone'));
        $show->field('email', __('Email'));
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
        
        $form = new Form(new Employer());

        $form->column(1/2,function($form){
            $setting = Helper::settings();
            $thumb_size = json_decode($setting["THUMB_SIZE_LOGO_EMPLOYER"]);
            $form->text('company_name', __('Company name'));
            $form->image('photo', __('Photo'))->move('images/employer/thumb')->uniqueName()->removable()->thumbnail('thumb', $thumb_size->width, $thumb_size->height);
            $form->text('maso_thue', __('Mã số thuế'));
            $form->text('address', __('Address'));
            $form->select('province_matp', __('Tinh/TP'))->options(Province::all()->pluck('name','matp'))->rules('required|numeric');
            $form->text('quy_mo', __('Quy mô'));
            $form->text('company_phone', __('Company phone'));
            $form->textarea('description', __('Description'));        
        });

        $form->column(1/2,function($form){
            $form->number('user_id', __('User id'));
            $form->text('name', __('Name'));
            $form->email('email', __('Email'));
            $form->mobile('phone', __('Phone'));
        });
    
        return $form;
    }
}
