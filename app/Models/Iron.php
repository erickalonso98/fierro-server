<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iron extends Model
{
    use HasFactory;

    protected $table = 'iron';

    public function person(){
        return $this->belongsTo('App\Models\Person','person_id');
    }

    public function type(){
        return $this->belongsTo('App\Models\Type','iron_type_id');
    }

    public function high(){
        return $this->belongsTo('App\Models\High','high_iron_id');
    }

    public function searchs(){
        return $this->belongsToMany('App\Models\Search');
    }

    public function revalidations(){
        return $this->belongsToMany('App\Models\Revalidation');
    }

    public function lows(){
        return $this->belongsToMany('App\Models\Low');
    }
}
