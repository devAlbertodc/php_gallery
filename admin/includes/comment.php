<?php

class Comment extends Db_object{

    //Variable para indicar a que tabla vamos a realizar el crud.
    protected static $db_table = 'comments';

    //Crear un array para obtener el nombre de los campos que queremos
    protected static $db_table_fields = array('id','photo_id','author','body');

    //Atributos de la clase user:
    public $id;
    public $photo_id;
    public $author;
    public $body;
      
    //Podemos pasar a la funcion unos parametros con valores por defecto:
    public static function create_comment($photo_id,$author="John Doe",$body=""){
        
        //Si no estan vacios los siguientes campos:
        if(!empty($photo_id) && !empty($author) && !empty($body)){
            $comment = new Comment();

            $comment->photo_id = (int) $photo_id;
            $comment->author = $author;
            $comment->body = $body;

            return $comment;
        }else{
            return false;
        }
    }

    public static function find_the_comments($photo_id=0){
        global $database;
        
        //Obtenemos las fotos cuyo id sea el correcto y ordenamos por ascendente:
        $sql = "select * from ". self::$db_table;
        $sql .= " where photo_id = ". $database->escape_string($photo_id);
        $sql .= " order by photo_id asc";

        return self::find_by_query($sql);
    }

}//End of the comment class.
?>