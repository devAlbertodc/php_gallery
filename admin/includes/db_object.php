<?php

//Clase generica que tiene los metodos para trabajar con cualquier tabla de la base de datos:
class Db_object {
    
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
            $this->user_image = basename($file['name']);
            $this->tmp_path = $file['tmp_name'];
            $this->type = $file['type'];
            $this->size = $file['size'];
        }
    }


    //Metodo estatico que devuelve todos los registros de una tabla:
    public static function find_all(){
        //Nos apoyamos en el metodo estatico para simplificar el codigo y no duplicarlo:
        //Para llamar a metodos estaticos tambien podemos poner User::*/
        return static::find_by_query("select * from ". static::$db_table. " ");
    }

    //Metodo que devuelve el registro de una tabla que tenga el mismo id que le pasamos por parametro.
    public static function find_by_id($id){
        //Llamamos a la variable superglobal database que esta en otra clase:
        global $database;

        //Como el id es unico, para que no hayan confusiones, limito el resultado en 1 para asegurarme que no hayan problemas.
        $the_result_array =  static::find_by_query("select * from ". static::$db_table. " where id=$id limit 1");

        /*Si contiene algo el resultado de la consulta:
            -Obtenemos el primer registro con ese id y lo pasamos a first_item y lo devolvemos.
        -Si la consulta no devuelve nada, pues devolvemos un falso*/
        
        /*if(!empty($the_result_array)){
            $first_item = array_shift($the_result_array);
            return $first_item;
        }else{
            return false;
        }*/

        /*Otra forma de realizarlo: con operadores ternarios:
        Si el array no esta vacio con ? le decimos que hacer.
        Si no se cumple la condicion con : hacemos el else*/
        return !empty($the_result_array) ? array_shift($the_result_array) : false ;
    }

    //Metodo que recibe la sentencia sql que se va a ejecutar:
    public static function find_by_query($sql){
        //Llamamos a la variable superglobal database que esta en otra clase:
        global $database;

        //Guarda el resultado de la sentencia en un resultset:
        $result_set = $database->query($sql);

        //Creamos un array vacio donde meteremos objeto:
        $the_object_array = array();

        /*Mientras hayan items que recorrer del resultset llamamos al metodo de instanciar y le pasamos el dato.
        Al mismo tiempo asignamos el valor obtenido al array que devuelve este metodo.
        mysqi_fetch_array es una funcion que permite acceder a informacion almacenada en el resultset de una consulta.*/
        while ($row = mysqli_fetch_array($result_set)) {
            $the_object_array[] = static::instanciation($row);
        }

        return $the_object_array;
    }
    
    //Metodo que puede ser publico/privado que recibe un array con el registro de una tabla:
    public static function instanciation($the_record){

        //Obtenemos la clase desde la que se llama a este metodo:
        $calling_class = get_called_class();

        /*Creamos un objeto segun la clase en la que se llama a este metodo.
          Poniendo new Self creaba un objeto de esta clase, y queremos hacer este metodo generico para llamarlo desde cualquier clase.*/
        $the_object = new $calling_class;

        foreach ($the_record as $the_attribute => $value) {
            
            //Comprobamos si el objeto tiene un atributo, si lo tiene le asignamos el valor
            if($the_object->has_the_attribute($the_attribute)){
                $the_object->$the_attribute = $value;
            }
        }
        return $the_object;
    }

    private function has_the_attribute($the_attribute){
    
        /*Funcion predefinida de php:
        Al metodo get_object_vars le pasamos la clase y nos devolvera todos los atributos.*/
        $object_properties = get_object_vars($this);

        /*Ahora comprueba si la clave existe, el nombre del campo, no el valor.
        Si el atributo existe en el array devuelve true o false*/
        return array_key_exists($the_attribute, $object_properties);
    }

    //Obtenemos las propiedades de un objeto:
    protected function properties(){

        $properties = array();
    
        /*Hacemos un bucle para recorrer los elementos del array y comprobamos si
        existe un valor para esa propiedad*/
        foreach (static::$db_table_fields as $db_field){
            if(property_exists($this, $db_field)){
                $properties[$db_field] = $this->$db_field;
            }
        }

        return $properties;
    }

    //Metodo que va a escapar caracteres extraños para poder usarse en create() y update()
    protected function clean_properties(){
        global $database;

        //Creo un array vacio que devolvera este metodo:
        $clean_properties = array();

        /*Iteramos a traves de los elementos que devuelve el metodo properties
        Usamos el metodo de la clase database para escapar el valor que estamos iterando*/
        foreach ($this->properties() as $key => $value) {
            $clean_properties[$key] = $database->escape_string($value);
        }

        return $clean_properties;
    }

    //Si el registro existe se actualiza, en caso contrario se inserta:
    public function save(){
        return 
            isset($this->id) ? 
                $this->update() 
            : 
                $this->create();
    }

    //Metodo para crear un registro en cualquier tabla:
    public function create(){
        //Requerimos usar la variable global para operar con metodos de esa clase.
        global $database;

		  //Las propiedades del objeto las pasamos por el metodo clean_properties:
        $properties = $this->clean_properties();

        /*Implode separa los elementos del array por , y el segundo parametro indica el array con las claves
        que queremos obtener. Las claves son los atributos*/
        $sql = "insert into " .static::$db_table." (".implode(",",array_keys($properties)).")";
        $sql.= " values ('".implode("','",array_values($properties))."')";

        //Comprobamos si la operacion ha sido realizada correctamente
        if($database->query($sql)){
            //Llamamos al metodo para obtener el ultimo id de la tabla
            $this->id = $database->the_insert_id();
            return true;
        }else{
            return false;
        }
    }

    public function update(){
        //Usamos la variable global de database para usar sus metodos:
        global $database;

			//Las propiedades del objeto las pasamos por el metodo clean_properties:
        $properties = $this->clean_properties();

        //Creamos un array que contendrá una clave y su valor:
        $properties_pairs = array();

        //Recorremos el bucle:
        foreach ($properties as $key => $value) {
            $properties_pairs[] = "{$key}='{$value}'";
        }

        //Construimos la sentencia de actualizar un usuario:
        $sql  = "update ".static::$db_table." set ";
        
        //Dividimos el array en partes, delimitando por las comas:
        $sql .= implode(", ",$properties_pairs);

        //Agregamos la condicion para que solo encuentre un registro:
        $sql .= " where id= " . $database->escape_string($this->id);

        $database->query($sql);
        //mysqli_affected_rows devuelve el numero de filas afectadas en la ultima sentencia
        return (mysqli_affected_rows($database->connection) == 1) ? true : false;
    }

    public function delete(){
        global $database;

        //El proceso de borrar se debe hacer con un where. 
        //Limit sirve para asegurarse mas de que solo vamos a borrar un registro.
        $sql = "delete from " .static::$db_table." where id = " .$database->escape_string($this->id);
        $sql .= " LIMIT 1";

        $database->query($sql);

        return (mysqli_affected_rows($database->connection) == 1) ? true : false;

    }

    public static function count_all(){
        global $database;

        $sql = "select count(*) from ". static::$db_table;
        $result_set = $database->query($sql);

        //Obtenemos un registro de la base de datos:
        $row = mysqli_fetch_array($result_set);

        //Devolvemos el primer registro, porque count solo va a devolver un registro:
        return array_shift($row);
    }
}

?>