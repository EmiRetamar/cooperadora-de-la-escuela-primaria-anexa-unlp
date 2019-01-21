<?php

class Logout extends Controller {

	public function __construct() {
        session_start();
    }

	public function index() {
		$_SESSION = array(); // Deja en blanco el array eliminando las variables de sesion
		session_destroy();
		$this->controlador('Home')->index();
	}

}

?>