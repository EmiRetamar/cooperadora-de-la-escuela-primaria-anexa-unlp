<?php

class Responsable extends Controller {

    private $alumno; //Dejar esto como está. Es para asignarle un Alumno al responsable.
    private $listado;

    public function __construct() {
        session_start();
        $this->alumno = $this->model('Alumno_model');
        $this->listado = $this->model('Listado_model');
    }

    // Carga bloque de twig en el backend

    public function controlResponsable($parametro = '') {
        if(isset($_SESSION['id'])) { // Si existe una sesion
            if($_SESSION['rol'] == "administrador") { // Si el usuario es administrador
                $listado = $this->model('Listado_model');
                $responsables = $listado->listarResponsables();
                $template = loadTwig("ABMResponsable.twig", $parametro);
                $template->display(array('responsables' => $responsables));
            }
            else
                header('Location: ../'); // Redirigir a otro lado porque no es administrador
        }
        else
            header('Location: ../'); // Redirigir a otro lado porque no esta logueado
    }

    // Se cargan las vistas del backend
    
    public function vistaAltaResponsable() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $alumno = [
            "tipoDocumento" => $_POST["tipoDocumento"],
            "numeroDocumento" => $_POST["numeroDocumento"],
            "apellido" => $_POST["apellido"],
            "nombre" => $_POST["nombre"],
            "fechaNacimiento" => $_POST["fechaNacimiento"],
            "sexo" => $_POST["sexo"],
            "mail" => $_POST["mail"],
            "direccion" => $_POST["direccion"],
            "fechaIngreso" => $_POST["fechaIngreso"],
            "fechaAlta" => date("Y-m-d"),
            "cantidadResponsables" => $_POST["cantidadResponsables"]
            ];
            $template = loadTwig("altaResponsable.twig");
            $template->display(array('alumno' => $alumno));
        }
    }

    public function vistaModificarResponsable($parametro = '') {
        if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            if($_SESSION['rol'] == "administrador") { // Si el usuario es administrador
                if($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $idResponsable = $_POST['idResponsable'];
                    $responsableModel = $this->model('Responsable_model');
                    if($responsableModel->existeResponsable($idResponsable)) {
                        $responsable = $responsableModel->obtenerResponsable($idResponsable);
                        $template = loadTwig("modificarResponsable.twig", $parametro);
                        $template->display(array('responsable' => $responsable));
                    }
                    else {
                        header('Location: controlResponsable');
                    }
                }
                else {
                    header('Location: controlResponsable');
                }
            }
            else {
                header('Location: ../Home/vistaBackend');
            }
        }
        else {
            header('Location: ../');
        }
    }


    public function vistaAsignarAlumno() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idResponsable = $_POST['idResponsable'];
            $alumnos = $this->listado->listarAlumnos();
            $asignados = $this->listado->alumnosAsignados($idResponsable);
            $template = loadTwig("asignarAlumno.twig");
            $template->display(array('idResponsable' => $idResponsable, 'alumnos' => $alumnos, 'asignados' => $asignados));
        }
        else {
            header('Location: controlResponsable');
        }
    }

    public function asignarAlumno() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idAlumno = $_POST['idAlumno'];
            $idResponsable = $_POST['idResponsable'];
            $error = $this->alumno->asignarResponsable($idAlumno, $idResponsable);
            if (!isset($error)) {
                $datos["exito"] = "Alumno asignado con exito";
                $this->controlResponsable($datos);
            }
            else{
                $datos["error"] = $error;
                $this->controlResponsable($datos);
            }
        }
        else {
            header('Location: controlResponsable');
        }
    }

    public function modificarResponsable() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $validar = $this->controlador('Validar');
            $mensaje = $validar->validarEmail($_POST["mail"]);
            if(!isset($mensaje)) {
                $idResponsable = $_POST['idResponsable'];
                $responsable = [
                "tipo" => $_POST["tipo"],
                "apellido" => $_POST["apellido"],
                "nombre" => $_POST["nombre"],
                "fechaNacimiento" => $_POST["fechaNacimiento"],
                "sexo" => $_POST["sexo"],
                "mail" => $_POST["mail"],
                "telefono" => $_POST['telefono'],
                "direccion" => $_POST["direccion"]
                ];
                $responsableModel = $this->model('Responsable_model');
                if($responsableModel->existeResponsable($idResponsable)) {
                    // Si el mail ingresado no existe se modifica el responsable, si ya existe en el sistema no se modifica el responsable y muestra un error (no tiene en cuenta el mail del propio responsable a modificar)
                    if(!$responsableModel->existeOtroMailResponsableIgual($idResponsable, $responsable['mail'])) {
                        $idUsuarioResponsable = $responsableModel->obtenerIdUsuarioResponsable($idResponsable);
                        // Si el usuario tiene cuenta
                        if($idUsuarioResponsable != null) {
                            $responsableModel->actualizarMailCuentaUsuarioResponsable($idUsuarioResponsable, $responsable['mail']);
                        }
                        $responsableModel->modificarResponsable($idResponsable, $responsable);
                        header('Location: controlResponsable');
                    }
                    // Else de que el mail ya existe
                    else {
                        $mensaje = 'No se pudo modificar el responsable debido a que el mail '.$responsable['mail'].' ya esta registrado en el sistema';
                        $this->vistaModificarResponsable($mensaje);
                    }
                }
                // Else de que no existe el responsable
                else {
                    $mensaje = 'Error, el responsable que usted quiere modificar no existe en el sistema';
                    $this->vistaModificarResponsable($mensaje);
                }
            }
            else {
                $this->vistaModificarResponsable($mensaje);
            }
        }
        // Else de que no hay un envio por POST
        else {
            header('Location: controlResponsable');
        }
    }


    public function vistaEliminarResponsable($parametro = '') {
        if(isset($_SESSION['id'])) { // Si el usuario esta logueado
            if($_SESSION['rol'] == "administrador") { // Si el usuario es administrador
                if($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $idResponsable = $_POST["idResponsable"];
                    $responsableModel = $this->model('Responsable_model');
                    if($responsableModel->existeResponsable($idResponsable)) {
                        $responsable = $responsableModel->obtenerResponsable($idResponsable);
                        $template = loadTwig("eliminarResponsable.twig", $parametro);
                        $template->display(array('responsable' => $responsable));
                    }
                    else {
                        header('Location: controlResponsable');
                    }
                }
                else {
                    header('Location: controlResponsable');
                }
            }
            else {
                header('Location: ../Home/vistaBackend');
            }
        }
        else {
            header('Location: ../');
        }
    }


    public function eliminarResponsable() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idResponsable = $_POST['idResponsable'];
            $responsableModel = $this->model('Responsable_model');
            $alumnos = $responsableModel->obtenerAlumnosAsignados($idResponsable);
            $alumnoModel = $this->model('Alumno_model');
            $error = false;
            // Si uno de los alumnos a cargo del responsable a eliminar tiene asignado UN SOLO RESPONSABLE (el cual seria este mismo) este responsable no puede eliminarse
            foreach ($alumnos as $alumno) {
                if($alumnoModel->cantidadResponsables($alumno['idAlumno']) == 1)
                    $error = true;
            }
            if(!$error) {
                $responsableModel->eliminarResponsable($idResponsable);
                header('Location: controlResponsable');
            }
            else {
                $mensaje = "Error, no se puede eliminar el responsable porque sus alumnos asignados no pueden quedarse sin responsables, y hay alumnos que solo dependen de este responsable";
                $this->vistaEliminarResponsable($mensaje);
            }
        }
        else {
            header('Location: controlResponsable');
        }
    }

}

?>