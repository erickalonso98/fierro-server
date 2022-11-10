<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $table = 'property';


    public function exploitation(){
        return $this->belongsTo('App\Models\Exploitation','exploitation_type_id');
    }

    public function tenencia(){
        return $this->belongsTo('App\Models\Tenencia','type_tenencia_id');
    }

    public function person(){
        return $this->belongsTo('App\Models\Person','person_id');
    }

}
