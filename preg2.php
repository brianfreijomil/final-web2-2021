<?php

require_once "Model/ModelCliente.php";
require_once "Model/ModelTarjeta.php";
require_once "Model/ModelActividad.php";

class Controller {
    private $model_cliente;
    private $model_tarjeta;
    private $model_actividad;
    private $view;
    //private $CardHelper;
    //private $authHelper;

    public function __construct(){
        $this->model_cliente = new ModelCliente();
        $this->model_tarjeta = new ModelTarjeta();
        $this->model_actividad = new ModelActividad();
        $this->view = new View();
        //$this->authHelper = new AuthHelper();
        //$this->CardHelper = new CardHelper();
    }
    
    public function resumenCuenta(){
        //asumo que para ver sus datos el cliente debe tener una manera de acceder 
        //y que no sea tan facil entrar a los mismos
        //por eso planteo que accede por dni
        if(isset($_POST['dni']) && !empty($_POST['dni'])){
            $dni = $_POST['dni'];
            //verifico existencia del cliente
            $cliente = $this->model_cliente->getClienteByDNI($dni);
            if ($cliente) {
                $actividad = $this->model_actividad->getActividad($cliente->id);
                $tarjetas = $this->model_tarjeta->getAllcardsById($cliente->id);
                //verifico si tiene actividad
                if ($actividad) {
                    //verifico existencia de tarjetas
                    if ($tarjetas) {
                        $this->view->mostrar("RESUMEN DE CUENTA",$cliente, $actividad, $tarjetas);
                    }
                    else {
                        $this->view->mostrarMsj("Ocurrio un error, usted no tiene tarjeta/s asociadas!");
                    }
                }
                //verifico si hay tarjetas aunque no tenga act
                elseif ($tarjetas) {
                    $this->view->mostrar($cliente, $tarjetas);
                    $this->view->mostrarMsj("Usted no tiene actividad registrada!");
                }
                else {
                    $this->view->mostrarMsj("Usted no tiene ni actividad registrada, ni tarjetas asociadas!");
                }
            
            }
            else {
                $this->view->mostrarMsj("No existe un cliente con ese dni! ");
            }
        }
    }
}


//CLIENTE(id: int; nombre: string, dni: string, telefono: string, direccion: string,ejecutivo: boolean)
//TARJETA(id: int; fecha_alta: datetime; nro_tarjeta: string, fecha_vencimiento:
//int, tipo_tarjeta: string, id_cliente: int)
//ACTIVIDAD(id: int; kms: int, fecha: datetime, tipo_operación: int, id_cliente:int)

class ModelCliente {
    private $db;

    public function __construct(){
        $this->db = new PDO('mysql:host=localhost;'.'dbname=PFY;charset=utf8', 'root', ''); 
    }

    public function getClienteByDNI($dni){
        $sentencia = $this->db->prepare("SELECT FROM CLIENTE WHERE dni=?");
        $sentencia->execute(array($dni));
        $cliente = $sentencia->fetch(PDO::FETCH_OBJ);
        return $cliente;
    }
}

class ModelActividad {
    private $db;

    public function __construct(){
        $this->db = new PDO('mysql:host=localhost;'.'dbname=PFY;charset=utf8', 'root', ''); 
    }

    public function getActividad($id_cliente){
        $sentencia = $this->db->prepare("SELECT FROM ACTIVIDAD WHERE id_cliente=?");
        $sentencia->execute(array($id_cliente));
        $actividad = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $actividad;
    }
}

class ModelTarjeta {
    private $db;

    public function __construct(){
        $this->db = new PDO('mysql:host=localhost;'.'dbname=PFY;charset=utf8', 'root', ''); 
    }

    public function getAllcardsById($id_cliente){
        $sentencia = $this->db->prepare("SELECT FROM TARJETA WHERE id_cliente=?");
        $sentencia->execute(array($id_cliente));
        $tarjetas = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $tarjetas;
    }
}