<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name','image','status'];   


    function sub_category(){
        return $this->hasOne(Sub_category::class,'category_id');  
    }
}
