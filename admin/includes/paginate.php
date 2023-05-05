<?php

class Paginate{

    public $current_page;
    public $items_per_page;
    public $items_total_count;

    //Constructor definiendo valores por defecto:
    public function __construct($page=1, $items_per_page=10,$items_total_count=0){
        $this->current_page = (int) $page;
        $this->items_per_page = (int) $items_per_page;
        $this->items_total_count = (int) $items_total_count;
    }

    //Devolvemos la siguiente pagina incrementando la pagina actual:
    public function next(){
        return $this->current_page+1;
    }

    //Devolvemos la anterior pagina decrementando la pagina actual:
    public function previous(){
        return $this->current_page-1;
    }

    //Funcion para obtener el numero total de paginas:
    public function page_total(){
        //Redondea hacia arriba, si redondea hacia abajo hay problemas:
        return ceil( $this->items_total_count / $this->items_per_page);
    }

    public function has_previous(){
        //Si la pagina previa es mayor que 1 es que hay una pagina anterior.
        //Devolvera false si estamos en la primera
        return $this->previous() >= 1 ? true : false;
    }

    public function has_next(){
        //Si la pagina siguiente es menor que el total de paginas devolvemos true.
        //Devolvera false si estamos en la primera
        return $this->next() <= $this->page_total() ? true : false;
    }

    public function offset(){
        return ($this->current_page -1) * $this->items_per_page;
    }

} //Fin de la clase
?>