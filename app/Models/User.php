<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'phone',
        'photo',
        'type',
        'address',
        'gender',
        'hon_nhan',
        'date_of_birth',
        'province_matp'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function recruitmentApply()
    {
        return $this->belongsToMany(Recruitment::class,'user_recruitment','user_id','recruitment_id')->wherePivot('hoso_id','>','0')->wherePivot('user_id',auth()->id());
    }

    public function recruitmentWishlist()
    {
        return $this->belongsToMany(Recruitment::class,'user_recruitment','user_id','recruitment_id')->wherePivot('wishlist',1)->wherePivot('user_id',auth()->id());
    }

    public function provinces()
    {
        return $this->hasOne(Province::class,'province_matp','matp');
    }

    public function Roles()
    {
        return $this->belongsToMany(Role::class,'role_admin','role_id','admin_id');
    }

    

    // public function checkPermissionAccess($checkPermission)
    // {
    //     $roles = auth()->user()->Roles;
    //     foreach ($roles as $key => $role) {
    //         if($role->permissions->contains('key_code',$checkPermission)){
    //             return true;
    //         }
    //     }
    //     return false;
    // }
}
