<?php

require_once 'twigAutoloader.php';

class Home extends Controller {

	public function __construct() {
        if(session_status() == PHP_SESSION_NONE)
        	session_start();
    }

	public function index() {
		$this->vistaFrontend();
	}

	public function vistaFrontend($checkFound = '') {
		if(empty($checkFound))
			$checkFound = "found";
		$ConfiguracionModel = $this->model('Configuracion_model');
		$configuracion = $ConfiguracionModel->traerConfiguracion();
		$template = loadTwig("frontend.twig", $configuracion); // Carga el template. Por la configuracion de twigAutoloader.php
		$template->display(array('isFound' => $checkFound));
	}

	public function vistaBackend($parametro = '') {
		if(isset($_SESSION['id'])) {
			$ConfiguracionModel = $this->model('Configuracion_model');
			$configuracion = $ConfiguracionModel->traerConfiguracion();
			$_SESSION['elementos'] = $configuracion['elementos'];
	        $template = loadTwig("backend.twig", $parametro); // Carga el template. Por la configuracion de twigAutoloader.php
	        $template->display(array());
		}
		else
			$this->vistaNoEncontrada();
	}

	public function vistaNoEncontrada() {
		$notfound = "notfound";
		$this->vistaFrontend($notfound); // Envio true para que en twig cargue la imagen de error 404
	}

}

?>