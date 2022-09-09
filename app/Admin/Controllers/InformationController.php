<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use App\Models\information;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Helper;

class InformationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Information';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $data = array();
        
        $states = [
            'on' => ['value' => 1, 'text' => 'enable', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => 'disable', 'color' => 'default'],
        ];
        $grid = new Grid(new information());

        $grid->column('order', __('Stt'))->editable()->sortable();
        $grid->column('name', __('Name'))->editable(); 
        $grid->column('categories.title', __('Tên danh mục'))->sortable();
        $grid->column('status','Hiển thị')->switch($states);
        $grid->column('noi_bat', __('Nổi bật'))->switch($states);
        $grid->actions(function($action){
            $action->disableView();
        });
        $grid->model()->orderBy('order','ASC');
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
        $show = new Show(information::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('category_id', __('Category id'));
        $show->field('name', __('Name'));
        $show->field('photo', __('Photo'));
        $show->field('status', __('Status'));
        $show->field('noi_bat', __('Noi bat'));
        $show->field('order', __('Stt'));
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
        $setting = Helper::settings();
        $thumb_size = json_decode($setting["THUMB_SIZE_INFORMATION"]);

        //$category = Category::select('id','name')->where('status', '>', 0)->get();
        // foreach ($category as $key => $value) { 
        //     $data[$value['id']] = $value['name'];
        // }

        $states = [
            'on'  => ['value' => 1, 'text' => 'enable', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'disable', 'color' => 'danger'],
        ];
        $form = new Form(new information());

        $form->text('name', __('Name'))->rules('required|max:200');
        $form->image('photo', __('Photo'))->move('images/information/thumb')->uniqueName()->removable()->thumbnail('thumb', $thumb_size->width, $thumb_size->height);
        // $form->select('category_id',__('Danh mục'))->options($data);
        $form->select('category_id',__('Danh mục'))->options((new Category())::selectOptions());
        $form->switch('status', 'Hiển thị')->states($states);
        $form->switch('noi_bat', 'Nổi bật')->states($states);
        $form->number('order', __('STT'))->default(1);
        $form->disableViewCheck();

        return $form;
    }
}
