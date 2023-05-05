<?php 

include("includes/init.php");

    //Si el usuario no ha iniciado sesion:
    if(!$session->is_signed_in()){
        //Llamamos a la funcion que hemos creado en functions.php y le decimos la ruta:
        redirect("login.php");
    }

    //Si no encontramos el id para esa imagen, y el get viene vacio, entonces redirigimos
    if(empty($_GET['id'])){
        redirect("photos.php");
    }

//Buscamos si el id que hemos obtenido tiene alguna foto asociada.
$photo = Photo::find_by_id($_GET['id']);

//Si la foto existe, entonces la eliminamos, en caso contrario, pues se redirige a la ventana del listado de fotos:
if($photo){
    $photo->delete_photo();
    $session->message("The {$photo->filename} photo has been deleted");

    redirect("photos.php");
}else{
    redirect("photos.php");
}
 ?>