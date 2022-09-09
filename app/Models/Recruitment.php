<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recruitment extends Model
{
    use HasFactory;
    protected $fillable = [
        'vi_tri',
        'slug',
        'so_luong',
        'han_nop',
        'description',
        'yeu_cau',
        'user_id',
        'employer_id',
        'quyen_loi',
        'ho_so_gom',
        'hinh_thuc',
        'hoa_hong_from',
        'hoa_hong_to',
        'status'
    ];

    public function informations()
    {
        return $this->belongsToMany(Information::class,'recruitment_information','recruitment_id','information_id');
    }

    public function provinces()
    {
        return $this->belongsToMany(Province::class,'recruitment_province','recruitment_id','province_matp');
    }

    public function Employers()
    {
        return $this->belongsTo(Employer::class,'employer_id');
    }

    public function hoSoXinViec(){
        return $this->belongsToMany(HoSoXinViec::class,'hosoxinviec_recruitment','hoso_id','recruitment_id');
    }
    public function hoSoApply()
    {
        return $this->belongsToMany(HoSoXinViec::class,'user_recruitment','recruitment_id','hoso_id')->wherePivot('hoso_id','>','0')->wherePivot('recruitment_id','>','0')->withPivot('date_apply')->withTimestamps();
    }
    // public function test($var = [])
    // {
    //     return $this->belongsToMany(Information::class,'recruitment_information','recruitment_id','information_id')
    //             ->wherePivotIn('priority', $var);
    // }
}
