<?php 
require_once('lib/slim/Slim/Slim.php');

class Api extends Controller {
	public function alumno($value='')
	{
		\Slim\Slim::registerAutoloader();
	    $slim = new \Slim\Slim();
		$slim->response->headers->set('Content-Type', 'application/json');
		$slim->response->headers->set('Access-Control-Allow-Origin', '*');
	    $slim->get('/Api/alumno/:dni', function ($dni) {
			/** Peticion a la DB con DNI y las cuotas **/

			echo json_encode( array('dni' => $dni ) );
		});
		$slim->run();
	}
}

 ?>