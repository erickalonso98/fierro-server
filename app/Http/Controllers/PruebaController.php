<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Person;
use App\Models\Role;
use App\Models\Property;
use App\Models\Iron;

class PruebaController extends Controller
{
    public function index($name = null){
        $text = "Hello in laravel";
        $text.=" Nombre: {$name}";

        return view('welcome',[
            'text' => $text
        ]);
    }

    public function pruebas(){
        /*
        $user = User::find(1);
        $person = Person::find(1);
        echo "<h1>{$person->name}</h1> <hr>";
        echo "<h2>{$person->user->name}</h2>";
        */

        $persons = Person::all();
        $property = Property::find(1);
        $iron = Iron::all();
        //echo var_dump($persons);

        
        foreach($persons as $person){
            echo "<hr/>";
            echo "<h1 style='color:darkgreen;'>{$person->name}</h1>";
            echo "<h1>{$person->curp}</h1>";
            echo "<h1>{$person->rfc}</h1>";
            echo "<h1 style='color:darkblue;' >{$person->user->name}</h1>";
            echo "<hr/>";
        }

        echo "<h1>{$property->person->name}</h1>";
        

        die();        
    }
}
