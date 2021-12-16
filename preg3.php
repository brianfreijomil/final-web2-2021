<?php


require_once "Model/ModelCliente.php";
require_once "Model/ModelActividad.php";
require_once "Helpers/AuthHelper.php";
//require_once "Helpers/CardHelper.php";


class Controller {
    private $model_cliente;
    private $model_actividad;
    private $view;
    private $CardHelper;
    private $authHelper;

    public function __construct(){
        $this->model_cliente = new ModelCliente();
        $this->model_actividad = new ModelActividad();
        $this->view = new View();
        $this->authHelper = new AuthHelper();
      //$this->CardHelper = new CardHelper();
    }
    
//asumo que la suma de kms que quiere transferir llega por post
    public function transferencia(){
        if($this->authHelper->CheckClientLogged()){
            if(isset($_POST['kms']) && isset($_POST['dni']) && !empty(isset($_POST['kms']) && !empty(isset($_POST['dni'])){
                //traigo el id mio
                $id_cliente = $this->authHelper->myIdclient();
                $miActividad = $this->model_actividad->getActividad($id_cliente);
                if ($miActividad->kms > $_POST['kms']) {
                    $cliente_2 = $this->model_cliente->getClienteByDNI($_POST['dni']);
                    if ($cliente_2) {
                        $operacion1 = "suma";
                        $operacion2 = "canje";
                        $this->model_actividad->operacionKMS($_POST['kms'], $operacion2, $id_cliente);
                        $this->model_actividad->operacionKMS($_POST['kms'], $operacion1, $cliente_2->id);
                        $this->view->mostrarMsj("transferencia con exito!");
                    }
                    else {
                        $this->view->mostrarMsj("No existe un cliente con el dni que ingresaste");
                    }
                }
                else {
                    $this->view->mostrarMsj("No tienes suficientes kms para transferir esa cantidad ingresada");
                }
            }
            else {
                $this->view->mostrarMsj("complete el formulario");
            }
        }
        else{
            $this->view->mostrarMsj("accion no permitida, usted no esta logueado!");
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

    public function operacionKMS($kms, $operacion, $id_cliente){
        $sentencia = $this->db->prepare("INSERT INTO ACTIVIDAD(Kms, tipo_operacion, id_cliente) VALUES(?,?,?)");
        $sentencia->execute(array($kms, $operacion, $id_cliente));
    }
}