<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    use HasFactory;

    protected $table = 'search';

    public function irons(){
        return $this->belongsToMany('App\Models\Iron');
    }
}
