<?php require_once("includes/header.php");

//Si el usuario ha iniciado sesion se manda a la pagina principal.
//Si intenta ir a la pagina de login aunque ha iniciado sesion, le redirige a la pagina principal:
if($session->is_signed_in()){
    redirect("index.php");
}

//Se comprueba si se ha hecho submit en el formulario:
if(isset($_POST['submit'])){
    //Obtenemos los valores de los formularios:
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    //Metodo para comprobar el usuario en la base de datos:
    //Metodo estatico de la clase user:
    $user_found = user::verify_user($username,$password);

    //Si se ha encontrado el usuario en la base de datos:
    if($user_found){
        $session->login($user_found);
        redirect("index.php");
    }else{
        $the_message = "Your password or username are incorrect";
    }
}else{
    //Si no se ha obtenido nada:
    $username = "";
    $password = "";
    $the_message = "";
}
?>

<div class="col-md-4 cold-md-offset-3">
<h4 class="bg-danger"> <?php echo $the_message ?> </h4>

    <form id="login-id" action="" method="POST">
        <div class="form-group">
            <label for="username"> Username </label>
            <input type="text" class="form-control" name="username" value="<?php echo htmlentities($username)?>">
        </div>

        <div class="form-group">
            <label for="password"> Password </label>
            <input type="password" class="form-control" name="password" value="<?php echo htmlentities($password)?>">
        </div>

        <div class="form-group">
            <input type="submit" name="submit" value="Submit" class="btn btn-primary">
        </div>
    </form>
</div>