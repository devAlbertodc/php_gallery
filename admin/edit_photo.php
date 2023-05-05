<?php
    include("includes/header.php"); 

    //Si el usuario no ha iniciado sesion:
    if(!$session->is_signed_in()){
        //Llamamos a la funcion que hemos creado en functions.php y le decimos la ruta:
        redirect("login.php");
    }

    //Si no obtenemos el id de una foto se manda a la pagina con el listado de las fotos:
    if(empty($_GET['id'])){
        redirect("photos.php");
    }else{
        $photo = Photo::find_by_id($_GET['id']);

        //Si se ha pulsado el boton de actualizar:
        if(isset($_POST['update'])){
        
            //Si el objeto photo existe asignamos valores obtenidos por $_post
            if($photo){
                $photo->title = $_POST['title'];
                $photo->caption = $_POST['caption'];
                $photo->alternate_text = $_POST['alternate_text'];
                $photo->description = $_POST['description'];

                //Llamamos al metodo guardar de la clase photo:
                $photo->save();
            }
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
                        Photos
                        <small>Subheading</small>
                    </h1>

                    <!--Formulario para mostrar propiedades de una foto-->
                    <form action="" method="POST">
                        <div class="col-md-8">
                            <div class="form-group">
                                    <input type="text" name="title" class="form-control" value="<?php echo $photo->title;?>">
                            </div>

                            <div class="form-group">
                                <a class="thumbnail" href="#"><img src="<?php echo $photo->picture_path(); ?>" alt=""></a>
                            </div>

                            <div class="form-group">
                                    <label for="caption">Caption</label>
                                    <input type="text" name="caption" class="form-control" value="<?php echo $photo->caption;?>">
                            </div>

                            <div class="form-group">
                                    <label for="caption">Alternate Text</label>
                                    <input type="text" name="alternate_text" class="form-control" value="<?php echo $photo->alternate_text;?>">
                            </div>

                            <div class="form-group">
                                    <label for="caption">Description</label>
                                    <textarea class="form-control" name="description" id="" cols="30" rows="10" value="<?php echo $photo->description;?>">
<?php echo $photo->description;?>
                                    </textarea>
                            </div>
                        </div> <!--Fin div col-md-8-->



                        <!--Espacio que se muestra a la derecha para eliminar o actualizar la imagen-->
                    <div class="col-md-4" >
                            <div  class="photo-info-box">
                                <div class="info-box-header">
                                   <h4>Save <span id="toggle" class="glyphicon glyphicon-menu-up pull-right"></span></h4>
                                </div>
                            <div class="inside">
                              <div class="box-inner">
                                 <p class="text">
                                   <span class="glyphicon glyphicon-calendar"></span> Uploaded on: April 22, 2030 @ 5:26
                                  </p>
                                  <p class="text ">
                                    Photo Id: <span class="data photo_id_box">34</span>
                                  </p>
                                  <p class="text">
                                    Filename: <span class="data">image.jpg</span>
                                  </p>
                                 <p class="text">
                                  File Type: <span class="data">JPG</span>
                                 </p>
                                 <p class="text">
                                   File Size: <span class="data">3245345</span>
                                 </p>
                              </div>
                              <div class="info-box-footer clearfix">
                                <div class="info-box-delete pull-left">
                                    <a  href="delete_photo.php?id=<?php echo $photo->id; ?>" class="btn btn-danger btn-lg ">Delete</a>   
                                </div>
                                <div class="info-box-update pull-right ">
                                    <input type="submit" name="update" value="Update" class="btn btn-primary btn-lg ">
                                </div>   
                              </div>
                            </div>          
                        </div>
                    </div>
                    </form>

                    




                </div>
            </div>
            <!-- /.row -->

            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

  <?php include("includes/footer.php"); ?>