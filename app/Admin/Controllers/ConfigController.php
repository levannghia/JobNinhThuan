<?php

namespace App\Admin\Controllers;

use App\Models\Config;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ConfigController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Setting';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $states = [
            'on' => ['value' => 1, 'text' => 'Dev', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => 'User', 'color' => 'default'],
        ];
        $grid = new Grid(new Config());
        if(config('thongtintuyendung.config.developer') != true){
            $grid->model()->where('type' ,0);
        }
        $grid->column('title', __('Title'));   
        $grid->column('value', __('Value'))->editable();
        if(config('thongtintuyendung.config.developer') == true){
            $grid->column('name', __('Name'));
            $grid->column('type', __('Type'))->switch($states);
        }  
       

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
        $show = new Show(Config::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('name', __('Name'));
        $show->field('value', __('Value'));
        $show->field('type', __('Type'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Config());
        
        $states = [
            'on'  => ['value' => 1, 'text' => 'Dev', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'User', 'color' => 'danger'],
        ];
        $form->text('title',__('Title'));
        $form->textarea('value', __('Value'));
        if(config('thongtintuyendung.config.developer') == true){
            $form->text('name', __('Name'));    
            $form->switch('type', __('Type'))->states($states);
        }
        
       
        return $form;
    }
}
