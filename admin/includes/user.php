<?php

class User extends Db_object{

    //Variable para indicar a que tabla vamos a realizar el crud.
    protected static $db_table = 'users';

    //Crear un array para obtener el nombre de los campos que queremos
    protected static $db_table_fields = array('username','password','first_name', 'last_name','user_image');

    //Atributos de la clase user:
    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;
    public $user_image;
    public $upload_directory = "images";
    public $image_placeholder = "http://placehold.it/400x400&text=image";

    //Array para almacenar los errores que suceden al usuario cuando sube un fichero:
    public $errors = array();

    public function upload_photo(){
        //Si el array que contiene los errores que almacenamos no esta vacio, y contiene algo:
        if(!empty($this->errors)){
            return false;
        }

        //Si el nombre del fichero o la ruta temporal estan vacias:
        if(empty($this->user_image) || empty($this->tmp_path)){
            $this->errors[] = "The file was not available";
            return false;
        }

        //Indicamos el directorio al que queremos subir los ficheros usando las constantes del fichero init.php:
        $target_path = SITE_ROOT.DS.'admin'.DS.$this->upload_directory.DS.$this->user_image;

        //Comprobamos si el fichero a subir ya existe:
        if(file_exists($target_path)){
            $this->errors[] = "The file {$this->user_image} already exists";
            return false;
        }

        //Si se ha movido correctamente el fichero de una ruta a otra:
        if(move_uploaded_file($this->tmp_path, $target_path)){
            //Destruimos la ruta anterior y devolvemos true como que si se ha movido:
            unset($this->tmp_path);
            return true;
        }else{
            //Si no tenemos permisos en el directorio definitivo:
            $this->errors[] = "The file directory probably does not have permission";
            return false;
        }
    }
    
    //Si el usuario tiene imagen se muestra:
    public function image_path_and_placeholder() {
		return empty($this->user_image) ? $this->image_placeholder : $this->upload_directory.DS.$this->user_image;
	}

    public static function verify_user($username,$password){
        //Usamos la variable global para llamar a sus metodos
        global $database;

        //Quitamos los caracteres raros a las cadenas:
        $username = $database->escape_string($username);
        $password = $database->escape_string($password);

        //Sentencia para obtener solo un usuario
        $sql = "select * from ". static::$db_table. " where ";
        $sql .= "username='{$username}' ";
        $sql .= "and password='{$password}' ";
        $sql .= "LIMIT 1";

        //Llamamos al metodo de la clase, que es estatico pasandole la sentencia sql:
        $the_result_array =  static::find_by_query($sql);

        //Si $result_array no esta vacio, obtenemos el primer resultado con array_shift
        return !empty($the_result_array) ? array_shift($the_result_array) : false;
    }

    public function ajax_save_user_image($user_image, $user_id) {
        global $database;
        
        //Mejoramos la cadena por si tiene algo extraño:
		$user_image = $database->escape_string($user_image);
		$user_id = $database->escape_string($user_id);

        //Indicamos que los valores recibidos se asignen a las propiedades del objeto:
		$this->user_image = $user_image;
		$this->id         = $user_id;

        //Creamos la sql para actualizar solo estos 2 campos:
		$sql  = "UPDATE " . self::$db_table . " SET user_image = '{$this->user_image}' ";
		$sql .= " WHERE id = {$this->id} ";
		$update_image = $database->query($sql);

		echo $this->image_path_and_placeholder();
	}

    //Comprueba para borrar la imagen/el fichero de la bbdd y del propio servidor
    public function delete_photo(){

        if($this->delete()){
            //Concatenamos la ruta para junto con el metodo que nos devuelve la carpeta y el fichero a borrar.
            $target_path = SITE_ROOT.DS.'admin'.DS.$this->upload_directory.DS. $this->user_image;

            //Mediante operador ternario devolvemos true o false para saber si se borro el fichero o no
            return unlink($target_path) ? true : false;
        }else{
            return false;
        }
    }

    public function photos(){
        return Photo::find_by_query("select * from photos where user_id = " . $this->id);
    }

    public static function getUserFirstLastName(){
        $first_name = User::find_by_id($_SESSION['user_id'])->first_name;
        $last_name = User::find_by_id($_SESSION['user_id'])->last_name;
        $user = $first_name. ' '. $last_name;
        return $user;
    }
}//End of the user class.
?>