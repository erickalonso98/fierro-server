<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class High extends Model
{
    use HasFactory;

    protected $table = 'high';

    public function irons(){
        return $this->hasMany('App\Models\Iron');
    }
}
