<?php

class Photo extends Db_object{

    //Variable para indicar a que tabla vamos a realizar el crud.
    protected static $db_table = "photos";

    //Crear un array con el nombre de los campos de esta tabla en la base de datos:
    protected static $db_table_fields = array('id', 'title', 'caption', 'description', 'filename', 'alternate_text', 'type','size'); //,'user_id'

    //Atributos de la clase photo:
    public $id;
    public $title;
    public $caption;
    public $description;
    public $filename;
    public $alternate_text;
    public $type;
    public $size;
    public $user_id;

    //Ruta temporal para usar cuando movamos una iamgen de un directorio temporal a uno permanente:
    public $tmp_path;
    public $upload_directory = "images";

    //Array para almacenar los errores que suceden al usuario cuando sube un fichero:
    public $errors = array();

    //Array que contiene posibles errores que pueden suceder al subir un fichero:
    public $upload_errors_array = array (
        UPLOAD_ERR_OK => "There is no error",
        UPLOAD_ERR_INI_SIZE => "The uploaded file exceeds the upload max filesize limit",
        UPLOAD_ERR_FORM_SIZE => "The uploaded file exceeds the max filesize limit",
        UPLOAD_ERR_PARTIAL => "PARTIAL ERROR",
        UPLOAD_ERR_PARTIAL => "The uploaded file was only partially uploaded",
        UPLOAD_ERR_NO_FILE => "No file was uploaded",
        UPLOAD_ERR_NO_TMP_DIR => "Missing Temporary folder",
        UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk",
        UPLOAD_ERR_EXTENSION => "A PHP extension stopped the file upload"
    );

    //A este metodo le estamos pasando $_FILES['uploaded_file'] como un parametro:
    public function set_file($file){

        //Si el parametro que recibe esta vacio, no es un fichero o no es un array:
        if(empty($file) || !$file || !is_array($file)){
            //Almacenamos el error en el array de errores y devolvemos false.
            $this->errors[] = "There was no file uploaded here";
            return false;
        } elseif($file['error'] != 0){
            /*Error = 0 es UPLOAD_ERR_OK
              Si hay algun otro error, entonces pasamos el error en concreto al array y devuelve false.*/
            $this->errors[] = $this->upload_errors_array[$file['error']];
            return false;
        } else {
            //Si no hay fallos, asignamos los valores del parametro a las propiedades del objeto:
            $this->filename = basename($file['name']);
            $this->tmp_path = $file['tmp_name'];
            $this->type = $file['type'];
            $this->size = $file['size'];
        }
    }

    public function picture_path(){
        return $this->upload_directory.DS.$this->filename;
    }

    public function save(){
        //Si el id ya existe, entonces actualizamos el registro:
        if($this->id){
            $this->update();
        }else{

            //Si el array que contiene los errores que almacenamos no esta vacio, y contiene algo:
            if(!empty($this->errors)){
                return false;
            }

            //Si el nombre del fichero o la ruta temporal estan vacias:
            if(empty($this->filename) || empty($this->tmp_path)){
                $this->errors[] = "The file was not available";
                return false;
            }

            //Indicamos el directorio al que queremos subir los ficheros usando las constantes del fichero init.php:
            $target_path = SITE_ROOT.DS.'admin'.DS.$this->upload_directory.DS.$this->filename;

            //Comprobamos si el fichero a subir ya existe:
            if(file_exists($target_path)){
                $this->errors[] = "The file {$this->filename} already exists";
                return false;
            }

            //Si se ha movido correctamente el fichero de una ruta a otra:
            if(move_uploaded_file($this->tmp_path, $target_path)){
                //Si se ha creado correctamente:
                if($this->create()){
                    //Destruimos la ruta anterior y devolvemos true como que si se ha movido:
                    unset($this->tmp_path);
                    return true;
                }
            }else{
                //Si no tenemos permisos en el directorio definitivo:
                $this->errors[] = "The file directory probably does not have permission";
                return false;
            }
        }
    }

    //Comprueba para borrar la imagen/el fichero de la bbdd y del propio servidor
    public function delete_photo(){

        if($this->delete()){
            //Concatenamos la ruta para junto con el metodo que nos devuelve la carpeta y el fichero a borrar.
            $target_path = SITE_ROOT.DS.'admin'.DS.$this->picture_path();

            //Mediante operador ternario devolvemos true o false para saber si se borro el fichero o no
            return unlink($target_path) ? true : false;
        }else{
            return false;
        }
    }

    public static function display_sidebar_data($photo_id){
        
        //Obtenemos la foto con el id que recibimos por parametro:
        $photo = Photo::find_by_id($photo_id);

        $output = "<a class='thumbnail' href='#'> <img width='100' src='{$photo->picture_path()}'></a>";
        $output .= "<p>{$photo->filename}</p>";
        $output .= "<p>{$photo->type}</p>";
        $output .= "<p>{$photo->size}</p>";

        echo $output;
    }
}
?>