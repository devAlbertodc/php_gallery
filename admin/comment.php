<?php 
    include("includes/header.php"); 

    //Si el usuario no ha iniciado sesion:
    if(!$session->is_signed_in()){
        //Llamamos a la funcion que hemos creado en functions.php y le decimos la ruta:
        redirect("login.php");
    }

    $comments = Comment::find_all();
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
                        All Comments
                    </h1>

                    <p class="bg-success"> <?php echo $message; ?> </p>

                    <div class="col-md-12">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Author</th>
                                    <th>Body</th>
                                </tr>
                            </thead>

                            <tbody>

                            <?php
                                foreach ($comments as $comment):
                            ?>
    
                            <tr>
                                <td><?php echo $comment->id; ?> </td>
                                
                                <td><?php echo $comment->author; ?>
                                    <div class="action_links">
                                        <a class="delete_link" href="delete_comment.php?id=<?php echo $comment->id; ?>">Delete</a>
                                    </div>
                                </td>
                                
                                <td><?php echo $comment->body ?></td>
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