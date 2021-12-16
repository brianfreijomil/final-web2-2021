<?php

require_once "Model/ModelCliente.php";
require_once "Model/ModelTarjeta.php";
require_once "Helpers/AuthHelper.php";
require_once "Helpers/CardHelper.php";




class controller {
    private $model_cliente;
    private $model_tarjeta;
    private $view;
    private $CardHelper;
    private $authHelper;

    public function __construct(){
        $this->model_cliente = new ModelCliente();
        $this->model_tarjeta = new TarjetaModel();
        $this->view = new View();
        $this->authHelper = new AuthHelper();
        $this->CardHelper = new CardHelper();
    }

    public function addCliente(){
        //chequeo logueo de admin
        if($this->authHelper->checkAdmminLoggedIn()){
            //chequeo errores en carga de datos
            if(isset($_POST['nombre']) && isset($_POST['dni']) && isset($_POST['telefono']) && isset($_POST['direccion']) && isset($_POST['ejecutivo']) && 
            !empty($_POST['nombre']) && !empty($_POST['dni']) && !empty($_POST['telefono']) && !empty($_POST['direccion']) && !empty($_POST['ejecutivo'])){
                //chequeo que no exista otro cliente con mismo dni
                $cliente = $this->model_cliente->getClienteByDNI($_POST['dni']);
                if ($cliente) {
                    $this->view->mostrarMsj("Ya existe un cliente con ese dni!");
                }
                else {
                    $newCliente = $this->model_cliente->addCliente($_POST['nombre'], $_POST['dni'], $_POST['telefono'], $_POST['direccion'], $_POST['ejecutivo']);
                    if(!empty($newCliente)){
                        $cliente_id = $this->model_cliente->getClienteByDNI($_POST['dni']);
                        //deposito 200 kms por ser nuevo cliente
                        $this->model_cliente->depositarKMs($cliente_id->id);
                        $this->view->mostrarMsj("Se ha añadido el cliente nuevo, y se le han sumado 200 kms automaticamyente");
                        //verifico si el nuevo cliente es ejecutivo
                        if($_POST['ejecutivo'] == true){
                            $bussinesCargd = $this->CardHelper->getBussinesCard();
                            //doy de alta la tarjeta de empresarial
                            $newCardBussines = $this->model_tarjeta->addbussinesCargd($bussinesCargd->fecha_alta, $bussinesCargd->nro_tarjeta, 
                            $bussinesCargd->fecha_vencimiento, $bussinesCargd->tipo_tarjeta, $cliente_id->id );
                            if(!empty($newCardBussines)){
                                $this->view->mostrarMsj("Se le asocio una tarjeta de tipo empresarial!");
                            }
                            else {
                                $this->view->mostrarMsj("ocurrio un error , no pudo asociarse su tarjeta empresarial!");
                            }
                        }

                    }
                    else{
                        $this->view->mostrarMsj("No se pudo agregar el cliente");
                    }
                }
            }
            else {
                $this->view->mostrarMsj("Debes completar todos los campos!!!!");
            }
        }
        else {
            $this->view->mostrarMsj("Usted No esta logueado!");
        }
    }
}

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

    public function addCliente($nombre,$dni,$telefono,$direccion,$ejecutivo){
        $sentencia = $this->db->prepare("INSERT INTO CLIENTE(nombre,dni,telefono,direccion,ejecutivo) VALUES(?,?,?,?,?)");
        $sentencia->execute(array($nombre,$dni,$telefono,$direccion,$ejecutivo));
        return $this->db->lastInsertId();
    }

    public function depositarKMs($id_cliente){
        $sentencia = $this->db->prepare("INSERT INTO ACTIVIDAD(Kms, tipo_operacion, id_cliente) VALUES(?,?,?)");
        $sentencia->execute(array(200,'suma',$id_cliente));
    }
}

class TarjetaModel {
    private $db;

    public function __construct(){
        $this->db = new PDO('mysql:host=localhost;'.'dbname=PFY;charset=utf8', 'root', ''); 
    }

    public function addbussinesCargd($fecha_alta,$nro_tarjeta,$fecha_vencimiento,$tipo_tarjeta,$id_cliente){
        $sentencia = $this->db->prepare("INSERT INTO TARJETA(fecha_alta,nro_tarjeta,fecha_vencimiento,tipo_tarjeta,id_cliente) VALUES(?,?,?,?,?)");
        $sentencia->execute(array($fecha_alta,$nro_tarjeta,$fecha_vencimiento,$tipo_tarjeta,$id_cliente));
        return $this->db->lastInsertId();
    }
}