<?php

class Cuota extends Controller {

    function __construct() {
        if (!isset($_SESSION['id']))
            session_start();
        $this->cuota = $this->model('Cuota_model');
    }

    // Carga bloque de twig en el backend

    // TESTEADA

    public function controlCuota($mensaje = '') {
        if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            if($_SESSION['rol'] == "administrador" or $_SESSION['rol'] == "gestion") { // Si el usuario es administrador o gestion
                $listado = $this->model('Listado_model');
                $cuotas = $listado->listarCuotas();
                $template = loadTwig("ABMCuota.twig", $mensaje);
                $template->display(array('cuotas' => $cuotas));
            }
            else
                $this->controlador('Home')->vistaNoEncontrada(); // Redirigir a otro lado porque no es administrador o gestion
        }
        else
            $this->controlador('Home')->vistaNoEncontrada(); // Redirigir a otro lado porque no esta logueado
    }

    // Se cargan las vistas del backend
    
    // TESTEADA

    public function vistaAltaCuota($parametro = '') {
        if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            if($_SESSION['rol'] == "administrador" or $_SESSION['rol'] == "gestion") { // Si el usuario es administrador o gestion
                $template = loadTwig("altaCuota.twig", $parametro);
                $template->display(array());
            }
            else
                $this->controlador('Home')->vistaNoEncontrada(); // Redirigir a otro lado porque no es administrador o gestion
        }
        else 
            $this->controlador('Home')->vistaNoEncontrada(); // Redirigir a otro lado porque no esta logueado
    }

    // TESTEADA

    public function vistaModificarCuota($mensaje = '') {
        if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            if($_SESSION['rol'] == "administrador" or $_SESSION['rol'] == "gestion") { // Si el usuario es administrador o gestion
                if($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $idCuota = $_POST['idCuota'];
                        $cuotaModel = $this->model('Cuota_model');
                        $cuota = $cuotaModel->obtenerCuota($idCuota);
                        if($cuota){ //si existe la cuota];
                            $template = loadTwig("modificarCuota.twig", $mensaje);
                            $template->display(array('cuota' => $cuota));
                        }
                        else{
                            header('Location: controlCuota'); //hay que enviar mensaje de error
                        }
                }
                else {
                    // Caso en el que no exista un envio por POST
                    $this->controlador('Home')->vistaNoEncontrada();
                }
            }
            else {
                // Caso en el que el usuario no sea administrador o gestion
                $this->controlador('Home')->vistaNoEncontrada();
            }
        }
        else {
            // Caso en el que el usuario no este logueado
            $this->controlador('Home')->vistaNoEncontrada();
        }
    }

    // TESTEADA

    public function vistaEliminarCuota($mensaje = '') {
        if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            if($_SESSION['rol'] == "administrador" or $_SESSION['rol'] == "gestion") { // Si el usuario es administrador o gestion
                if($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $idCuota = $_POST["idCuota"];
                    $cuotaModel = $this->model('Cuota_model');
                    if($cuotaModel->existeCuota($idCuota)) {
                        $cuota = $cuotaModel->obtenerCuota($idCuota);
                        $template = loadTwig("eliminarCuota.twig", $mensaje);
                        $template->display(array('cuota' => $cuota));
                    }
                    else {
                        header('Location: controlCuota'); //falta enviar mensaje de error
                    }
                }
                else {
                    $this->controlador('Home')->vistaNoEncontrada();
                }
            }
            else {
                $this->controlador('Home')->vistaNoEncontrada();
            }
        }
        else {
            $this->controlador('Home')->vistaNoEncontrada();
        }
    }

    // TESTEADA

    public function vistaGestionarCuotas($parametro = '') {
        if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            if($_SESSION['rol'] == "administrador" or $_SESSION['rol'] == "gestion") { // Si el usuario es administrador o gestion
                $listado = $this->model('Listado_model');
                $alumnos = $listado->listarAlumnos();
                $template = loadTwig("listadoAlumnosCuotas.twig", $parametro);
                $template->display(array('alumnos' => $alumnos));
            }
            else {
                $this->controlador('Home')->vistaNoEncontrada();
            }
        }
        else {
            $this->controlador('Home')->vistaNoEncontrada();
        }
    }

    public function controlCuotaAlumno($parametro = '') {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(isset($_SESSION['id'])) { // Si existe el usuario esta logueado
                if($_SESSION['rol'] == "administrador" or $_SESSION['rol'] == "gestion" ) { // Si el usuario es administrador
                    $validar = $this->controlador('Validar');
                    $validado = $validar->validarEntero($_POST['idAlumno'], 1, 9999999); //valida que idAlumno sea realmente entero.
                    $alu = $this->model('Alumno_model');
                    $aluExiste = $alu->existeIdAlumno($_POST['idAlumno']);
                    if($validado and $aluExiste){
                        $listado = $this->model('Listado_model');
                        // Se cargan las cuotas impagas
                        $cuotasImpagas = $listado->listarCuotasImpagasAlumnos($_POST['idAlumno']);
                        $datos[0]= $_POST['idAlumno'];
                        $datos[1]= $cuotasImpagas;
                        $datos[2]= $_POST['apellido'];
                        $datos[3]= $_POST['nombre'];
                        $template = loadTwig("ABMCuotasImpagas.twig", $_SESSION['elementos']);
                        $template->display(array('datos' => $datos));

                        // Se cargan las cuotas vencidas
                        $cuotasImpagas = $listado->listarCuotasVencidasAlumnos($_POST['idAlumno']);
                        $datos[1]= $cuotasImpagas;
                        $template = loadTwig("ABMCuotasVencidas.twig", $_SESSION['elementos']);
                        $template->display(array('datos' => $datos));

                        // Se cargan las cuotas pagas
                        $cuotasPagas = $listado->listarCuotasPagasAlumnos($_POST['idAlumno']);
                        $datos[1]= $cuotasPagas;
                        $template = loadTwig("ABMCuotasPagas.twig", $_SESSION['elementos']);
                        $template->display(array('datos' => $datos));
                    }
                    else 
                        header('Location: ../Cuota/vistaGestionarCuotas'); // falta mandar mensaje de error
                }
                else 
                    $this->controlador('Home')->vistaNoEncontrada(); // Redirigir a otro lado porque no es administrador
            }
            else 
                $this->controlador('Home')->vistaNoEncontrada(); // Redirigir a otro lado porque no esta logueado
        }
        else 
            $this->controlador('Home')->vistaNoEncontrada(); // Redirigir a otro lado porque no esta logueado
    }

    // Funcionalidades llamadas desde las vistas

    // TESTEADA
    
    public function agregarCuota() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
                "error" => array(),
                "post" => $_POST
            ];
            $datos["error"] = $this->cuota->setDatos($datos["post"], $datos["error"]);
            if(empty($datos["error"])) {
                $datos["error"] = $this->cuota->esValido($datos["error"]);
                if(empty($datos["error"])) {
                    $this->cuota->save();
                    $datos['exito'] = "Cuota agregada con exito";
                    $this->controlCuota($datos);
                }
                else {
                    $this->vistaAltaCuota($datos);
                }
            }
            else {
                $this->vistaAltaCuota($datos);
            }
        }
        else {
            $this->controlador('Home')->vistaNoEncontrada();
        }
    }

    // TESTEADA

    public function modificarCuota() {

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
                "error" => array(),
                "post" => $_POST
            ];
            $datos["error"] = $this->cuota->setDatos($datos["post"], $datos["error"]);
            if(empty($datos["error"])) {
                $datos["error"] = $this->cuota->esValidoModificar($datos['post']['idCuota'],$datos["error"]);
                if(empty($datos["error"])) {
                    $datos = $this->cuota->edit($datos);
                    if (empty($datos['error']))
                    $this->controlCuota("Cuota modificada exitosamente.");
                    else{
                        $this->vistaModificarCuota($datos);
                    }
                }
                else {
                    $this->vistaModificarCuota($datos);
                }
            }
            else {
                $this->vistaModificarCuota($datos);
            }
        }
        else {
            $this->controlador('Home')->vistaNoEncontrada();
        }
    }

        /* if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idCuota= $_POST['idCuota'];
            unset($_POST['idCuota']); //Esto lo hago para usar el validador de cuota, que usa 6 elementos. En el modificar tenemos 7 por el campo hidden que es el idcuota, no entraría al if del validador.
            $validar = $this->controlador('Validar');
            $mensaje = $validar->cuota();
            $_POST['idCuota']= $idCuota; //lo reasigno por el bug de la linea 201                     
            if(empty($mensaje)){ //No hay mensajes de errores, da OK.            
                $cuota = [
                "anio" => $_POST["anio"],
                "mes" => $_POST["mes"],
                "numero" => $_POST["numero"],
                "monto" => $_POST["monto"],
                "tipo" => $_POST["tipo"],
                "comisionCobrador" => $_POST["comisionCobrador"],
                ];
                $cuotaModel = $this->model('Cuota_model'); // Genera una instancia de Cuota_model (new Cuota_model)
                if ($cuotaModel->modificarCuota($idCuota, $cuota)) {
                    $msj[0]="exito";
                    $msj[1]="Cuota modificada con exito.";
                    $this->controlCuota($msj);
                }
                else {
                    $mensaje[0]="error"; 
                    $mensaje[1]="No pudo modificarse la cuota ya que hubo una paga/beca realizada.";
                    $this->vistaModificarCuota($mensaje);
                }
            }
            else
                $this->vistaModificarCuota($mensaje); 
        }
        else {
            $this->controlador('Home')->vistaNoEncontrada();
        }
    } */


    public function eliminarCuota() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idCuota = $_POST['idCuota'];
            $cuotaModel = $this->model('Cuota_model');
            if($cuotaModel->existeCuota($idCuota)) {
                if ($cuotaModel->eliminarCuota($idCuota)) {
                    $msj[0]="exito";
                    $msj[1]="Cuota eliminada con exito.";
                    $this->controlCuota($msj);
                }
                else {
                    $msj[0]="error";
                    $msj[1]="No pudo eliminarse la cuota ya que hubo una paga/beca realizada.";
                    $this->vistaEliminarCuota($msj);
                }
            }
            else {
                $msj[0]="error";
                $msj[1]="No existe la cuota a eliminar.";
                $this->vistaEliminarCuota($msj);
            }
        }
        else {
            $this->controlador('Home')->vistaNoEncontrada();
        }
    }

    // Modulo Pago-Cuota
    
    public function pagarBecarAlumno($value='') {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(isset($_POST['submit'])) {
                if(!empty($_POST['check_list'])) {
                    if($_POST['submit'] == 'Becar') {
                        $esBecado = 1;
                    }
                    else
                        $esBecado = 0;
                    foreach($_POST['check_list'] as $idCuota) {
                        $this->pagarCuota($idCuota, $_POST['idAlumno'], $esBecado);
                    }
                    $mensaje = "Cuotas pagadas con éxito";
                    $this->vistaGestionarCuotas($mensaje);
                }
                else{
                    $mensaje = "No se ha seleccionado ninguna cuota";
                    $this->vistaGestionarCuotas($mensaje);
                }
            }
        }
        else
            $this->controlador('Home')->vistaNoEncontrada(); // Redirigir a otro lado porque no esta logueado
    }

    public function pagarCuota($idCuota, $idAlumno, $esBecado) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(isset($_POST['submit'])) {
                $array = [
                "idAlumno" => $idAlumno, // Esto no va en la tabla de cuota, pero lo coloco para verificar en el modelo de que se trata de un alumno existente
                "idCuota" => $idCuota,
                "becado" => $esBecado,
                "fechaAlta" => date("Y-m-d")
                ];

                $cuota = $this->model('Cuota_model'); // Genera una instancia de Cuota_model (new Cuota_model)
                $cuota->pagarCuota($array);
            }
            else
                $this->controlador('Home')->vistaNoEncontrada(); // Redirigir a otro lado porque no es administrador
        }
        else 
            $this->controlador('Home')->vistaNoEncontrada(); // Redirigir a otro lado porque no es administrador
    }      


}

?>