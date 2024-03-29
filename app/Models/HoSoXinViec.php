<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\Json;

class HoSoXinViec extends Model
{
    use HasFactory;
    use \Encore\Admin\Traits\Resizable;
    protected $table = "hosoxinviec";

    protected $casts = [
        'bang_cap' =>'json',
        'kinh_nghiem'=>'json',
        'ngoai_ngu' => 'json',
        'tin_hoc' => 'json'
    ];

    public function informations()
    {
        return $this->belongsToMany(Information::class,'hosoxinviec_information','hosoxinviec_id','information_id');
    }

    public function provinces()
    {
        return $this->belongsToMany(Province::class,'hosoxinviec_province','hosoxinviec_id','province_matp');
    }

    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
