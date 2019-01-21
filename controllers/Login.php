<?php

class Login extends Controller {

    public function __construct() {
        if(session_status() == PHP_SESSION_NONE)
            session_start();
    }

    public function index($parametro = '') {
        if(!isset($_SESSION['id'])) { // Si el usuario no esta logueado
            $template = loadTwig("login.twig", $parametro); // Carga el template. Por la configuracion de twigAutoloader.php
            $template->display(array());
        }
        else {
            $this->controlador('Home')->vistaBackend();
        }
    }

    public function login() {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $validar = $this->model('Validar');
            $gateKeeper = $validar->login($_POST);
            if(!isset($gateKeeper['userError']) and !isset($gateKeeper['passError'])) {
                $username = $_POST["username"];
                $password = $_POST["password"];
                $user = $this->model('Usuario_model');
                if($user->login($username, $password)) { // Retorna true o false
                    if($user->estaHabilitado()) {
                        session_start(); // Crea la sesion
                        $_SESSION['id'] = $user->getId();
                        $_SESSION['username'] = $user->getUsername();
                        $_SESSION['rol'] = $user->getRol();
                        $_SESSION['mail'] = $user->getMail();
                        header('Location: ../Home/vistaBackend');
                    }
                    else {
                        $mensaje = "Usted tiene su cuenta deshabilitada";
                        $this->index($mensaje);
                    }
                }
                else {
                    $mensaje = "Usuario o contraseña incorrecta";
                    $this->index($mensaje);
                }
            }
            else {
                $mensaje = "Nombre de usuario o contraseña incorrecta";
                $this->index($mensaje);
            }
        }
        else {
            $this->controlador('Login')->index();
        }
    }

}

?>