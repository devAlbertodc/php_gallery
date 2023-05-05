<?php

//Metodo que carga las clases a las que no se les haya hecho un include
function classAutoLoader($class){

    //Pasamos a minusculas el parametro recibido:
    $class = strtolower($class);
    //Dentro de la carpeta de includes ponemos el parametro class
    $the_path = "includes/{$class}.php";

    //Si el directorio es un fichero y la clase no existe se hace un include del fichero:
    if(is_file($the_path) && !class_exists($class)){
        include $the_path;
    }
}

//Funcion generica para redirigir una pagina web.
//La funcion recibe la pagina a la que se quiere redirigir.
function redirect($location){
    header("Location: {$location}");
}

spl_autoload_register('classAutoLoader');

?>