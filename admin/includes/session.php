<?php

//Clase que va a manejar las sesiones de los usuarios:
class Session{

    /*Cada vez que un usuario acceda a la aplicacion, va a usar una sesion, y se necesita saber si ha entrado antes o es la primera vez que entra:
    Si ha iniciado sesion mostramos la pagina principal, y si no ha entrado lo mandamos a la pagina de login*/

    //Variable privada que servira para saber si el usuario ha iniciado sesion
    private $signed_in = false;
    public  $user_id;
    public $message;
    public $count;

    function __construct(){
        //Me interesa iniciar una sesion cuando se cree un objeto de esta clase.
        //Tambien me interesa saber si el usuario ha iniciado sesion o no.
        session_start();
        $this->visitor_count();
        $this->check_the_login();
        $this->check_message();
    }

    //Contador para el numero de visitas:
    public function visitor_count(){
      // unset($_SESSION['count']);
        //Si la variable de count tiene valor incrementamos su valor:
        if(isset($_SESSION['count'])){
            return $this->count = $_SESSION['count']++;
        }else{
            //Si no tiene valor pues ponemos 1 para empezar:
            return $_SESSION['count'] = 1;
        }
    }

    //Devolvemos el valor de la variable privada mediante un metodo get.
    public function is_signed_in(){
        return $this->signed_in;
    }

    //El metodo recibe un boolean.
    //Si el true asignamos el valor de la sesion, y al id del objeto user:
    public function login($user){
        if($user){
            $this->user_id = $_SESSION['user_id'] = $user->id;
            $this->signed_in = true;
        }
    }

    //Metodo para cerrar la sesion del usuario, las variables se destruyen y
    //la variable de sesion la dejaremos a false.
    public function logout(){
        unset($_SESSION['user_id']);
        unset($this->user_id);
        $this->signed_in = false;
    }

    //Comprobar si la session del usuario se ha iniciado:
    private function check_the_login(){
        //Si la session esta iniciada asignamos el valor a la variable de esta clase:
        if(isset($_SESSION['user_id'])){
            $this->user_id = $_SESSION['user_id'];
            $this->signed_in = true;
        }else{
            //Destruimos la variable e indicamos que no ha iniciado sesion:
            unset($this->user_id);
            $this->signed_in = false;
        }
    }

    public function message($msg=""){
        if(!empty($msg)){
            $_SESSION['message'] = $msg;
        }else{
            return $this->message;
        }
    }

    public function check_message(){
        //Comprueba si la sesion se ha iniciado:
        if(isset($_SESSION['message'])){
            //Asignamos el valor del mensaje 
            //de la superglobal session a la propiedad de la clase
           $this->message = $_SESSION['message'];
           unset($_SESSION['message']);
        }else{
            $this->message = "";
        }
    }
}

//Creamos el objeto que automaticamente llama al constructor:
$session = new Session();
$message = $session->message();
?>