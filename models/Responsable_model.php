<?php

require_once 'PDOConnection.php';

class Responsable_model {

    private  $id;
    private  $tipo;
    private  $apellido;
    private  $nombre;
    private  $fechaNacimiento;
    private  $sexo;
    private  $mail;
    private  $telefono;
    private  $direccion;
    private  $idUsuario;
    private  $pdo;

    function __construct() {
        $this->pdo = new PDOConnection();
    }

    public function getId() {
        return ($this->id);
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTipo() {
        return ($this->tipo);
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
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

    public function getTelefono() {
        return ($this->telefono);
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function getDireccion() {
        return ($this->direccion);
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function getIdUsuario() {
        return ($this->idUsuario);
    }

    public function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    public function setDatos($args, $error) {
        // TIPO DOC
        if(isset($args['tipoResponsable']))
            $this->setTipo($args['tipoResponsable']);
        else
            $error['tipoResponsable'] = "Este campo no debe estar vacio";
        // APELLIDO
        if(isset($args['apellidoResponsable']))
            $this->setApellido($args['apellidoResponsable']);
        else
            $error['apellidoResponsable'] = "Este campo no debe estar vacio";
        // NOMBRE
        if(isset($args['nombreResponsable']))
            $this->setNombre($args['nombreResponsable']);
        else
            $error['nombreResponsable'] = "Este campo no debe estar vacio";
        // FECHA DE NACIMIENTO
        if(isset($args['fechaNacimientoResponsable']))
            $this->setFechaNacimiento($args['fechaNacimientoResponsable']);
        else
            $error['fechaNacimientoResponsable'] = "Este campo no debe estar vacio";
        // SEXO
        if(isset($args['sexoResponsable']))
            $this->setSexo($args['sexoResponsable']);
        else
            $error['sexoResponsable'] = "Este campo no debe estar vacio";
        // MAIL
        if(isset($args['mailResponsable']))
            $this->setMail($args['mailResponsable']);
        else
            $error['mailResponsable'] = "Este campo no debe estar vacio";
        // DIRECCION
        if(isset($args['direccionResponsable']))
            $this->setDireccion($args['direccionResponsable']);
        else
            $error['direccionResponsable'] = "Este campo no debe estar vacio";
        // TELEFONO
        if(isset($args['telefonoResponsable']))
            $this->setTelefono($args['telefonoResponsable']);
        else
            $error['telefonoResponsable'] = "Este campo no debe estar vacio";
        return ($error);
    }

    // VALIDADORES
    
    public function esValidoTipoResponsable($tipo, $error) {
        if($tipo == "tutor" or $tipo == "padre" or $tipo == "madre")
            return ($error);
        else
            $error['tipoResponsable'] = "Este campo no cumple el formato solicitado";
        return ($error);
    }

    public function esValidoApellido($apellido, $error) {
        if(preg_match("/^[a-z ,.'-\s]{2,20}+$/i", $apellido))
            return ($error);
        else
            $error['apellidoResponsable'] = "Este campo no cumple el formato solicitado";
        return ($error);
    }

    public function esValidoNombre($nombre, $error) {
        if(preg_match("/^[a-z ,.'-\s]{2,20}+$/i", $nombre))
            return ($error);
        else
            $error['nombreResponsable'] = "Este campo no cumple el formato solicitado";
        return ($error);
    }

    public function esValidoFechaNacimiento($fechaNacimiento, $error) {
        if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$fechaNacimiento))
            return ($error);
        else
            $error['fechaNacimientoResponsable'] = "Este campo no cumple el formato solicitado";
    }

    public function esValidoSexo($sexo, $error) {
        if($sexo == "M" or $sexo == "F")
            return ($error);
        else
            $error['sexoResponsable'] = "Este campo no cumple el formato solicitado";
        return ($error);
    }

    public function esValidoMail($mail, $error) {
        if(filter_var($mail, FILTER_VALIDATE_EMAIL))
            return ($error);
        else
            $error['mailResponsable'] = "Este campo no cumple el formato solicitado";
        return ($error);
    }

    public function esValidoTelefono($telefono, $error) {
        if(preg_match('/^[0-9]{6,20}$/', $telefono))
            return ($error);
        else
            $error['telefonoResponsable'] = "Este campo no cumple el formato solicitado";
        return ($error);
    }

    public function esValidoDireccion($direccion, $error) {
        if(preg_match('/^[a-z0-9- ]{3,50}+$/i', $direccion))
            return ($error);
        else
            $error['direccionResponsable'] = "Este campo no cumple el formato solicitado";
        return ($error);
    }

    public function esValido($error) {
        $error = $this->esValidoTipoResponsable($this->getTipo(), $error);
        $error = $this->esValidoApellido($this->getApellido(), $error);
        $error = $this->esValidoNombre($this->getNombre(), $error);
        $error = $this->esValidoFechaNacimiento($this->getFechaNacimiento(), $error);
        $error = $this->esValidoSexo($this->getSexo(), $error);
        $error = $this->esValidoMail($this->getMail(), $error);
        $error = $this->esValidoTelefono($this->getTelefono(), $error);
        $error = $this->esValidoDireccion($this->getDireccion(), $error);
        return ($error);
    }

    public function save() {

    }

    public function desenlazarCuenta($idResponsable) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("UPDATE responsable
            set
                idUsuario = NULL
            WHERE id = :idResponsable");
        $stmt->bindParam(":idResponsable", $idResponsable, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
    }

    public function existeMailResponsable($mail) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT mail FROM responsable WHERE mail = :mail");
        $stmt->bindParam(":mail", $mail, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexionn
        if($stmt->rowCount() > 0) {
            return (true);
        }
        else {
            return (false);
        }
    }

    public function existeOtroMailResponsableIgual($idResponsable, $mail) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT mail FROM responsable WHERE id <> :idResponsable AND mail = :mail");
        $stmt->bindParam(":idResponsable", $idResponsable, PDO::PARAM_STR);
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

    public function existeResponsable($idResponsable) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT id FROM responsable WHERE id = :idResponsable");
        $stmt->bindParam(":idResponsable", $idResponsable, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return (true);
        }
        else {
            return (false);
        }
    }

    public function obtenerResponsable($idResponsable) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT * FROM responsable WHERE id = :idResponsable");
        $stmt->bindParam(":idResponsable", $idResponsable, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return ($stmt->fetch());
        }
        else {
            return (false);
        }
    }

    public function obtenerResponsables() {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT nombre, apellido, mail FROM responsable");
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return ($stmt->fetchAll());
        }
        else {
            return (false);
        }
    }

