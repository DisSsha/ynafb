<?php

namespace App\Models;

class User
{

    
    /*
    * @var name
    */
    private $name;
    
    public function __construct(String $name){
        $this->name = $name;
    }

    public function getNom(){
        return $this->name;
    }

}
