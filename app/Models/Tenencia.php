<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenencia extends Model
{
    use HasFactory;

    protected $table = 'type_tenencia';

    public function propertys(){
        return $this->hasMany('App\Models\Property');
    }
}
