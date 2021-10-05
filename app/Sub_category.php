<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sub_category extends Model
{
    protected $fillable = ['name','image','status','category_id'];

    function main_category(){
        return $this->belongsTo(Category::class,"category_id");
    }

}
