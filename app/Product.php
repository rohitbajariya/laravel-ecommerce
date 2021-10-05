<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    function category(){
        return $this->belongsTo(Category::class,'category_id');  
    }
}
