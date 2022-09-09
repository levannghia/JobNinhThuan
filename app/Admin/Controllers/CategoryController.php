<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use Carbon\Carbon;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Intervention\Image\ImageManagerStatic as Image;
use Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Category';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Category());
        $grid->filter(function ($filter) {
            // Remove the default id filter
            $filter->disableIdFilter();

            // Add a column filter
            $filter->like('title', 'title');
        });
        $grid->quickSearch(function ($model, $query) {
            $model->where('title', 'like', "%{$query}%");
        });

        $grid->column('order', __('STT'))->sortable();
        $grid->column('title', __('Tiêu đề'))->editable();
        $grid->column('photo')->image('', 70, 70);


        $grid->column('type')->display(function () {
            if ($this->type == 1) {
                $data = '<span class="badge bg-info text-dark">Ứng viên</span>';
            } elseif ($this->type == 2) {
                $data = '<span class="badge bg-warning text-dark">Nhà tuyển dụng</span>';
            } else {
                $data = '<span class="badge bg-success">Cả hai</span>';
            }

            return $data;
        });
        $states = [
            'on' => ['value' => 1, 'text' => 'enable', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => 'disable', 'color' => 'default'],
        ];
        $grid->column('status', 'Hiển thị')->switch($states);
        $grid->column('noi_bat', __('Nổi bật'))->switch($states);
        $grid->column('search', __('Tìm kiếm'))->switch($states);

        $grid->column('show_index', __('Show index'))->display(function () {
            $data = '<input type="radio" id="" class="show-checkbox" name="show_index"
           data-id="' . $this->id . '" data-column="show_index" data-table="categories"
           ' . Helper::checked($this->show_index) . '>';

            return $data;
        });
        $grid->actions(function ($actions) {
            $actions->disableView();
        });
        $grid->paginate(15);
        $grid->model()->orderBy('order', 'ASC');
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
        $show = new Show(Category::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('photo', __('Photo'));

        $show->field('status', __('Status'));
        $show->field('search', __('Search'));
        $show->field('type', __('Type'));
        $show->field('noi_bat', __('Nổi bật'));
        $show->field('show_index', __('Show index'));
        $show->field('order', __('Stt'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {


        $form = new Form(new Category());

        $form->column(1 / 2, function ($form) {
            $states = [
                'on'  => ['value' => 1, 'text' => 'enable', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => 'disable', 'color' => 'danger'],
            ];
            $form->text('title', __('Title'))->attribute(['id' => 'convert_slug'])->rules('required|max:200');
            $form->text('slug', __('Slug'))->creationRules(['required', "unique:categories,slug"])
                ->updateRules(['required', "unique:categories,slug,{{id}}"]);
            $form->select('type', 'Loại')->options([0 => 'Cả 2', 1 => 'Ứng viên', 2 => 'Nhà tuyển dụng']);
            $form->switch('status', 'Hiển thị')->states($states);
            $form->switch('search', 'Tìm kiếm')->states($states);
            $form->switch('noi_bat', 'Nổi bật')->states($states);
            $form->seo('seo_title','SEO TITLE')->attribute(['maxlength' => 100]);
            $form->seo('meta_description','Description')->attribute(['maxlength' => 120]);
            $form->seo('meta_keywords','Keywords')->attribute(['maxlength' => 120]);
           
        });

        $form->column(1 / 2, function ($form) {
            $setting = Helper::settings();
            $thumb_size = json_decode($setting["THUMB_SIZE_CATEGORY"]);
            $form->image('photo', __('Photo'))->move('images/category/thumb')->uniqueName()->removable()->thumbnail('thumb', $thumb_size->width, $thumb_size->height);
            $form->number('order', __('STT'))->default(1);
            
        });

        $form->disableViewCheck();
        return $form;
    }
}
