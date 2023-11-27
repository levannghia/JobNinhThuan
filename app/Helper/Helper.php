<?php

use Carbon\Carbon;
use App\Models\Config;
use App\Models\Employer;
use App\Models\Category;
use App\Models\Information;
use App\Models\Province;

class Helper
{

    public static function getTinHoc()
    {
        return [
            tinHoc::Word => [
                'name' => 'Word',
                'value' => tinHoc::Word,
            ],
            tinHoc::Excel => [
                'name' => 'Excel',
                'value' => tinHoc::Excel,
            ],
            tinHoc::Powerpoint => [
                'name' => 'PowerPoint',
                'value' => tinHoc::Powerpoint,
            ],
            tinHoc::Outlook => [
                'name' => 'Outlook',
                'value' => tinHoc::Outlook,
            ],
        ];
    }

    public static function getEXP()
    {
        return [
            '' => 'Chọn hạng sử dụng',
            eXP::Day => 'Ngày',
            eXP::Month => 'Tháng',
            eXP::Year => 'Năm'
        ];
    }

    public static function getEXPValue($EXP)
    {
        switch ($EXP) {
            case eXP::Day:
                return 'Ngày';
                break;

            case eXP::Month:
                return 'Tháng';
                break;

            default:
                return 'Năm';
                break;
        }
    }

    public static function getGender()
    {
        return [
            '' => 'Chọn giới tính',
            gender::Male => 'Nam',
            gender::Female => 'Nữ',
            gender::Other => 'Khác'
        ];
    }

    public static function getGenderValue($gender)
    {
        switch ($gender) {
            case gender::Male:
                return 'Nam';
                break;

            case gender::Female:
                return 'Nữ';
                break;
                
            default:
                return 'Khác';
                break;
        }
    }

    public static function getHonNhan()
    {
        return [
            honNhan::single => "Độc thân",
            honNhan::Married => "Đã kết hôn"
        ];
    }

    public static function getStatus()
    {
        return [
            '' => 'Select status',
            constStatus::Active => "Kích hoạt",
            constStatus::Lock => "Khóa"
        ];
    }

    public static function getStatusClass($status)
    {
        switch ($status) {
            case constStatus::Delete:
                return "danger";
                break;
            case constStatus::Lock:
                return "warning";
                break;
            default:
                return "success";
                break;
        }
    }

    public static function getStatusValue($status, $id = "")
    {
        switch ($status) {
            case constStatus::Lock:
                return '<span class="badge bg-warning text-dark" data-status-id="' . $id . '" id="status-' . $id . '">Khóa</span';
                break;
            case constStatus::Delete:
                return '<span class="badge bg-danger">Thùng rác</span>';
                break;
            default:
                return '<span class="badge bg-success" data-status-id="' . $id . '" id="status-' . $id . '">Kích hoạt</span>';
                break;
        }
    }

    public static function getSelectedValue($key, $value)
    {
        return $key == $value ? 'selected' : '';
    }

    public static function getCheckedValue($key, $value)
    {
        return $key == $value ? 'checked' : '';
    }

    public static function formatDate($date)
    {
        return Carbon::parse($date)->format('d/m/Y');
    }

    public static function settings()
    {
        $settings = Config::all(['name', 'value'])
            ->keyBy('name')
            ->transform(function ($setting) {
                return $setting->value; // return only the value
            })
            ->toArray();

        return $settings;
    }

    public static function checked($value)
    {
        switch ($value) {
            case 1:
                return 'checked';
                break;
            case 0:
                return '';
                break;
            default:
                return '';
                break;
        }
    }

    public static function getNameEmployer()
    {
        if (auth()->guard('web')->check() && auth()->guard('web')->user()->type == 2) {
            $id = auth()->guard('web')->user()->id;
            $name = auth()->guard('web')->user()->name;
            $employer = Employer::where('user_id', $id)->first();

            if ($employer && !empty($employer->company_name)) {
                return $employer->company_name;
            } else {
                return $name;
            }
        }
    }

    public static function formatCurrency($val, $symbol = 'vi_VN', $r = 0)
    {
        $amount = new \NumberFormatter($symbol, \NumberFormatter::CURRENCY);
        $amount->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $r);

        return $amount->format($val);
    }

    public static function categoryShowIndex(){
        $data_category = Category::where('show_index', 1)->where('status', 1)->orderBy('order', 'ASC')->first();
        $data = Information::withCount('recruitments')->where('category_id',$data_category->id)->where('status',1)->where('noi_bat',1)->get();
        return $data;
    }

    public static function provinceSearch(){
        $province = Province::where('status',1)->get();
        return $province;
    }
}
