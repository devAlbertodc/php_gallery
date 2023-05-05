<?php
    include("includes/header.php");
    include("includes/photo_library_modal.php"); 

    //Si el usuario no ha iniciado sesion:
    if(!$session->is_signed_in()){
        //Llamamos a la funcion que hemos creado en functions.php y le decimos la ruta:
        redirect("login.php");
    }

    //Si no obtenemos el id de una foto se manda a la pagina con el listado de las fotos:
    if(empty($_GET['id'])){
        redirect("users.php");
    }

    $user = User::find_by_id($_GET['id']);

    //Si se ha pulsado el boton de actualizar:
    if(isset($_POST['update'])){
        
        //Si el objeto user existe asignamos valores obtenidos por $_post
        if($user){
            $user->username = $_POST['username'];
            $user->password = $_POST['password'];
            $user->first_name = $_POST['first_name'];
            $user->last_name = $_POST['last_name'];

            //Si el fichero esta vacio:
            if(empty($_FILES['user_image'])){
                $user->save();
                redirect("users.php");
                $session->message("The {$user->username} user data has been updated successfully");

            }else{
                //Si hay contenido:
                $user->set_file($_FILES['user_image']);

                //Llamamos al metodo guardar de la clase user:
                $user->upload_photo();
                $user->save();

                $session->message("The {$user->username} user data has been updated successfully");

                //Redirige a la misma pagina con la imagen actualizada:
                //redirect("edit_user.php?id={$user->id}");
                redirect("users.php");

            }
        }
    }

    if(isset($_POST['delete'])){
        $user->delete();
        redirect("users.php");
    }
    
?>

    <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            
            <!--Separamos la barra superior en otro fichero y hacemos el include a ese fichero desde esta pagina principal-->
            <?php
                include("includes/top_nav.php");
            ?>

            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <!--Realizamos el mismo proceso, dividimos en varios ficheros la pagina principal-->
           <?php
                include("includes/side_nav.php");
           ?>
        </nav>

        <div id="page-wrapper">
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Users
                    </h1>

                    <div class="col-md-6 user_image_box">

                    <!--Extra features: cuando se hace click en la imagen, se muestra un dialogo
                        modal con todas las imagenes. En data-target se pone #para indicar que se trata como un id-->
                        <a href="#" data-toggle="modal" data-target="#photo-library"><img class="img-responsive" src="<?php echo $user->image_path_and_placeholder(); ?>" alt=""></a>

                    </div>

                    <!--Formulario para mostrar propiedades de una foto-->
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="file" name="user_image" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" class="form-control" value="<?php echo $user->username;?>">
                            </div>

                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" class="form-control" value="<?php echo $user->first_name;?>">
                            </div>

                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="<?php echo $user->last_name; ?>">
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" value="<?php echo $user->password; ?>">
                            </div>

                            <div class="form-group">
                                <a id="user-id" class="btn btn-danger" href="delete_user.php?id=<?php echo $user->id; ?>" type="submit" name="delete"> Delete </a>
                                <input type="submit" value="Update" name="update" class="btn btn-primary pull-right">
                            </div>
                        </div> <!--Fin div col-md-8-->
                    </form>
                </div>
            </div>
            <!-- /.row -->

            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

  <?php include("includes/footer.php"); ?>