<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function session(){
        return $this->hasMany(Session::class);
    }

    public function review(){
        return $this->hasMany(Review::class);
    }
}
