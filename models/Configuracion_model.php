<?php

require_once 'PDOConnection.php';

class Configuracion_model {

    private  $titulo;
    private  $descripcion;
    private  $mail;
    private  $elementos;
    private  $habilitado;
    private  $mensaje;

    function __construct() {
        $this->pdo = new PDOConnection();
    }

    public function getTitulo() {
        return ($this->titulo);
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function getDescripcion() {
        return ($this->descripcion);
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getMail() {
        return ($this->mail);
    }

    public function setMail($mail) {
        $this->mail = $mail;
    }

    public function getElementos() {
        return ($this->elementos);
    }

    public function setElementos($elementos) {
        $this->elementos = $elementos;
    }

    public function getHabilitado() {
        return ($this->habilitado);
    }

    public function setHabilitado($habilitado) {
        $this->habilitado = $habilitado;
    }

    public function getMensaje() {
        return ($this->mensaje);
    }

    public function setMensaje($mensaje) {
        $this->mensaje = $mensaje;
    }

    public function setDatos($args, $error) {
        //TITULO
        if(isset($args['titulo']))
            $this->setTitulo($args['titulo']);
        else
            $error['titulo'] = "Este campo no debe estar vacio";
        //DESCRIPCION
        if(isset($args['descripcion']))
            $this->setDescripcion($args['descripcion']);
        else
            $error['descripcion'] = "Este campo no debe estar vacio";
        //MAIL
        if(isset($args['mail']))
            $this->setMail($args['mail']);
        else
            $error['mail'] = "Este campo no debe estar vacio";
        //ELEMENTOS
        if(isset($args['elementos']))
            $this->setElementos($args['elementos']);
        else
            $error['elementos'] = "Este campo no debe estar vacio";
        //HABILITADO
        if(isset($args['habilitado']))
            $this->setHabilitado($args['habilitado']);
        else
            $error['habilitado'] = "Este campo no debe estar vacio";
        //MENSAJE
        if(isset($args['mensaje']))
            $this->setMensaje($args['mensaje']);
        else
            $error['mensaje'] = "Este campo no debe estar vacio";
        return ($error);
    }

    public function esValidoTitulo($titulo, $error) {
        if(!preg_match('/^[a-z\d_.,\s]{2,30}$/i', $titulo))
            $error['titulo'] = "Este campo no cumple el formato solicitado";
        return ($error);
    }

    public function esValidoDescripcion($descripcion, $error) {
        if(!preg_match('/^[a-z\d_.,\s]{2,255}$/i', $descripcion))
            $error['descripcion'] = "Este campo no cumple con el tamaño esperado (Min: 2 Max: 255)";
        return ($error);
    }

    public function esValidoMail($mail, $error) {
        if(!filter_var($mail, FILTER_VALIDATE_EMAIL))
            $error['mail'] = "Este campo no cumple el formato solicitado";
        return ($error);
    }

    public function esValidoElementos($elementos, $error) {
        if(!preg_match('/^\d*$/', $elementos))
            $error['elementos'] = "Este campo no cumple el formato solicitado";
        return ($error);
    }

    public function esValidoHabilitado($habilitado, $error) {
        if(!preg_match('/[0,1]/', $habilitado))
            $error['habilitado'] = "Este campo no cumple el formato solicitado";
        return ($error);
    }

    public function esValidoMensaje($mensaje, $error) {
        if(!preg_match('/^[a-z\d_.,\s]{2,255}$/i', $mensaje))
            $error['mensaje'] = "Este campo no cumple con el tamaño esperado (Min: 2 Max: 255)";
        return ($error);
    }

    public function esValido($error) {
        $error = $this->esValidoTitulo($this->getTitulo(), $error);
        $error = $this->esValidoDescripcion($this->getDescripcion(), $error);
        $error = $this->esValidoMail($this->getMail(), $error);
        $error = $this->esValidoElementos($this->getElementos(), $error);
        $error = $this->esValidoHabilitado($this->getHabilitado(), $error);
        $error = $this->esValidoMensaje($this->getMensaje(), $error);
        return ($error);
    }

    public function save() {
        $this->cargarInfoCooperadora();
        return "Configuracion guardada con exito";
    }

    public function traerConfiguracion() {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("SELECT * FROM configuracion");
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
        if($stmt->rowCount() > 0) {
            return ($stmt->fetch());
        }
        else {
            return (false);
        }
    }

    public function cargarInfoCooperadora() {
        $conn = $this->pdo->getConnection();
        $stmt = $conn->prepare("UPDATE configuracion
            set
                titulo = :titulo,
                descripcion = :descripcion,
                mail = :mail,
                elementos = :elementos,
                habilitado = :habilitado,
                mensaje = :mensaje");
        $stmt->bindValue(":titulo", $this->getTitulo(), PDO::PARAM_STR);
        $stmt->bindValue(":descripcion", $this->getDescripcion(), PDO::PARAM_STR);
        $stmt->bindValue(":mail", $this->getMail(), PDO::PARAM_STR);
        $stmt->bindValue(":elementos", $this->getElementos(), PDO::PARAM_STR);
        $stmt->bindValue(":habilitado", $this->getHabilitado(), PDO::PARAM_STR);
        $stmt->bindValue(":mensaje", $this->getMensaje(), PDO::PARAM_STR);
        $stmt->execute();
        $this->pdo->closeConnection($conn); // Cerramos la conexion
    }

}

?>