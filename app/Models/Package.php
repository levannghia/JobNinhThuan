<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Service;

class Package extends Model
{
    use HasFactory;

    public function services()
    {
        return $this->belongsToMany(Service::class,'package_service','package_id','service_id');
    }
    public function users(){
        return $this->belongsToMany(User::class,'user_package','package_id','user_id');
    }
}
