<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function event(){
        return $this->belongsTo(Event::class);
    }

    public function user(){
        return $this->belongsToMany(User::class)->withTimeStamps();
    }
}
