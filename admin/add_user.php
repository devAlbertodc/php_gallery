<?php
    include("includes/header.php"); 

    //Si el usuario no ha iniciado sesion:
    if(!$session->is_signed_in()){
        //Llamamos a la funcion que hemos creado en functions.php y le decimos la ruta:
        redirect("login.php");
    }

    $user = new User();

    //Si se ha pulsado el boton de actualizar:
    if(isset($_POST['create'])){
      
        //Si el objeto user existe asignamos valores obtenidos por $_post
        if($user){
            $user->username = $_POST['username'];
            $user->password = $_POST['password'];
            $user->first_name = $_POST['first_name'];
            $user->last_name = $_POST['last_name'];

            $user->set_file($_FILES['user_image']);
            
            //Llamamos al metodo guardar de la clase user:
            $user->upload_photo();
            $session->message("The {$user->username} user has been added");
            $user->save();
            redirect("users.php");
        }
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

                    <!--Formulario para mostrar propiedades de una foto-->
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="col-md-6 col-md-offset-3">
                            <div class="form-group">
                                <input type="file" name="user_image" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>

                            <div class="form-group">
                                <input type="submit" value="Submit" name="create" class="btn btn-primary pull-right">
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