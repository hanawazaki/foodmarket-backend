<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use App\Models\Food;
use App\Models\User;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'food_id',
        'total',
        'quantity',
        'status',
        'payment_url'
    ];

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->timestamp;
    }

    public function getUpdatedAtAttribute($value){
        return Carbon::parse($value)->timestamp;
    }

    public function food(){
        return $this->HasOne(Food::class,'id','food_id');
    }

    public function user(){
        return $this->HasOne(User::class,'id','user_id');
    }


}
