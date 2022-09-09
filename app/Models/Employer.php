<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company_phone',
        'company_name',
        'province_matp',
        'description',
        'address',
        'maso_thue',
        'user_id',
        'quy_mo'
    ];

    public function provinceAdmin()
    {
        return $this->belongsTo(Province::class,'province_matp','matp');
    }
}
