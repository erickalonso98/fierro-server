<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $table = 'states';

    public function municipalitys(){
        return $this->hasMany('App\Models\Municipality');
    }

    public function persons(){
        return $this->hasMany('App\Models\Person');
    }
}
