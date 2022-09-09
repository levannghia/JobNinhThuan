<?php

namespace App\Admin\Controllers;

use App\Models\Province;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Helper;

class SeekerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Seeker';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());
        $states = [
            'on' => ['value' => 1, 'text' => 'enable', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => 'disable', 'color' => 'default'],
        ];

        $grid->model()->where('type', 1);
        $grid->column('photo', __('Photo'))->image('', 50, 50);
        $grid->column('name', __('Name'));
        $grid->column('email', __('Email'));
        $grid->column('phone', __('Phone'));

        $grid->column('gender')->display(function () {
            return Helper::getGenderValue($this->gender);
        });
        $grid->column('address', __('Address'));
        $grid->column('provinceAdmin.name', __('Tỉnh/TP'));
        $grid->column('status', __('Status'))->switch($states);
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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('photo', __('Photo'));
        $show->field('email', __('Email'));
        $show->field('email_verified_at', __('Email verified at'));
        $show->field('phone', __('Phone'));
        $show->field('gender', __('Gender'));
        $show->field('province_matp', __('Province matp'));
        $show->field('hon_nhan', __('Hon nhan'));
        $show->field('address', __('Address'));
        $show->field('date_of_birth', __('Date of birth'));
        $show->field('type', __('Type'));
        $show->field('status', __('Status'));
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
        $thumb_size = json_decode($setting["THUMB_SIZE_USER"]);
        $states = [
            'on'  => ['value' => 1, 'text' => 'enable', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'disable', 'color' => 'danger'],
        ];
        $form = new Form(new User());

        $form->text('name', __('Name'))->rules('required|max:100');
        $form->image('photo', __('Photo'))->move('images/seeker/thumb')->uniqueName()->removable()->thumbnail('thumb', $thumb_size->width, $thumb_size->height);
        $form->email('email', __('Email'))->rules('required|max:100');
        // $form->datetime('email_verified_at', __('Email verified at'))->default(date('Y-m-d H:i:s'));
        $form->mobile('phone', __('Phone'));
        $form->number('gender', __('Gender'));
        $form->date('date_of_birth', __('Date of birth'))->format('DD/MM/YYYY');
        // $form->select('province_matp', 'Tỉnh/TP')->options(function ($id) {
        //     $province = Province::find($id);

        //     if ($province) {
        //         return [$province->matp =>  $province->name];
        //     }
        // })->ajax('/admin/api/provinces');
        $form->text('address', __('Address'));
        $form->select('province_matp','Tỉnh/TP')->options(Province::all()->pluck('name','matp'));
        $form->select('hon_nhan', __('Hôn nhân'))->options(Helper::getHonNhan());    
        $form->switch('status',__('Status'))->states($states);

        return $form;
    }

    public function provinces(Request $request)
    {
        $q = $request->get('q');

        return Province::where('name', 'like', "%$q%")->paginate(null, ['matp', 'name as text']);
    }
}
