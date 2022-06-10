<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "social";
    protected $fillable = [
        'provider_id',
        'provider',
        'user_id'
    ];

    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
