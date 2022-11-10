<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Low extends Model
{
    use HasFactory;

    protected $table = 'low';

    public function irons(){
        return $this->belongsToMany('App\Models\Iron');
    }
}
