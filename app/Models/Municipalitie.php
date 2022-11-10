<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipalitie extends Model
{
    use HasFactory;

    protected $table = 'municipalities';

    public function state(){
        return $this->belongsTo('App\Models\State','state_id');
    }

    public function locations(){
        return $this->hasMany('App\Models\Location');
    }
}
