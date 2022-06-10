<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoSoXinViec extends Model
{
    use HasFactory;
    protected $table = "hosoxinviec";

    public function informations()
    {
        return $this->belongsToMany(Information::class,'hosoxinviec_information','hosoxinviec_id','information_id');
    }

    public function provinces()
    {
        return $this->belongsToMany(Province::class,'hosoxinviec_province','hosoxinviec_id','province_matp');
    }
}
