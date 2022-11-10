<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $table = 'iron_type';

    public function irons(){
        return $this->hasMany('App\Models\Iron');
    }
}