    public function obtenerIdResponsable($mail) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT id FROM responsable WHERE mail = :mail");
        $stmt->bindParam(":mail", $mail, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return ($stmt->fetchColumn()); // Lo que hace fetchColumn() es devolver una única columna de la siguiente fila de un conjunto de resultados. Por ejemplos si se tienen 2 filas y se lo usa la primera vez devuelve un dato de la primera fila y si se lo usa otra vez devuelve un dato de la segunda fila. Si se le ingresa un parametro fetchColumn(1) lo que hace es devolver la segunda columna de la siguiente fila. En este caso devuelve la primera columna de la primera fila porque se usa una vez y tenemos una sola fila
        }
        else {
            return (false);
        }
    }

    function obtenerIdUsuarioResponsable($idResponsable) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT idUsuario FROM responsable WHERE id = :idResponsable");
        $stmt->bindParam(":idResponsable", $idResponsable, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return ($stmt->fetchColumn());
        }
        else {
            return (false);
        }
    }

    public function obtenerAlumnosAsignados($idResponsable) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT idAlumno
            FROM alumnoResponsable
            WHERE idResponsable = :idResponsable");
        $stmt->bindParam(":idResponsable", $idResponsable, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        return ($stmt->fetchAll());
    }

    public function asignarCuenta($mail, $idUsuario) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("UPDATE responsable
            set
                idUsuario = :idUsuario
            WHERE mail = :mail");
        $stmt->bindValue(":mail", $mail, PDO::PARAM_STR);
        $stmt->bindValue(":idUsuario", $idUsuario, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
    }

    public function actualizarMailCuentaUsuarioResponsable($idUsuarioResponsable, $mail) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("UPDATE usuario
            set
                mail = :mail
            WHERE id = :idUsuarioResponsable");
        $stmt->bindValue(":idUsuarioResponsable", $idUsuarioResponsable, PDO::PARAM_STR);
        $stmt->bindValue(":mail", $mail, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
    }

    public function crearResponsable() {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("INSERT INTO responsable (tipo, apellido, nombre, fechaNacimiento, sexo, mail, telefono, direccion)
            VALUES (
                :tipo,
                :apellido,
                :nombre,
                :fechaNacimiento,
                :sexo,
                :mail,
                :telefono,
                :direccion)");
        $stmt->bindValue(":tipo", $this->getTipo(), PDO::PARAM_STR);
        $stmt->bindValue(":apellido", $this->getApellido(), PDO::PARAM_STR);
        $stmt->bindValue(":nombre", $this->getNombre(), PDO::PARAM_STR);
        $stmt->bindValue(":fechaNacimiento", $this->getFechaNacimiento(), PDO::PARAM_STR);
        $stmt->bindValue(":sexo", $this->getSexo(), PDO::PARAM_STR);
        $stmt->bindValue(":mail", $this->getMail(), PDO::PARAM_STR);
        $stmt->bindValue(":telefono", $this->getTelefono(), PDO::PARAM_STR);
        $stmt->bindValue(":direccion", $this->getDireccion(), PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
    }

    public function modificarResponsable($idResponsable, $responsable) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("UPDATE responsable
            set
                tipo = :tipo,
                apellido = :apellido,
                nombre = :nombre,
                fechaNacimiento = :fechaNacimiento,
                sexo = :sexo,
                mail = :mail,
                telefono = :telefono,
                direccion = :direccion
            WHERE id = :idResponsable");
        $stmt->bindValue(":idResponsable", $idResponsable, PDO::PARAM_STR);
        $stmt->bindValue(":tipo", $responsable['tipo'], PDO::PARAM_STR);
        $stmt->bindValue(":apellido", $responsable['apellido'], PDO::PARAM_STR);
        $stmt->bindValue(":nombre", $responsable['nombre'], PDO::PARAM_STR);
        $stmt->bindValue(":fechaNacimiento", $responsable['fechaNacimiento'], PDO::PARAM_STR);
        $stmt->bindValue(":sexo", $responsable['sexo'], PDO::PARAM_STR);
        $stmt->bindValue(":mail", $responsable['mail'], PDO::PARAM_STR);
        $stmt->bindValue(":telefono", $responsable['telefono'], PDO::PARAM_STR);
        $stmt->bindValue(":direccion", $responsable['direccion'], PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
    }

    public function eliminarResponsable($idResponsable) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("DELETE FROM responsable WHERE id = :idResponsable");
        $stmt->bindValue(":idResponsable", $idResponsable, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
    }

}

?>