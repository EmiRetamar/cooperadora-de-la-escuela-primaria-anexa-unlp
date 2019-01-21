<?php

require_once 'PDOConnection.php';
require_once 'Alumno_model.php';

class Cuota_model {

    private $anio;
    private $mes;
    private $numero;
    private $monto;
    private $tipo;
    private $comisionCobrador;
    private $fechaAlta;
    private $pdo;

    function __construct() {
        $this->pdo = new PDOConnection();
    }

    public function getAnio() {
        return ($this->anio);
    }

    public function setAnio($anio) {
        $this->anio = $anio;
    }

    public function getMes() {
        return ($this->mes);
    }

    public function setMes($mes) {
        $this->mes = $mes;
    }

    public function getNumero() {
        return ($this->numero);
    }

    public function setNumero($numero) {
        $this->numero = $numero;
    }

    public function getMonto() {
        return ($this->monto);
    }

    public function setMonto($monto) {
        $this->monto = $monto;
    }

    public function getTipo() {
        return ($this->tipo);
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function getComisionCobrador() {
        return ($this->comisionCobrador);
    }

    public function setComisionCobrador($comisionCobrador) {
        $this->comisionCobrador = $comisionCobrador;
    }

    public function getFechaAlta() {
        return ($this->fechaAlta);
    }

    public function setFechaAlta($fechaAlta) {
        $this->fechaAlta = $fechaAlta;
    }

    public function setDatos($args, $error) {
        //NUMERO
        if(isset($args['numero']))
            $this->setNumero($args['numero']);
        else
            $error['numero']="Este campo no debe estar vacio.";
        //TIPO
        if(isset($args['tipo']))
            $this->setTipo($args['tipo']);
        else
            $error['tipo']="Este campo no debe estar vacio.";
        //MONTO
        if(isset($args['monto']))
            $this->setMonto($args['monto']);
        else
            $error['monto']="Este campo no debe estar vacio.";
        //MES
        if(isset($args['mes']))
            $this->setMes($args['mes']);
        else
            $error['mes']="Este campo no debe estar vacio.";
        //AÑO
        if(isset($args['anio']))
            $this->setAnio($args['anio']);
        else
            $error['anio']="Este campo no debe estar vacio.";        
        //PORCENTAJE
        if(isset($args['comisionCobrador']))
            $this->setComisionCobrador($args['comisionCobrador']);
        else
            $error['comisionCobrador']="Este campo no debe estar vacio.";

        return $error;
    }

    public function esValidoNumero($numero, $error) {
        if($numero >= 1 and $numero <= 9999)
            return $error; // Error seria un arreglo vacio, por lo tanto queda validado OK
        else
            $error['numero']="Este campo no cumple el formato solicitado";
        return $error;
    }

    public function esValidoTipo($tipo, $error) {
        if($tipo == 1 or $tipo == 0)
            return $error; // Error seria un arreglo vacio, por lo tanto queda validado OK
        else
            $error['tipo']="Este campo no cumple el formato solicitado";
        return $error;
    }

    public function esValidoMonto($monto, $error) {
        if($monto >= 1 and $monto <= 9999)
            return $error; // Error seria un arreglo vacio, por lo tanto queda validado OK
        else
            $error['monto']="Este campo no cumple el formato solicitado";
        return $error;
    }

    public function esValidoMes($mes, $error) {
        if($mes >= 01 and $mes <= 12)
            return $error; // Error seria un arreglo vacio, por lo tanto queda validado OK
        else
            $error['mes']="Este campo no cumple el formato solicitado";
        return $error;
    }

    public function esValidoAnio($anio, $error) {
        if($anio >= 1990 and $anio <= 2050)
            return $error; // Error seria un arreglo vacio, por lo tanto queda validado OK
        else
            $error['anio']="Este campo no cumple el formato solicitado";
        return $error;
    }

    public function esValidoComision($comision, $error) {
        if($comision >= 0 and $comision <= 100)
            return $error; // Error seria un arreglo vacio, por lo tanto queda validado OK
        else
            $error['comisionCobrador']="Este campo no cumple el formato solicitado";
        return $error;
    }

    public function esValidoId($id, $error) {
        if(preg_match('/[[:digit:]]/', $id))
            return $error; // Error seria un arreglo vacio, por lo tanto queda validado OK
        else
            $error['id']="Hubo una falla al identificar la cuota. Intente nuevamente.";
        return $error;
    }

    public function esValido($error) {
        $error = $this->esValidoNumero($this->getNumero(), $error);
        $error = $this->esValidoTipo($this->getTipo(), $error);
        $error = $this->esValidoMonto($this->getMonto(), $error);
        $error = $this->esValidoMes($this->getMes(), $error);
        $error = $this->esValidoAnio($this->getAnio(), $error);
        $error = $this->esValidoComision($this->getComisionCobrador(), $error);
        return $error;
    }

    public function esValidoModificar($id, $error) {
        $error = $this->esValidoId($id, $error);
        $error = $this->esValidoNumero($this->getNumero(), $error);
        $error = $this->esValidoTipo($this->getTipo(), $error);
        $error = $this->esValidoMonto($this->getMonto(), $error);
        $error = $this->esValidoMes($this->getMes(), $error);
        $error = $this->esValidoAnio($this->getAnio(), $error);
        $error = $this->esValidoComision($this->getComisionCobrador(), $error);
        return $error;
    }

    public function save(){
        $this->crearCuota();
        return "Cuota agregada con exito";
    }

    public function edit($datos){
        $idCuota = $datos['post']['idCuota'];
        if ($this->existeCuota($idCuota)) {
            if ($this->modificarCuota($idCuota)) {
                    return $datos;
                }
                else {
                    $datos['error'] = "La cuota no puede modificarse porque ya se encuentra pagada y/o becada por algún alumno.";
                    return $datos;
                }
        }
        else{
            $datos['error'] = "La cuota que desea modificar no se encuentra.";
            return $datos;
        }
    }

    public function existeCuota($idCuota) {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT id FROM cuota WHERE id = :idCuota");
        $stmt->bindParam(":idCuota", $idCuota, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return (true);
        }
        else {
            return (false);
        }
    }

    public function obtenerCuota($idCuota) {
        $conn = $this->pdo->getConnection();
        $stmt= $conn->prepare("SELECT * FROM cuota WHERE id = :idCuota");
        $stmt->bindValue(":idCuota", $idCuota, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return ($stmt->fetch());
        }
        else {
            return (false);
        }
    }

    public function crearCuota() {
        $conn = $this->pdo->getConnection();
        $hoy = date("Y-m-d");
        $stmt = $conn->prepare("INSERT INTO cuota (anio, mes, numero, monto, tipo, comisionCobrador, fechaAlta)
            VALUES (
                :anio,
                :mes,
                :numero,
                :monto,
                :tipo,
                :comisionCobrador,
                :fechaAlta)");
        $stmt->bindValue(":anio", $this->getAnio(), PDO::PARAM_STR);
        $stmt->bindValue(":mes", $this->getMes(), PDO::PARAM_STR);
        $stmt->bindValue(":numero", $this->getNumero(), PDO::PARAM_STR);
        $stmt->bindValue(":monto", $this->getMonto(), PDO::PARAM_STR);
        $stmt->bindValue(":tipo", $this->getTipo(), PDO::PARAM_STR);
        $stmt->bindValue(":comisionCobrador", $this->getComisionCobrador(), PDO::PARAM_STR);
        $stmt->bindValue(":fechaAlta", $hoy, PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
    }

    public function cuotaPuedeModificarse($idCuota, $conn) {
        $stmt = $conn->prepare("SELECT * 
            FROM pago
            WHERE pago.idCuota = :idCuota
            ");
        $stmt->bindValue(":idCuota", $idCuota, PDO::PARAM_STR);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
            return (false);
        }
        else
            return (true);
     }

    public function modificarCuota($idCuota) {
        $conn = $this->pdo->getConnection();
        if ($this->cuotaPuedeModificarse($idCuota, $conn)) {
            $stmt = $conn->prepare("UPDATE cuota
                set
                    anio = :anio,
                    mes = :mes,
                    numero = :numero,
                    monto = :monto,
                    tipo = :tipo,
                    comisionCobrador = :comisionCobrador
                WHERE id = :idCuota");
            $stmt->bindValue(":idCuota", $idCuota, PDO::PARAM_STR);
            $stmt->bindValue(":anio", $this->getAnio(), PDO::PARAM_STR);
            $stmt->bindValue(":mes", $this->getMes(), PDO::PARAM_STR);
            $stmt->bindValue(":numero", $this->getNumero(), PDO::PARAM_STR);
            $stmt->bindValue(":monto", $this->getMonto(), PDO::PARAM_STR);
            $stmt->bindValue(":tipo", $this->getTipo(), PDO::PARAM_STR);
            $stmt->bindValue(":comisionCobrador", $this->getComisionCobrador(), PDO::PARAM_STR);
            $stmt->execute();
            $this->pdo->closeConnection($conn); // Cerramos la conexion
            return true;
        }
        else{
            $this->pdo->closeConnection($conn); // Cerramos la conexion
            return false;
        }
    }


    public function eliminarCuota($idCuota) {
        $conn = $this->pdo->getConnection();
        if($this->cuotaPuedeModificarse($idCuota, $conn)){
            $stmt = $conn->prepare("DELETE FROM cuota WHERE id = :idCuota");
            $stmt->bindValue(":idCuota", $idCuota, PDO::PARAM_STR);
            $stmt->execute();
            $this->pdo->closeConnection($conn); // Cerramos la conexion
            return true;
        }
        else {
            $this->pdo->closeConnection($conn); // Cerramos la conexion
            return false;
            }
    }
    
    public function pagarCuota($cuota) {
        $conn = $this->pdo->getConnection();
        $alumno = new Alumno_model();
        if($alumno->existeAlumno($cuota['idAlumno'])) {
            $stmt= $conn->prepare("INSERT INTO pago (idAlumno, idCuota, becado, fechaAlta)
                VALUES (
                    :idAlumno,
                    :idCuota,
                    :becado,
                    :fechaAlta)");
            $stmt->bindValue(":idAlumno", $cuota['idAlumno'], PDO::PARAM_STR);
            $stmt->bindValue(":idCuota", $cuota['idCuota'], PDO::PARAM_STR);
            $stmt->bindValue(":becado", $cuota['becado'], PDO::PARAM_STR);
            $stmt->bindValue(":fechaAlta", $cuota['fechaAlta'], PDO::PARAM_STR);
            $stmt->execute();
            $this->pdo->closeConnection($conn); // Cerramos la conexion
        }
        else {
            $this->pdo->closeConnection($conn); // Cerramos la conexion
            return (false);
        }
    }

}

?>