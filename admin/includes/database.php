<?php

require_once("new_config.php");

class Database{

    public $connection;
    public $db;

    //Constructor de la clase que llama al metodo que usa los datos para realizar la conexion.
    public function __construct(){
        $this->db = $this->open_db_connection();
    }

    public function open_db_connection(){
        //Devuelve true o false. Comentado por si acaso se usa en el futuro:
        //$this->connection = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);

        //Ahora podemos usar la conexion como un objeto:
        $this->connection = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

        //Si hay un error de conexion:
        if($this->connection->connect_errno){
            die("Database connection failed badly". $this->connecion->connect_error);
        }

        return $this->connection;
    } 

    //Metodo para realizar sentencias sql para devolver registros:
    public function query($sql){
        //A la funcion le pasamos la conexion ,y la sentencia:
        $result = $this->db->query($sql);

        $this->confirm_query($sql);
        return $result;
    }

    //Creamos otro metodo para simplicar el metodo query:
    private function confirm_query($result){
        if(!$result){
            die("Query failed ".$this->db->error);
        }
    }

    //Evitamos caracteres extraños con el metodo que recibe una conexion y la cadena a tratar:
    public function escape_string($string){
        return $this->db->real_escape_string($string);
    }

    public function the_insert_id(){
        return $this->db->insert_id;
        //return mysqli_insert_id($this->connection);
    }
}

$database = new Database();
?>