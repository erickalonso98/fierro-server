<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revalidation extends Model
{
    use HasFactory;

    protected $table = 'revalidation';

    public function irons(){
        return $this->belongsToMany('App\Models\Iron');
    }
}
