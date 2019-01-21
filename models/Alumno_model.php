<?php

require_once 'PDOConnection.php';
require_once 'Responsable_model.php';

class Alumno_model {

    private  $id;
    private  $tipoDocumento;
    private  $numeroDocumento;
    private  $apellido;
    private  $nombre;
    private  $fechaNacimiento;
    private  $sexo;
    private  $mail;
    private  $direccion;
    private  $fechaIngreso;
    private  $fechaEgreso;
    private  $fechaAlta;
    private  $responsable;
    private  $pdo;

    function __construct() {
        $this->pdo = new PDOConnection();
        $this->responsable = new Responsable_model();
    }

    public function getId() {
        return ($this->id);
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTipoDocumento() {
        return ($this->tipoDocumento);
    }

    public function setTipoDocumento($tipoDocumento) {
        $this->tipoDocumento = $tipoDocumento;
    }

    public function getNumeroDocumento() {
        return ($this->numeroDocumento);
    }

    public function setNumeroDocumento($numeroDocumento) {
        $this->numeroDocumento = $numeroDocumento;
    }

    public function getApellido() {
        return ($this->apellido);
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    public function getNombre() {
        return ($this->nombre);
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getFechaNacimiento() {
        return ($this->fechaNacimiento);
    }

    public function setFechaNacimiento($fechaNacimiento) {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    public function getSexo() {
        return ($this->sexo);
    }

    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    public function getMail() {
        return ($this->mail);
    }

    public function setMail($mail) {
        $this->mail = $mail;
    }

    public function getDireccion() {
        return ($this->direccion);
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function getFechaIngreso() {
        return ($this->fechaIngreso);
    }

    public function setFechaIngreso($fechaIngreso) {
        $this->fechaIngreso = $fechaIngreso;
    }

    public function getFechaEgreso() {
        return ($this->fechaEgreso);
    }

    public function setFechaEgreso($fechaEgreso) {
        $this->fechaEgreso = $fechaEgreso;
    }

    public function getFechaAlta() {
        return ($this->fechaAlta);
    }

    public function setFechaAlta($fechaAlta) {
        $this->fechaAlta = $fechaAlta;
    }

    public function setDatos($args, $error) {
        // TIPO DOC
        if(isset($args['tipoDocumento']))
            $this->setTipoDocumento($args['tipoDocumento']);
        else
            $error['tipoDocumento'] = "Este campo no debe estar vacio";
        // NUMERO DOC
        if(isset($args['numeroDocumento']))
            $this->setNumeroDocumento($args['numeroDocumento']);
        else
            $error['numeroDocumento'] = "Este campo no debe estar vacio";
        // APELLIDO
        if(isset($args['apellido']))
            $this->setApellido($args['apellido']);
        else
            $error['apellido'] = "Este campo no debe estar vacio";
        // NOMBRE
        if(isset($args['nombre']))
            $this->setNombre($args['nombre']);
        else
            $error['nombre'] = "Este campo no debe estar vacio";
        // FECHA DE NACIMIENTO
        if(isset($args['fechaNacimiento']))
            $this->setFechaNacimiento($args['fechaNacimiento']);
        else
            $error['fechaNacimiento'] = "Este campo no debe estar vacio";
        // SEXO
        if(isset($args['sexo']))
            $this->setSexo($args['sexo']);
        else
            $error['sexo'] = "Este campo no debe estar vacio";
        // FECHA DE INGRESO
        if(isset($args['fechaIngreso']))
            $this->setFechaIngreso($args['fechaIngreso']);
        else
            $error['fechaIngreso'] = "Este campo no debe estar vacio";
        // MAIL
        if(isset($args['mail']))
            $this->setMail($args['mail']);
        else
            $error['mail'] = "Este campo no debe estar vacio";
        // DIRECCION
        if(isset($args['direccion']))
            $this->setDireccion($args['direccion']);
        else
            $error['direccion'] = "Este campo no debe estar vacio";
        // ID
        if(isset($args['idAlumno'])) // Si existe idAlumno significa que el usuario selecciono "Modificar alumno", de lo contrario selecciono "Agregar alumno"
            $this->setId($args['idAlumno']); // Es la forma que encontre de hacer reusable este metodo, sino hay que hacer uno nuevo solo para el modificar alumno
        else {
            // SELECT
            if(isset($args['responsable'])) {
                if($args['responsable'] == 'elegir') {
                    if(isset($args['mailResponsableExistente']))
                        $this->responsable->setMail($args['mailResponsableExistente']);
                    else
                        $error['mailResponsableExistente'] = "Este campo no debe estar vacio";
                }
                else {
                    if($args['responsable'] == 'cargar')
                        $this->responsable->setDatos($args, $error);
                    else
                        $error['responsable'] = "Este campo no debe ser cambiado";
                }
            }
            else
                $error['responsable'] = "Este campo no debe estar vacio";
        }
        // FECHA DE ALTA
        $this->setFechaAlta(date('Y-m-d'));
        return ($error);
    }

    // VALIDADORES

    public function esValidoTipoDocumento($tipo, $error) {
        if($tipo == "DNI" or $tipo == "CI" or $tipo == "LE" or $tipo == "LC" )
            return ($error);
        else
            $error['tipoDocumento'] = "Este campo no cumple el formato solicitado";
        return ($error);
    }

    public function esValidoNumeroDocumento($numeroDocumento, $error) {
        if(preg_match('/^[0-9]{8}$/', $numeroDocumento))
            return ($error); // Error seria un arreglo vacio, por lo tanto queda validado OK
        else
            $error['numeroDocumento'] = "Este campo no cumple el formato solicitado";
        return ($error);
    }

    public function esValidoApellido($apellido, $error) {
        if(preg_match("/^[a-z'\s]{2,30}$/i", $apellido))
            return ($error); // Error seria un arreglo vacio, por lo tanto queda validado OK
        else
            $error['apellido'] = "Este campo no cumple el formato solicitado";
        return ($error);
    }

    public function esValidoNombre($nombre, $error) {
        if(preg_match("/^[a-z'\s]{2,30}$/i", $nombre))
            return ($error); // Error seria un arreglo vacio, por lo tanto queda validado OK
        else
            $error['nombre'] = "Este campo no cumple el formato solicitado";
        return ($error);
    }

    public function esValidoFechaNacimiento($fechaNacimiento, $error) {
        if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fechaNacimiento))
            return ($error);
        else
            $error['fechaNacimiento'] = "Este campo no cumple el formato solicitado";
        return ($error);
    }

    public function esValidoSexo($sexo, $error) {
        if($sexo == "M" or $sexo == "F")
            return ($error); // Error seria un arreglo vacio, por lo tanto queda validado OK
        else
            $error['sexo'] = "Este campo no cumple el formato solicitado";
        return ($error);
    }

    public function esValidoMail($mail, $error) {
        if(filter_var($mail, FILTER_VALIDATE_EMAIL))
            return ($error); // Error seria un arreglo vacio, por lo tanto queda validado OK
        else
            $error['mail'] = "Este campo no cumple el formato solicitado";
        return ($error);
    }

    public function esValidoDireccion($direccion, $error) {
        if(preg_match('/^[a-z0-9- \s]{2,50}+$/i', $direccion))
            return ($error); // Error seria un arreglo vacio, por lo tanto queda validado OK
        else
            $error['direccion'] = "Este campo no cumple el formato solicitado";
        return ($error);
    }

    public function esValidoFechaIngreso($fechaIngreso, $error) {
        if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fechaIngreso))
            return ($error);
        else 
            $error['fechaIngreso']="Este campo no cumple el formato solicitado";
        return ($error);
    }

    public function esValido($error) {
        $error = $this->esValidoTipoDocumento($this->getTipoDocumento(), $error);
        $error = $this->esValidoNumeroDocumento($this->getNumeroDocumento(), $error);
        $error = $this->esValidoApellido($this->getApellido(), $error);
        $error = $this->esValidoNombre($this->getNombre(), $error);
        $error = $this->esValidoFechaNacimiento($this->getFechaNacimiento(), $error);
        $error = $this->esValidoSexo($this->getSexo(), $error);
        $error = $this->esValidoMail($this->getMail(), $error);
        $error = $this->esValidoDireccion($this->getDireccion(), $error);
        $error = $this->esValidoFechaIngreso($this->getFechaIngreso(), $error);
        if($this->getId() == null) { // En caso de que el idAlumno sea null significa que el usuario selecciono la opcion de "Agregar alumno", caso contrario selecciono "Modificar alumno"
            if(is_null($this->responsable->getNombre())) // Si el nombre del responsable no fue seteado, es porque se selecciono elegir (tiene solo cargado el mail)
                $error = $this->responsable->esValidoMail($this->responsable->getMail(), $error);
            else
                $error = $this->responsable->esValido($error);
        }
        return ($error);
    }

    public function save($error) {
        if(!$this->existeDniAlumno($this->getNumeroDocumento())) {
            if(!$this->existeMailAlumno($this->getMail())) {
                if(!is_null($this->responsable->getNombre())) { // Si el nombre del responsable fue seteado, es porque se selecciono cargar
                    if(!$this->responsable->existeMailResponsable($this->responsable->getMail())) {
                        $this->crearAlumno();
                        $this->responsable->crearResponsable();
                        $idAlumno = $this->obtenerIdAlumno($this->getNumeroDocumento());
                        $idResponsable = $this->responsable->obtenerIdResponsable($this->responsable->getMail());
                        $this->asignarResponsable($idAlumno, $idResponsable);
                    }
                    else {
                        $error['mailResponsable'] = 'No se pudo crear el responsable debido a que este mail ya esta registrado en el sistema';
                    }
                }
                else { // Se selecciono elegir
                    if($this->responsable->existeMailResponsable($this->responsable->getMail())) {
                        $this->crearAlumno();
                        $idAlumno = $this->obtenerIdAlumno($this->getNumeroDocumento());
                        $idResponsable = $this->responsable->obtenerIdResponsable($this->responsable->getMail());
                        $this->asignarResponsable($idAlumno, $idResponsable);
                    }
                    else {
                        $error['mailResponsable'] = 'No se pudo crear el responsable debido a que este mail ya esta registrado en el sistema';
                    }
                }
            }
            else {
                $error['mail'] = 'No se pudo crear el alumno debido a que este mail ya esta registrado en el sistema';
            }
        }
        else {
            $error['numeroDocumento'] = 'No se pudo crear el alumno debido a que este DNI ya esta registrado en el sistema';
        }
        return ($error);
    }

    public function edit($error) {
        if($this->existeAlumno($this->getId())) {
            // Si el DNI ingresado no existe se modifica el alumno, si ya existe en el sistema no se modifica el alumno y muestra un error (no tiene en cuenta el DNI del propio alumno a modificar)
            if(!$this->existeOtroDniAlumnoIgual($this->getId(), $this->getNumeroDocumento())) {
                // Si el mail ingresado no existe se modifica el alumno, si ya existe en el sistema no se modifica el alumno y muestra un error (no tiene en cuenta el mail del propio alumno a modificar)
                if(!$this->existeOtroMailAlumnoIgual($this->getId(), $this->getMail())) {
                    $this->modificarAlumno();
                }
                // Else de que el mail ya existe
                else {
                    $error['mail'] = 'Este mail ya esta registrado en el sistema';
                }
            }
            // Else de que el DNI ya existe
            else {
                $error['numeroDocumento'] = 'Este DNI ya esta registrado en el sistema';
            }
        }
        // Else de que no existe el alumno
        else {
            $error['alumno'] = 'Error, el alumno que usted quiere modificar no existe en el sistema';
        }
        return ($error);
    }

    public function delete($error) {
        $this->setId($_POST['idAlumno']);
        if($this->existeAlumno($this->getId())) {
            if(!$this->alumnoPoseePagos()) {
                $this->eliminarAlumno();
            }
            else
                $error = "No se puede eliminar el alumno porque posee pagos realizados";
        }
        else
            $error = "El alumno a eliminar no existe";
        return ($error);
    }

    // Cuando se va a dar de alta un alumno, se verifica si existe en el sistema un alumno con el mismo DNI

    public function existeIdAlumno($idAlumno) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT * FROM alumno WHERE id = :idAlumno");
        $stmt->bindParam(":idAlumno", $idAlumno, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return (true);
        }
        else {
            return (false);
        }
    }

