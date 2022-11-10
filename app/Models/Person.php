<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;
    
    protected $table = 'datapersons';

    protected $fillable = [
        'name',
        'surname',
        'lastname',
        'description',
        'state_id',
        'code_postal',
        'curp',
        'rfc',
        'ine',
        'age',
        'image',
        'phone',
        'email'
    ];

 
    public function user(){
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function property(){
        return $this->hasOne('App\Models\Property');
    }

    public function iron(){
        return $this->hasOne('App\Models\Iron');
    }

    public function state(){
        return $this->belongsTo('App\Models\State','state_id');
    }

}
