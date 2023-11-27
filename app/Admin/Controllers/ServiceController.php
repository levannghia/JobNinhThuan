<?php

namespace App\Admin\Controllers;

use App\Models\Service;
use App\Models\Package;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ServiceController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Service';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Service());

        $states = [
            'on'  => ['value' => 1, 'text' => 'enable', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'disable', 'color' => 'danger'],
        ];

        $grid->column('order', __('STT'))->sortable()->editable();
        $grid->column('title', __('Title'))->editable();
        $grid->column('status', __('Status'))->switch($states);
        $grid->column('created_at', __('Created at'))->display(function(){
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
        $show = new Show(Service::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('status', __('Status'));
        $show->field('order', __('Order'));
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
        $form = new Form(new Service());

        $states = [
            'on'  => ['value' => 1, 'text' => 'enable', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'disable', 'color' => 'danger'],
        ];

        $form->text('title', __('Title'))->rules('required');
        $form->select('key_code',__('Key code'))->options(config('permission_test.service_access'));
        $form->switch('status', __('Status'))->states($states)->default(1);
        $form->number('order', __('Order'))->default(1);

        return $form;
    }
}
