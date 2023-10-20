<?php 

class conectar{

    static public function conectar(){

        $link = new PDO("mysql:host=127.0.0.1:3306;dbname=web-4b", "soporte", "soporte");

        $link->exec("set names utf8");

        return $link;

    }

}

