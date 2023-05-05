<?php 
    include("includes/header.php");
   
    //Si el usuario no ha iniciado sesion:
    if(!$session->is_signed_in()){
        //Llamamos a la funcion que hemos creado en functions.php y le decimos la ruta:
        redirect("login.php");
    }

    $message = "";
    //Si se ha presionado el boton de hacer submit:
    if(isset($_FILES['file'])){
        
        //Creamos un objeto de la clase Photo:
        $photo = new Photo();

        //Asignamos los valores del post a las propiedades del objeto Photo:
        $photo->user_id = $_SESSION['user_id'];
        $photo->title = $_POST['title'];
        $photo->set_file($_FILES['file']);

        //Si se ha guardado correctamente:
        if($photo->save()){
            $message = "Photo uploaded successfully";
        }else{
            $message = join("<br>", $photo->errors);
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
                        Upload
                    </h1>

                    <div class="row">
                        <!--Formulario para hacer que ocupe la mitad de la pantalla-->
                        <div class="col-md-6">

                        <?php echo $message ?>

                            <!--Formulario para poder subir imagenes-->
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <input type="text" name="title" class="form-control">
                                </div>

                                <div class="form-group">
                                    <input type="file" name="file">
                                </div>

                                <input type="submit" name="submit">
                            
                            </form>
                        </div> <!--Col-md-6-->
                    </div> <!--Row -->

                    <div class="div row">
                        <div class="col-lg-12">
                            <form action="upload.php" class="dropzone">

                            </form>
                        </div>
                    </div>


                </div>
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </div> <!-- /#page-wrapper -->

  <?php include("includes/footer.php"); ?>