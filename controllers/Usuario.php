<?php

class Usuario extends Controller {

    private $usuario;
    
    public function __construct() {
        session_start();
        $this->usuario = $this->model('Usuario_model');
    }

    // Carga bloque de twig en el backend

    public function controlUsuario($parametro = '') {
        if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            if($_SESSION['rol'] == "administrador") { // Si el usuario es administrador
                $listado = $this->model('Listado_model');
                $usuarios = $listado->listarUsuarios();
                $template = loadTwig("ABMUsuario.twig", $parametro);
                $template->display(array('usuarios' => $usuarios));
            }
            else
                $this->controlador('Home')->vistaNoEncontrada(); // Redirigir a otro lado porque no es administrador
        }
        else
            $this->controlador('Home')->vistaNoEncontrada(); // Redirigir a otro lado porque no esta logueado
    }

    // Se cargan las vistas del backend

    public function vistaAltaUsuario($parametro = '') {
        if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            if($_SESSION['rol'] == "administrador") { // Si el usuario es administrador
                $responsableModel = $this->model('Responsable_model');
                $responsables = $responsableModel->obtenerResponsables();
                $template = loadTwig("altaUsuario.twig", $parametro);
                $template->display(array('responsables' => $responsables));
            }
            else {
                $this->controlador('Home')->vistaNoEncontrada();
            }
        }
        else {
            $this->controlador('Home')->vistaNoEncontrada();
        }
    }

    public function vistaModificarUsuario($parametro = '') {
        if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            if($_SESSION['rol'] == "administrador") { // Si el usuario es administrador
                if($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $idUsuario = $_POST['idUsuario'];
                    if($this->usuario->existeUsuario($idUsuario)) {
                        $usuario = $this->usuario->obtenerUsuario($idUsuario);
                        $responsableModel = $this->model('Responsable_model');
                        $responsables = $responsableModel->obtenerResponsables();
                        $template = loadTwig("modificarUsuario.twig", $parametro);
                        $template->display(array('usuario' => $usuario, 'responsables' => $responsables));
                    }
                    else {
                        $datos["error"] = "El usuario a modificar no existe";
                        $this->controlUsuario($datos);
                    }
                }
                else {
                    // Caso en el que se quiere acceder a traves de la url
                    header('Location: controlUsuario');
                }
            }
            else {
                // Caso en el que el usuario no sea administrador
                $this->controlador('Home')->vistaNoEncontrada();
            }
        }
        else {
            // Caso en el que el usuario no este logueado
            $this->controlador('Home')->vistaNoEncontrada();
        }
    }

    public function vistaEliminarUsuario($parametro = '') {
        if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            if($_SESSION['rol'] == "administrador") { // Si el usuario es administrador
                if($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $idUsuario = $_POST["idUsuario"];
                    if($this->usuario->existeUsuario($idUsuario)) {
                        $usuario = $this->usuario->obtenerUsuario($idUsuario);
                        $template = loadTwig("eliminarUsuario.twig", $parametro);
                        $template->display(array('usuario' => $usuario));
                    }
                    else {
                        $datos["error"] = "El usuario a modificar no existe";
                        $this->controlUsuario($datos);
                    }
                }
                else {
                    // Caso en el que se quiere acceder a traves de la url
                    header('Location: controlUsuario');
                }
            }
            else {
                // Caso en el que el usuario no sea administrador
                $this->controlador('Home')->vistaNoEncontrada();
            }
        }
        else {
            // Caso en el que el usuario no este logueado
            $this->controlador('Home')->vistaNoEncontrada();
        }
    }

    // Funcionalidades llamadas desde las vistas

    public function agregarUsuario() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
            "post" => $_POST,
            "error" => array()
            ];
            $datos["error"] = $this->usuario->setDatos($datos["post"], $datos["error"]);
            if(empty($datos["error"])) {
                $datos["error"] = $this->usuario->esValido($datos["error"]);
                if(empty($datos["error"])) {
                    $datos["error"] = $this->usuario->save($datos["error"]);
                    if(empty($datos["error"])) {
                        $datos["exito"] = "Usuario agregado con exito";
                        $this->controlUsuario($datos);
                    }
                    else {
                        $this->vistaAltaUsuario($datos);
                    }
                }
                else {
                    $this->vistaAltaUsuario($datos);
                }
            }
            else {
                $this->vistaAltaUsuario($datos);
            }
        }
        else {
            header('Location: controlAlumno');
        }
    }

    public function modificarUsuario() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos= [
            "post" => $_POST,
            "error" => array()
            ];
            $datos["error"] = $this->usuario->setDatos($datos["post"], $datos["error"]);
            if(empty($datos["error"])) {
                $datos["error"] = $this->usuario->esValido($datos["error"]);
                if(empty($datos["error"])) {
                    $datos["error"] = $this->usuario->edit($datos["error"]);
                    if(empty($datos["error"])) {
                        $datos['exito'] = "Usuario modificado con exito";
                        $this->controlUsuario($datos);
                    }
                    else {
                        $this->vistaModificarUsuario($datos);
                    }
                }
                else {
                    $this->vistaModificarUsuario($datos);
                }
            }
            else {
                $this->vistaModificarUsuario($datos);
            }
        }
        else {
            header('Location: controlAlumno');
        }
    }

    public function eliminarUsuario() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos["error"] = '';
            $datos["error"] = $this->usuario->delete($datos["error"]);
            if(empty($datos["error"])) {
                $datos["exito"] = "Usuario eliminado con exito";
                $this->controlUsuario($datos);
            }
            else {
                $this->vistaEliminarUsuario($datos);
            }
        }
        else {
            header('Location: controlUsuario');
        }
    }

}

?>