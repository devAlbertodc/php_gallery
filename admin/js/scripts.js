$(document).ready(function(){

//Declarar variables:
var user_href;
var user_href_splitted;
var user_id;

var image_src;
var image_href_splitted;
var image_name;

var photo_id;

    //Cuando haga click en la imagen del dialogo modal que quiero:
    $(".modal_thumbnails").click(function(){
        //Al item de la imagen, llamamos a un metodo prop.
        //Le pasamos la propiedad que queremos cambiar.
        //Disabled es lo que queremos cambiar, y segundo parametro es el valor:
        $("#set_user_image").prop('disabled',false);

        //$(this).addClass('selected');

        user_href = $("#user-id").prop('href');
    
        //Almacenamos en un array el resultado de separar el href mediante = 
        user_href_splitted = user_href.split("=");
    
        //Sabemos que el id esta en la ultima posicion del array:
        user_id = user_href_splitted[user_href_splitted.length - 1];
    
        //Obtenemos el source de la imagen que se ha clickado:
        image_src = $(this).prop("src");
    
        //Dividimos la ruta de las imagenes separando por /
        image_href_splitted = image_src.split("/");
    
        //Obtenemos el id de la imagen de forma similar a la del usuario: 
        image_name = image_href_splitted[image_href_splitted.length - 1];

        photo_id = $(this).attr("data");

        $.ajax({
            url: "includes/ajax_code.php",
            data: {photo_id:photo_id},
            type: "POST",
            success:function(data){
                if(!data.error){
                    $("#modal_sidebar").html(data);
                }
            }
        });
    });

    //Cuando haga click en el boton para aplicar los cambios despues de seleccionar una imagen:
    $("#set_user_image").click(function(){

        $.ajax({
            url: "includes/ajax_code.php",
            data:{image_name:image_name, user_id:user_id},
            type: "POST",
            success:function(data){
                if(!data.error){
                    //Actualizo la imagen por el contenido actualizado:
                    $(".user_image_box a img").prop('src', data);
                }
            }
        });
    });

    /********** Edit Photo SideBar */
    $(".info-box-header").click(function(){

        //
        $(".inside").slideToggle("fast");

        //Hacemos que se despliegue, si tiene una clase nuestra, se cambia.
        //Si no tiene clase se la indica:
        $("#toggle").toggleClass("glyphicon-menu-down glyphicon-menu-up");
    });

    //Delete function event:
    $(".delete_link").click(function(){
        //Muestra un alert informando al usuario si quiere eliminar.
        //Si cancela no pasa nada, si acepta lo borra
        return confirm("Are you sure you want to delete this item? ");
    });

    //Para poner comentarios en las fotos:
    tinymce.init({selector:'textarea'});
});