<?php

namespace App\Admin\Controllers;

use App\Models\Package;
use App\Models\Service;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Helper;
use Illuminate\Support\Facades\DB;

class PackageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Package';

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
        $grid = new Grid(new Package());

        $grid->column('order', __('STT'))->sortable()->editable();
        $grid->column('title', __('Title'))->editable();
        $grid->column('description', __('Description'));
        $grid->column('status', __('Status'))->switch($states);
        $grid->column('price', __('Price'))->sortable();
        $grid->column('new_price', __('New price'))->sortable();
        $grid->column('expiry', __('EXP'))->display(function(){
            return Helper::getEXPValue($this->expiry);
        });
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
        $show = new Show(Package::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('description', __('Description'));
        $show->field('status', __('Status'));
        $show->field('order', __('Order'));
        $show->field('price', __('Price'));
        $show->field('new_price', __('New price'));
        $show->field('sale', __('Sale'));
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

        $states = [
            'on'  => ['value' => 1, 'text' => 'enable', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'disable', 'color' => 'danger'],
        ];
        
        $form = new Form(new Package());

        $form->text('title', __('Title'))->rules('required|max:100');
        $form->textarea('description', __('Description'));
        $form->multipleSelect('services',__('Service'))->options(Service::all()->pluck('title','id'))->rules('required');
        $form->switch('status', __('Status'))->states($states)->default(1);
        $form->currency('price', __('Price'))->rules('required');
        $form->currency('new_price', __('New price'));
        $form->select('expiry', __('EXP'))->options(Helper::getEXP())->rules('required');
        $form->number('order', __('Order'))->default(1);

        return $form;
    }
}
