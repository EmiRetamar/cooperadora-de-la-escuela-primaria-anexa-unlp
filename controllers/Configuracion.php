<?php

class Configuracion extends Controller {

    private $configuracion;

    public function __construct() {
        session_start();
        $this->configuracion = $this->model('Configuracion_model');
    }

    public function configuracionSitio($parametro = '') {
        if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            if($_SESSION['rol'] == "administrador") { // Si el usuario es administrador
                $configuracion = $this->configuracion->traerConfiguracion();
                $template = loadTwig("configuracion.twig", $parametro); // Carga el template. Por la configuracion de twigAutoloader.php
                $template->display(array('configuracion' => $configuracion));
            }
            else
                $this->controlador('Home')->vistaNoEncontrada(); // Redirigir a otro lado porque no es administrador
        }
        else
            $this->controlador('Home')->vistaNoEncontrada(); // Redirigir a otro lado porque no esta logueado
    }

    public function infoCooperadora() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
                "error" => array(),
                "post" => $_POST
            ];
            $datos["error"] = $this->configuracion->setDatos($datos["post"], $datos["error"]);
            if(empty($datos["error"])) {
                $datos["error"] = $this->configuracion->esValido($datos["error"]);
                if(empty($datos["error"])) {
                    $datos['exito']= $this->configuracion->save();
                    $this->configuracionSitio($datos);
                }
                else {
                    $this->configuracionSitio($datos);
                }
            }
            else {
                $this->configuracionSitio($datos);
            }
        }
        else {
            $this->controlador('Home')->vistaNoEncontrada();
        }
    }

}

?>