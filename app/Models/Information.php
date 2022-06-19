<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;
    public function hoSoXinViec()
    {
        return $this->belongsToMany(HoSoXinViec::class,'hosoxinviec_information','information_id','hosoxinviec_id');
    }
}
