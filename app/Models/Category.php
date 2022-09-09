<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Traits\ModelTree;

class Category extends Model
{
    use \Encore\Admin\Traits\Resizable;
    use ModelTree;
    use HasFactory;

    public function informations()
    {
        return $this->hasMany(Information::class,'category_id');
    }
}
