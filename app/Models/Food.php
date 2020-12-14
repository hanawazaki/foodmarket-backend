<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Food extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'ingridient',
        'price',
        'rate',
        'types',
        'description',
        'picture_path'
    ];

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->timestamp;
    }

    public function getUpdatedAtAttribute($value){
        return Carbon::parse($value)->timestamp;
    }

    // public function toArray()
    // {
    //     $toarray = parent::toArray();
    //     $toarray['picture_path']=$this->picture_path;
    //     return $toarray;
    // }

    public function getPicturePathAttribute(){
        return url('') . Storage::url($this->attributes['picture_path']);
    }
}
