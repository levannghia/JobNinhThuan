<?php

use Carbon\Carbon;
use App\Models\Config;
use App\Models\Employer;
use Illuminate\Support\Facades\Auth;

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

    public static function getStatusValue($status)
    {
        switch ($status) {
            case constStatus::Lock:
                return "Khóa";
                break;
            case constStatus::Delete:
                return "Thùng rác";
                break;
            default:
                return "Kích hoạt";
                break;
        }
    }

    public static function getSelectedValue($key, $value)
    {
        return $key == $value ? 'selected' : '';
    }

    public static function formatDate($date)
    {
        return $date->format('d/m/Y');
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

    public static function getNameEmployer()
    {
        if(auth()->guard('web')->check() && auth()->guard('web')->user()->type == 2){
            $id = auth()->guard('web')->user()->id;
            $name = auth()->guard('web')->user()->name;
            $employer = Employer::where('user_id',$id)->first();

            if($employer && !empty($employer->company_name)){
                return $employer->company_name;
            }else{
                return $name;
            }
        }
    }
}
