<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public function Packages()
    {
        return $this->belongsToMany(Package::class,'package_service','service_id','package_id');
    }
}
