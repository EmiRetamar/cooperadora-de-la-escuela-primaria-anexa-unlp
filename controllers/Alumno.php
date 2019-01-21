<?php

class Alumno extends Controller {

    private $alumno;
    private $listado;

    public function __construct() {
        session_start();
        $this->alumno = $this->model('Alumno_model');
        $this->listado = $this->model('Listado_model');
    }

    // Carga bloque de twig en el backend


    public function controlAlumno($parametro = '') {
        if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            if($_SESSION['rol'] == "administrador") { // Si el usuario es administrador
                $listado = $this->model('Listado_model');
                $alumnos = $listado->listarAlumnos();
                $responsables = $listado->listarResponsables();
                $template = loadTwig("ABMAlumno.twig", $parametro);
                $template->display(array('alumnos' => $alumnos, 'responsables' => $responsables));
            }
            else
                $this->controlador('Home')->vistaNoEncontrada(); // Redirigir a otro lado porque no es administrador
        }
        else
            $this->controlador('Home')->vistaNoEncontrada(); // Redirigir a otro lado porque no esta logueado
    }

    // Se cargan las vistas del backend

    public function vistaAltaAlumno($parametro = '') {
        if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            if($_SESSION['rol'] == "administrador") { // Si el usuario es administrador
                $responsableModel = $this->model('Responsable_model');
                $responsables = $responsableModel->obtenerResponsables();
                $template = loadTwig("altaAlumno.twig", $parametro);
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

    public function agregarAlumnoResponsable() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
            "post" => $_POST,
            "error" => array()
            ];
            $datos["error"] = $this->alumno->setDatos($datos["post"], $datos["error"]);
            if(empty($datos["error"])) {
                $datos["error"] = $this->alumno->esValido($datos["error"]);
                if(empty($datos["error"])) {
                    $datos["error"] = $this->alumno->save($datos["error"]);
                    if(empty($datos["error"])) {
                        $datos["exito"] = "Alumno agregado con exito";
                        $this->controlAlumno($datos);
                    }
                    else {
                        $this->vistaAltaAlumno($datos);
                    }
                }
                else {
                    $this->vistaAltaAlumno($datos);
                }
            }
            else {
                $this->vistaAltaAlumno($datos);
            }
        }
        else {
            header('Location: controlAlumno');
        }
    }

    public function vistaAsignarResponsable() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idAlumno = $_POST['idAlumno'];
            $responsables = $this->listado->listarResponsables();
            $asignados = $this->listado->responsablesAsignados($idAlumno);
            $template = loadTwig("asignarResponsable.twig");
            $template->display(array('idAlumno' => $idAlumno, 'responsables' => $responsables, 'asignados' => $asignados));
        }
        else {
            header('Location: controlAlumno');
        }
    }

    public function asignarResponsable() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idAlumno = $_POST['idAlumno'];
            $idResponsable = $_POST['idResponsable'];
            $error = $this->alumno->asignarResponsable($idAlumno, $idResponsable);
            if (!isset($error)) {
                $datos["exito"] = "Responsable asignado con exito";
                $this->controlAlumno($datos);
            }
            else{
                $datos["error"] = $error;
                $this->controlAlumno($datos);
            }
        }
        else {
            header('Location: controlAlumno');
        }
    }

    public function vistaModificarAlumno($parametro = '') {
        if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            if($_SESSION['rol'] == "administrador") { // Si el usuario es administrador
                if($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $idAlumno = $_POST['idAlumno'];
                    // Si existe el alumno se lo obtiene y se carga el formulario, sino vuelve a la vista anterior
                    if($this->alumno->existeAlumno($idAlumno)) {
                        $alumno = $this->alumno->obtenerAlumno($idAlumno);
                        $template = loadTwig("modificarAlumno.twig", $parametro);
                        $template->display(array('alumno' => $alumno));
                    }
                    else {
                        $datos["error"] = "El alumno a modificar no existe";
                        $this->controlAlumno($datos);
                    }
                }
                else {
                    header('Location: controlAlumno');
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

    public function modificarAlumno() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = [
            "post" => $_POST,
            "error" => array()
            ];
            $datos["error"] = $this->alumno->setDatos($datos["post"], $datos["error"]);
            if(empty($datos["error"])) {
                $datos["error"] = $this->alumno->esValido($datos["error"]);
                if(empty($datos["error"])) {
                    $datos["error"] = $this->alumno->edit($datos["error"]);
                    if(empty($datos["error"])) {
                        $datos["exito"] = "Alumno modificado con exito";
                        $this->controlAlumno($datos);
                    }
                    else {
                        $this->vistaModificarAlumno($datos);
                    }
                }
                else {
                    $this->vistaModificarAlumno($datos);
                }
            }
            else {
                $this->vistaModificarAlumno($datos);
            }
        }
        else {
            header('Location: controlAlumno');
        }
    }

    public function vistaEliminarAlumno($parametro = '') {
        if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            if($_SESSION['rol'] == "administrador") { // Si el usuario es administrador
                if($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $idAlumno = $_POST["idAlumno"];
                    if($this->alumno->existeAlumno($idAlumno)) {
                        $alumno = $this->alumno->obtenerAlumno($idAlumno);
                        $template = loadTwig("eliminarAlumno.twig", $parametro);
                        $template->display(array('alumno' => $alumno));
                    }
                    else {
                        $datos["error"] = "El alumno a eliminar no existe";
                        $this->controlAlumno($datos);
                    }
                }
                else {
                    // Caso en el que se quiere acceder a traves de la url
                    header('Location: controlAlumno');
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

    public function eliminarAlumno() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos["error"] = '';
            $datos["error"] = $this->alumno->delete($datos["error"]);
            if(empty($datos["error"])) {
                $datos["exito"] = "Alumno eliminado con exito";
                $this->controlAlumno($datos);
            }
            else {
                $this->vistaEliminarAlumno($datos);
            }
        }
        else {
            header('Location: controlAlumno');
        }
    }


    // Listados y estadísticas.

    public function matriculasPagas() {
       if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            $listado = $this->model('Listado_model');
            if($_SESSION['rol'] == "administrador" or $_SESSION['rol'] == "gestion")
                $matriculas = $listado->listarMatriculasPagas();
            else
                $matriculas = $listado->listarMatriculasPagasResponsable($_SESSION['id']);
            $template = loadTwig("listadoAlumnosMatriculasPagas.twig");
            $template->display(array('matriculas' => $matriculas));
        }
        else {
            $this->controlador('Home')->vistaNoEncontrada(); // Redirigir porque no esta logueado
        }
    }


    public function recorridos() {
        if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            if($_SESSION['rol'] == "administrador" or $_SESSION['rol'] == "gestion"){
                $listado = $this->model('Listado_model');
                $alumnos = $listado->listarAlumnos();
                $template = loadTwig("recorridos.twig");
                $template->display(array('alumnos' => $alumnos));
            }
            else
            $this->controlador('Home')->vistaNoEncontrada(); // Redirigir porque no esta logueado
        }
        else
            $this->controlador('Home')->vistaNoEncontrada(); // Redirigir porque no esta logueado
    }

    public function estadisticas() {
        if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            $listado = $this->model('Listado_model');
            if($_SESSION['rol'] == "administrador" or $_SESSION['rol'] == "gestion")
                $cuotas = $listado->listarCuotasPagas();
            else
                $cuotas = $listado->listarCuotasPagasResponsable($_SESSION['id']);
            $template = loadTwig("estadisticas.twig");
            $template->display(array('cuotas' => $cuotas));
        }
        else {
            $this->controlador('Home')->vistaNoEncontrada(); // Redirigir porque no esta logueado
        }
    }

    public function cuotasPagas() {
        if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            $listado = $this->model('Listado_model');
            if($_SESSION['rol'] == "administrador" or $_SESSION['rol'] == "gestion")
                $cuotas = $listado->listarCuotasPagas();
            else
                $cuotas = $listado->listarCuotasPagasResponsable($_SESSION['id']);
            $template = loadTwig("listadoAlumnosCuotasPagas.twig");
            $template->display(array('cuotas' => $cuotas));
        }
        else {
            $this->controlador('Home')->vistaNoEncontrada(); // Redirigir porque no esta logueado
        }
    }

    public function cuotasVencidas() {
        if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            $listado = $this->model('Listado_model');
            if($_SESSION['rol'] == "administrador" or $_SESSION['rol'] == "gestion")
                $cuotas = $listado->listarCuotasVencidas();
            else
                $cuotas = $listado->listarCuotasVencidasResponsable($_SESSION['id']);
            $template = loadTwig("listadoAlumnosCuotasVencidas.twig");
            $template->display(array('cuotas' => $cuotas));
        }
        else {
            $this->controlador('Home')->vistaNoEncontrada(); // Redirigir porque no esta logueado
        }
    }

     public function vistaCuotasPagasPDF($parametro = '') {
        if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            $template = loadTwig("generarPDF.twig", $parametro);
            $template->display(array());
        }
        else {
            $this->controlador('Home')->vistaNoEncontrada(); // Redirigir porque no esta logueado
        }
     }

     public function cuotasPagasPDF() {
        if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            $listado = $this->model('Listado_model');
            if($_SESSION['rol'] == "administrador" or $_SESSION['rol'] == "gestion")
                $cuotas = $listado->listarCuotasPagasPorMesAnio($_POST['mes'], $_POST['anio']);
            else
                $cuotas = $listado->listarCuotasPagasResponsablePorMesAnio($_SESSION['id'], $_POST['mes'], $_POST['anio']);
            $template = loadTwig("listadoAlumnosCuotasPagas.twig");
            $template->display(array('cuotas' => $cuotas));
        }
        else {
            $this->controlador('Home')->vistaNoEncontrada(); // Redirigir porque no esta logueado
        }
    }

    public function printToPDF($value='')
    {
        require_once("lib/dompdf/dompdf_config.inc.php");

        $html =
          '<html><body>'.
          '<p>Put your html here, or generate it with your favourite '.
          'templating system.</p>'.
          '</body></html>';

        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->render();
        $dompdf->stream("sample.pdf");
    }

}

?>