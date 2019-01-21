<?php

require_once 'PDOConnection.php';

class Listado_model {

    private $pdo;

    function __construct() {
        $this->pdo = new PDOConnection();
    }

    public function listarUsuarios() {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT * FROM usuario");
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) { 
            return ($stmt);
        }
        else {
            return (false);
        }
    }
    
    public function listarAlumnos() {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT * FROM alumno");
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return ($stmt);
        }
        else {
            return (false);
        }
    }

    public function listarResponsables() {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT responsable.*, usuario.username FROM responsable left join usuario on (responsable.idUsuario = usuario.id)");
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return ($stmt);
        }
        else {
            return (false);
        }
    }

    public function listarCuotas() {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT * FROM cuota");
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return ($stmt);
        }
        else {
            return (false);
        }
    }

    public function responsablesAsignados($idAlumno) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT *
            FROM responsable INNER JOIN alumnoResponsable
            ON (responsable.id = alumnoResponsable.idResponsable)
            WHERE alumnoResponsable.idAlumno = :idAlumno");
        $stmt->bindValue(":idAlumno", $idAlumno, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return ($stmt);
        }
        else {
            return (false);
        }
    }

    public function alumnosAsignados($idResponsable) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT *
            FROM alumno INNER JOIN alumnoResponsable
            ON (alumno.id = alumnoResponsable.idAlumno)
            WHERE alumnoResponsable.idResponsable = :idResponsable");
        $stmt->bindValue(":idResponsable", $idResponsable, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return ($stmt);
        }
        else {
            return (false);
        }
    }

    public function listarCuotasPagasAlumnos($aluID) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare(
            "SELECT *, pago.becado as becado
                FROM cuota
                INNER JOIN pago on (cuota.id = pago.idCuota)
                WHERE pago.idAlumno = :idAlumno AND cuota.id IN (
                    SELECT pago.idCuota
                        FROM pago
                        where pago.idAlumno = :idAlumno)
                ORDER BY cuota.anio DESC, cuota.mes DESC");
        $stmt->bindValue(":idAlumno", $aluID, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return ($stmt);
        }
        else {
            return (false);
        }
    }

    public function listarCuotasImpagasAlumnos($aluID) {
        $conn = $this->pdo->getConnection();
        $month = date("m");
        $year = date("Y");
        $stmt = $conn->prepare(
            "SELECT * 
            FROM cuota
            WHERE cuota.mes >= :month 
            AND cuota.anio >= :year
            AND cuota.id NOT IN(SELECT pago.idCuota
                                FROM pago
                                WHERE pago.idAlumno = :idAlumno)
            ORDER BY cuota.anio ASC, cuota.mes ASC");

        $stmt->bindValue(":idAlumno", $aluID, PDO::PARAM_STR);
        $stmt->bindValue(":month", $month, PDO::PARAM_STR);
        $stmt->bindValue(":year", $year, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion        
        if($stmt->rowCount() > 0) {
            return ($stmt);
        }
        else {
            return (false);
        }
    }

    public function listarCuotasVencidasAlumnos($aluID) {  
        $conn = $this->pdo->getConnection();
        $month = date("m");
        $year = date("Y");
        $stmt = $conn->prepare(
            "SELECT * 
            FROM cuota
            WHERE cuota.mes < :month
            AND cuota.anio <= :year
            AND cuota.id NOT IN(SELECT pago.idCuota
                                FROM pago
                                WHERE pago.idAlumno = :idAlumno)
            ORDER BY cuota.anio ASC, cuota.mes ASC");

        $stmt->bindValue(":idAlumno", $aluID, PDO::PARAM_STR);
        $stmt->bindValue(":month", $month, PDO::PARAM_STR);
        $stmt->bindValue(":year", $year, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion        
        if($stmt->rowCount() > 0) {
            return ($stmt);
        }
        else {
            return (false);
        }
    }

    public function listarAlumnosConMatriculaPaga($idUsuario, $rolUsuario) {
        $conn = $this->pdo->getConnection();
        if($rolUsuario == 'consulta') {
            $stmt = $conn->prepare(
                "SELECT 
                    alumno.apellido, alumno.nombre, cuota.monto, cuota.mes, cuota.anio
                FROM 
                    responsable 
                    INNER JOIN alumnoResponsable 
                    INNER JOIN alumno 
                    INNER JOIN pago
                    INNER JOIN cuota
                WHERE 
                    responsable.id = :idUsuario
                    AND cuota.tipo = 1
                    AND responsable.id = alumnoResponsable.idResponsable 
                    AND alumnoResponsable.idAlumno = alumno.id 
                    AND pago.idAlumno = alumno.id
                    AND pago.idCuota = cuota.id
                ORDER BY cuota.anio ASC, cuota.mes ASC");
            $stmt->bindValue(":idUsuario", $idUsuario, PDO::PARAM_STR);
            $stmt->execute();
            $this->pdo->closeConnection($conn); // Cerramos la conexion            
            if($stmt->rowCount() > 0) {
                return ($stmt);
            }
            else {
                return (false);
            }

        }
        else {
            $stmt = $conn->prepare("SELECT * FROM alumno");
        }
    }

    public function listarMatriculasPagas() {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare(
            "SELECT DISTINCT alumno.numeroDocumento, alumno.nombre, alumno.apellido, cuota.numero, cuota.mes, cuota.anio, cuota.monto, pago.becado
            FROM alumno
            INNER JOIN pago ON (alumno.id = pago.idAlumno)
            INNER JOIN cuota ON (pago.idCuota = cuota.id)
            WHERE cuota.tipo = 1
            ORDER BY cuota.mes ASC, cuota.anio ASC");
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return ($stmt);
        }
        else {
            return (false);
        }
    }

    public function listarMatriculasPagasResponsable($idUsuario) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare(
            "SELECT DISTINCT alumno.numeroDocumento, alumno.nombre, alumno.apellido, cuota.numero, cuota.mes, cuota.anio, cuota.monto, pago.becado
            FROM responsable
            INNER JOIN alumnoResponsable ON (responsable.id = alumnoResponsable.idResponsable)
            INNER JOIN alumno ON (alumnoResponsable.idAlumno = alumno.id)
            INNER JOIN pago ON (alumno.id = pago.idAlumno)
            INNER JOIN cuota ON (pago.idCuota = cuota.id)
            WHERE responsable.idUsuario = :idUsuario AND cuota.tipo = 1
            ORDER BY cuota.mes ASC, cuota.anio ASC");
        $stmt->bindValue(":idUsuario", $idUsuario, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return ($stmt);
        }
        else {
            return (false);
        }
    }

    public function listarCuotasPagas() {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare(
            "SELECT DISTINCT alumno.numeroDocumento, alumno.nombre, alumno.apellido, cuota.tipo, cuota.numero, cuota.mes, cuota.anio, cuota.monto, pago.becado
            FROM alumno
            INNER JOIN pago ON (alumno.id = pago.idAlumno)
            INNER JOIN cuota ON (pago.idCuota = cuota.id)
            WHERE cuota.tipo = 0
            ORDER BY cuota.mes ASC, cuota.anio ASC");
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return ($stmt);
        }
        else {
            return (false);
        }
    }

    public function listarCuotasPagasResponsable($idUsuario) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare(
            "SELECT DISTINCT alumno.numeroDocumento, alumno.nombre, alumno.apellido, cuota.numero, cuota.mes, cuota.anio, cuota.monto, pago.becado
            FROM responsable
            INNER JOIN alumnoResponsable ON (responsable.id = alumnoResponsable.idResponsable)
            INNER JOIN alumno ON (alumnoResponsable.idAlumno = alumno.id)
            INNER JOIN pago ON (alumno.id = pago.idAlumno)
            INNER JOIN cuota ON (pago.idCuota = cuota.id)
            WHERE responsable.idUsuario = :idUsuario AND cuota.tipo = 0
            ORDER BY cuota.mes ASC, cuota.anio ASC");
        $stmt->bindValue(":idUsuario", $idUsuario, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return ($stmt);
        }
        else {
            return (false);
        }
    }

    public function listarCuotasVencidas() {
        $conn = $this->pdo->getConnection();
        $month = date("m");
        $year = date("Y");
        $stmt = $conn->prepare(
            "SELECT DISTINCT alumno.numeroDocumento, alumno.nombre, alumno.apellido, cuota.numero, cuota.mes, cuota.anio
            FROM alumno
            INNER JOIN cuota
            WHERE cuota.tipo = 0 AND cuota.id NOT IN
                (SELECT pago.idCuota
                FROM pago
                WHERE pago.idAlumno = alumno.id)
            AND (cuota.anio < :year
            OR (cuota.mes < :month AND cuota.anio = :year))
            ORDER BY cuota.mes ASC, cuota.anio ASC");
        $stmt->bindValue(":month", $month, PDO::PARAM_STR);
        $stmt->bindValue(":year", $year, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return ($stmt);
        }
        else {
            return (false);
        }
    }

    public function listarCuotasVencidasResponsable($idUsuario) {
        $conn = $this->pdo->getConnection();
        $month = date("m");
        $year = date("Y");
        $stmt = $conn->prepare(
            "SELECT DISTINCT alumno.numeroDocumento, alumno.nombre, alumno.apellido, cuota.numero, cuota.mes, cuota.anio
            FROM responsable
            INNER JOIN alumnoResponsable ON (responsable.id = alumnoResponsable.idResponsable)
            INNER JOIN alumno ON (alumno.id = alumnoResponsable.idAlumno)
            INNER JOIN cuota 
            WHERE cuota.tipo = 0 AND :idUsuario = responsable.idUsuario AND cuota.id NOT IN
                (SELECT pago.idCuota
                FROM pago
                WHERE pago.idAlumno = alumno.id)
            AND (cuota.anio < :year
            OR (cuota.mes < :month AND cuota.anio = :year))
            ORDER BY cuota.mes ASC, cuota.anio ASC");
        $stmt->bindValue(":idUsuario", $idUsuario, PDO::PARAM_STR);
        $stmt->bindValue(":month", $month, PDO::PARAM_STR);
        $stmt->bindValue(":year", $year, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return ($stmt);
        }
        else {
            return (false);
        }
    }

    public function listarCuotasPagasPorMesAnio($mes, $anio) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare(
            "SELECT DISTINCT alumno.numeroDocumento, alumno.nombre, alumno.apellido, cuota.numero, cuota.mes, cuota.anio, cuota.monto, pago.becado
            FROM alumno
            INNER JOIN pago ON (alumno.id = pago.idAlumno)
            INNER JOIN cuota ON (pago.idCuota = cuota.id)
            WHERE cuota.tipo = 0
            AND cuota.mes = :mes
            AND cuota.anio = :anio
            ORDER BY cuota.mes ASC, cuota.anio ASC");
        $stmt->bindValue(":mes", $mes, PDO::PARAM_STR);
        $stmt->bindValue(":anio", $anio, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return ($stmt);
        }
        else {
            return (false);
        }
    }

}

?>