    // Cuando se va a dar de alta un alumno, se verifica si existe en el sistema un alumno con el mismo DNI

    public function existeDniAlumno($numeroDocumento) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT numeroDocumento FROM alumno WHERE numeroDocumento = :numeroDocumento");
        $stmt->bindParam(":numeroDocumento", $numeroDocumento, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return (true);
        }
        else {
            return (false);
        }
    }

    // Cuando se va a dar de alta un alumno, se verifica si existe en el sistema un alumno con el mismo mail

    public function existeMailAlumno($mail) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT mail FROM alumno WHERE mail = :mail");
        $stmt->bindParam(":mail", $mail, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return (true);
        }
        else {
            return (false);
        }
    }

    // Cuando se va a modificar un alumno , se verifica si existe otro usuario con el DNI ingresado

    public function existeOtroDniAlumnoIgual($idAlumno, $numeroDocumento) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT numeroDocumento FROM alumno WHERE id <> :idAlumno AND numeroDocumento = :numeroDocumento");
        $stmt->bindParam(":idAlumno", $idAlumno, PDO::PARAM_STR);
        $stmt->bindParam(":numeroDocumento", $numeroDocumento, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return (true);
        }
        else {
            return (false);
        }
    }

    // Cuando se va a modificar un alumno , se verifica si existe otro usuario con el mail ingresado

    public function existeOtroMailAlumnoIgual($idAlumno, $mail) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT mail FROM alumno WHERE id <> :idAlumno AND mail = :mail");
        $stmt->bindParam(":idAlumno", $idAlumno, PDO::PARAM_STR);
        $stmt->bindParam(":mail", $mail, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return (true);
        }
        else {
            return (false);
        }
    }

    public function existeAlumno($idAlumno) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT id FROM alumno WHERE id = :idAlumno");
        $stmt->bindParam(":idAlumno", $idAlumno, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return (true);
        }
        else {
            return (false);
        }
    }

    public function obtenerAlumno($idAlumno) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT * FROM alumno WHERE id = :idAlumno");
        $stmt->bindParam(":idAlumno", $idAlumno, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return ($stmt->fetch());
        }
        else {
            return (false);
        }
    }

    public function obtenerIdAlumno($numeroDocumento) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT id FROM alumno WHERE numeroDocumento = :numeroDocumento");
        $stmt->bindParam(":numeroDocumento", $numeroDocumento, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return ($stmt->fetchColumn()); // Lo que hace fetchColumn() es devolver una única columna de la siguiente fila de un conjunto de resultados. Por ejemplos si se tienen 2 filas y se lo usa la primera vez devuelve un dato de la primera fila y si se lo usa otra vez devuelve un dato de la segunda fila. Si se le ingresa un parametro fetchColumn(1) lo que hace es devolver la segunda columna de la siguiente fila. En este caso devuelve la primera columna de la primera fila porque se usa una vez y tenemos una sola fila
        }
        else {
            return (false);
        }
    }

    public function cantidadResponsables($idAlumno) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT COUNT(*)
            FROM alumnoResponsable
            WHERE idAlumno = :idAlumno");
        $stmt->bindParam(":idAlumno", $idAlumno, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        return ($stmt->fetchColumn());
    }

    public function alumnoPoseePagos() {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT * FROM alumno
            INNER JOIN pago on (alumno.id = pago.idAlumno)
            WHERE alumno.id = :idAlumno");
        $stmt->bindParam(":idAlumno", $this->getId(), PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return (true);
        }
        else {
            return (false);
        }
    }

    public function crearAlumno() {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("INSERT INTO alumno (tipoDocumento, numeroDocumento, apellido, nombre, fechaNacimiento, sexo, mail, direccion, fechaIngreso, fechaAlta)
            VALUES (
                :tipoDocumento,
                :numeroDocumento,
                :apellido,
                :nombre,
                :fechaNacimiento,
                :sexo,
                :mail,
                :direccion,
                :fechaIngreso,
                :fechaAlta)");
        $stmt->bindValue(":tipoDocumento", $this->getTipoDocumento(), PDO::PARAM_STR);
        $stmt->bindValue(":numeroDocumento", $this->getNumeroDocumento(), PDO::PARAM_STR);
        $stmt->bindValue(":apellido", $this->getApellido(), PDO::PARAM_STR);
        $stmt->bindValue(":nombre", $this->getNombre(), PDO::PARAM_STR);
        $stmt->bindValue(":fechaNacimiento", $this->getFechaNacimiento(), PDO::PARAM_STR);
        $stmt->bindValue(":sexo", $this->getSexo(), PDO::PARAM_STR);
        $stmt->bindValue(":mail", $this->getMail(), PDO::PARAM_STR);
        $stmt->bindValue(":direccion", $this->getDireccion(), PDO::PARAM_STR);
        $stmt->bindValue(":fechaIngreso", $this->getFechaIngreso(), PDO::PARAM_STR);
        $stmt->bindValue(":fechaAlta", $this->getFechaAlta(), PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
    }

    public function modificarAlumno() {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("UPDATE alumno
            set
                tipoDocumento = :tipoDocumento,
                numeroDocumento = :numeroDocumento,
                apellido = :apellido,
                nombre = :nombre,
                fechaNacimiento = :fechaNacimiento,
                sexo = :sexo,
                mail = :mail,
                direccion = :direccion,
                fechaIngreso = :fechaIngreso
            WHERE id = :idAlumno");
        $stmt->bindValue(":idAlumno", $this->getId(), PDO::PARAM_STR);
        $stmt->bindValue(":tipoDocumento", $this->getTipoDocumento(), PDO::PARAM_STR);
        $stmt->bindValue(":numeroDocumento", $this->getNumeroDocumento(), PDO::PARAM_STR);
        $stmt->bindValue(":apellido", $this->getApellido(), PDO::PARAM_STR);
        $stmt->bindValue(":nombre", $this->getNombre(), PDO::PARAM_STR);
        $stmt->bindValue(":fechaNacimiento", $this->getFechaNacimiento(), PDO::PARAM_STR);
        $stmt->bindValue(":sexo", $this->getSexo(), PDO::PARAM_STR);
        $stmt->bindValue(":mail", $this->getMail(), PDO::PARAM_STR);
        $stmt->bindValue(":direccion", $this->getDireccion(), PDO::PARAM_STR);
        $stmt->bindValue(":fechaIngreso", $this->getFechaIngreso(), PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
    }

    public function eliminarAlumno() {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("DELETE FROM alumno WHERE id = :idAlumno");
        $stmt->bindValue(":idAlumno", $this->getId(), PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
    }

    public function asignarResponsable($idAlumno, $idResponsable) {
        try {
            $conn = $this->pdo->getConnection();
            $stmt = $conn->prepare("INSERT INTO alumnoResponsable (idAlumno, idResponsable)
                VALUES (
                    :idAlumno,
                    :idResponsable)");
            $stmt->bindValue(":idAlumno", $idAlumno, PDO::PARAM_STR);
            $stmt->bindValue(":idResponsable", $idResponsable, PDO::PARAM_STR);
            $stmt->execute();
            $this->pdo->closeConnection($conn); // Cerramos la conexion
            return null; 
        } catch (Exception $e) {
            return "El responsable ya tiene asignado a ese alumno.";
        }
    }

}

?>