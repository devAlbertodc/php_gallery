<?php include("includes/header.php"); ?>

<?php 

//Si no esta vacia asignamos el valor, si esta vacia asignamos 1:
$page = !empty($_GET['page']) ? (int) $_GET['page'] : 1 ;

//Numero manual:
$items_per_page = 3;

//Obtenemos el total de fotos en la bbdd:
$items_total_count = Photo::count_all();

//Inicializar objeto pasando valores:
$paginate = new Paginate($page, $items_per_page, $items_total_count);

$sql = "select * from photos ";
$sql .= " limit {$items_per_page} ";
$sql .= " offset {$paginate->offset()}";

$photos = Photo::find_by_query($sql);
?>

<div class="row">

    <!-- Blog Entries Column -->
    <div class="col-md-12">
    
        <div class="thumbnails row">
            <!--Bucle para mostrar las fotos:-->
            <?php foreach ($photos as $photo):?>
                <div class="col-xs-6 col-md-3">
                    <a class="thumbnail" href="photo.php?id=<?php echo $photo->id?>">
                        <img class="img-responsive home_page_photo" src="admin/<?php echo $photo->picture_path();?>" alt="">
                    </a>
                </div>
            <?php endforeach;?>           
    </div>

    <div class="row">
        <ul class="pager"> <!--Pager o pagination como class sirven para lo mismo-->

            <?php
                //Si tenemos algo:
                if($paginate->page_total() > 1){
                    if($paginate->has_next()){
                        echo "<li class='next'><a href='index.php?page={$paginate->next()}'> Next</a></li>";
                    }

                    //Empezamos por 1 porque las paginas empiean por ese valor, y mientras sea menor 
                    //que el total de paginas iteramos el bucle:
                    for ($i=1; $i <= $paginate->page_total(); $i++) { 
                        
                        //Si el item que se itera tiene el mismo valor que la pagina actual:
                        if($i == $paginate->current_page){
                            echo "<li class='active'> <a href='index.php?page={$i}'> $i </a> </li>";
                        }else{
                            echo "<li> <a href='index.php?page={$i}'> $i </a> </li>";
                        }
                    }
                   
                    if($paginate->has_previous()){
                        echo "<li class='previous'><a href='index.php?page={$paginate->previous()}'> Previous</a></li>";
                    }   
                }
            ?>

        </ul>
    </div>
</div>

<!-- Blog Sidebar Widgets Column -->
<!-- /.row -->
<?php include("includes/footer.php"); ?>