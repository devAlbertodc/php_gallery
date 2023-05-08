<?php 
    include("includes/header.php"); 

    //Si el usuario no ha iniciado sesion:
    if(!$session->is_signed_in()){
        //Llamamos a la funcion que hemos creado en functions.php y le decimos la ruta:
        redirect("login.php");
    }

    $photos = Photo::find_all();
    //$photos = User::find_by_id($_SESSION['user_id'])->photos();
    //var_dump("aaaaa");
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
                    </h1>

                    <p class="bg-success"> <?php echo $message; ?> </p>

                    <div class="col-md-12">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Id</th>
                                    <th>File</th>  
                                    <th>Title</th>
                                    <th>Size</th>
                                    <th>Comments</th>
                                </tr>
                            </thead>

                            <tbody>

                            <?php
                                foreach ($photos as $photo):
                            ?>
    
                                <tr>
                                    <td>
                                        <img class="admin-photo-thumbnail" src="<?php echo $photo->picture_path(); ?>" alt="">
                                        <div class="action_links">
                                           <a class="delete_link" href="delete_photo.php?id=<?php echo $photo->id?>">Delete</a>
                                           <a href="edit_photo.php?id=<?php echo $photo->id?>">Edit</a>
                                           <a href="../photo.php?id=<?php echo $photo->id;?>">View</a> 
                                        </div>
                                    </td>
                                    <td><?php echo $photo->id ?> </td>
                                    <td><?php echo $photo->filename ?></td>
                                    <td><?php echo $photo->title ?></td>
                                    <td><?php echo $photo->size ?></td>
                                    <td>
                                        <?php 
                                            //Para mostrar el numero de comentarios que tiene cada foto:
                                            $comments = Comment::find_the_comments($photo->id);
                                            //echo count($comments);
                                        ?>

                                        <a href="comment_photo.php?id=<?php echo $photo->id?>"> <?php echo count($comments);?> </a>
                                    </td>

                                </tr>

                            <?php 
                                endforeach;
                            ?>
                            </tbody>
                        </table> <!--End of table-->
                    </div>
                </div>
            </div>
            <!-- /.row -->

            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

  <?php include("includes/footer.php"); ?>