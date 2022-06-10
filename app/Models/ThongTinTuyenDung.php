<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThongTinTuyenDung extends Model
{
    use HasFactory;
    protected $table = "thong_tin_tuyen_dung";
    protected $fillable = [
        'name',
        'type',
        'status',
    ];

}
