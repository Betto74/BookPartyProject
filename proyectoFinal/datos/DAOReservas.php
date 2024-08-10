<?php
// Importa la clase conexión y el modelo para usarlos
require_once 'conexion.php'; 
require_once '../modelos/Reservas.php'; 

class DAOReservas
{
    private $conexion; 
    
    /**
     * Permite obtener la conexión a la BD
     */
    private function conectar() {
        try {
            $this->conexion = Conexion::conectar(); 
        } catch (Exception $e) {
            die($e->getMessage()); // Si la conexion no se establece se cortara el flujo enviando un mensaje con el error
        }
    }
    
    public function obtenerById($id) {
        try
        {
            $this->conectar();
            
            $lista = array();
            $sentenciaSQL = $this->conexion->prepare(
                "select * from reservas where idreserva = ?");
            
            $sentenciaSQL->execute();
            
            $resultado = $sentenciaSQL->fetchAll(PDO::FETCH_OBJ);

            if( $resultado )return true;
            return false;
        }
        catch(PDOException $e){
            
            return false;
        }finally{
            Conexion::desconectar();
        }
    }

    public function obtenerReservas($id) {
        try
        {
            $this->conectar();
            
            $lista = array();
            $sentenciaSQL = $this->conexion->prepare(
                "WITH dias_ocupados AS (
                    SELECT
                        idSalon,
                        generate_series(
                            DATE_TRUNC('day', fechaInicio),
                            DATE_TRUNC('day', fechaFin),
                            '1 day'::interval
                        )::date AS fecha
                    FROM
                        Reservas
                    WHERE
                        DATE_TRUNC('day', fechaInicio) >= CURRENT_DATE AND idsalon = $id 
                )
                SELECT DISTINCT fecha
                FROM dias_ocupados
                ORDER BY fecha");
            
            $sentenciaSQL->execute();
            
            $resultado = $sentenciaSQL->fetchAll(PDO::FETCH_OBJ);

            foreach($resultado as $fila)
            {
                $obj = new Reservas();
                $obj->fecha = $fila->fecha;
                $lista[] = $obj;
            }
            
            return $lista;
        }
        catch(PDOException $e){
            $lista[] = "";
            return $lista;
        }finally{
            Conexion::desconectar();
        }
    }

    public function obtenerEntreFechas($id,$inicio,$fin) {
        try
        {
            $this->conectar();
            
            $lista = array();
            $sentenciaSQL = $this->conexion->prepare(
                "WITH dias_ocupados AS (
                    SELECT
                        idSalon,
                        generate_series(
                            DATE_TRUNC('day', fechaInicio),
                            DATE_TRUNC('day', fechaFin),
                            '1 day'::interval
                        )::date AS fecha
                    FROM
                        Reservas
                    WHERE
                        DATE_TRUNC('day', fechaInicio) >= CURRENT_DATE AND idsalon = ?
						AND fechaInicio>=? and fechaFin <= ?
                )
                SELECT DISTINCT fecha
                FROM dias_ocupados
                ORDER BY fecha");
            
            $sentenciaSQL->execute([$id,$inicio,$fin]);
            
            $resultado = $sentenciaSQL->fetchAll(PDO::FETCH_OBJ);

            foreach($resultado as $fila)
            {
                $obj = new Reservas();
                $obj->fecha = $fila->fecha;
                $lista[] = $obj;
            }
            
            return $lista;
        }
        catch(PDOException $e){
            $lista[] = "";
            return $lista;
        }finally{
            Conexion::desconectar();
        }
    }

    public function insertarReserva($reserva) {
        try {
            $this->conectar();
            
            $sentenciaSQL = $this->conexion->prepare("INSERT INTO Reservas (idSalon, idUsuario, fechaInicio, fechaFin) VALUES (?, ?, ?, ?)");
            
            $sentenciaSQL->bindParam(1, $reserva -> idsalon, PDO::PARAM_INT);
            $sentenciaSQL->bindParam(2, $reserva -> idusuario , PDO::PARAM_INT);
            $sentenciaSQL->bindParam(3, $reserva -> fechainicio , PDO::PARAM_STR);
            $sentenciaSQL->bindParam(4, $reserva -> fechafin , PDO::PARAM_STR);
            
            $sentenciaSQL->execute();
            return true;
           // return $this->conexion->lastInsertId();
        } catch(PDOException $e) {
            return false;
        } finally {
            Conexion::desconectar();
        }
    }

    public function obtenerReservasByUsuario($idusuario) {
        try
        {
            $this->conectar();
            
            $lista = array();
            $sentenciaSQL = $this->conexion->prepare("SELECT 
                                                        r.idreserva AS idreserva,
                                                        r.idsalon AS idsalon,
                                                        r.idusuario AS idusuario,
                                                        r.fechaInicio AS fechaInicio,
                                                        r.fechafin AS fechafin,
                                                        (select u.nombre from usuarios u inner join salones sa on u.idusuario = sa.idusuario where s.idsalon = sa.idsalon ) as nombre,
                                                        u.telefono AS telefono,
                                                        s.nombresalon AS nombresalon
                                                    FROM 
                                                        reservas r
                                                    INNER JOIN 
                                                        Usuarios u 
                                                        ON u.idusuario = r.idusuario
                                                    INNER JOIN 
                                                    salones s 
                                                    ON s.idsalon = r.idsalon
                                                    WHERE 
                                                        r.idusuario = ?;");
            
            
            $sentenciaSQL->execute([$idusuario]);
                
            
            
            
            $resultado = $sentenciaSQL->fetchAll(PDO::FETCH_OBJ);


            foreach($resultado as $fila)
            {
                $obj = new Reservas();
                $obj->idreserva = $fila->idreserva;
                $obj->idsalon = $fila->idsalon;
                $obj->idusuario = $fila->idusuario;
                $obj->fechainicio = $fila->fechainicio;
                $obj->fechafin = $fila->fechafin;
                $obj->nombresalon = $fila->nombresalon;
                $obj->dueniotel = $fila->telefono;
                $obj->duenio = $fila->nombre;
                $lista[] = $obj;
            }
            return $lista;
        }
        catch(PDOException $e){
            $lista[] = "";
            return $e->getMessage();
        }finally{
            Conexion::desconectar();
        }
    }

    public function obtenerReservasByDuenio($idusuario) {
        try
        {
            $this->conectar();
            
            $lista = array();
            $sentenciaSQL = $this->conexion->prepare("SELECT  r.idreserva AS idreserva,
                                                        r.idsalon AS idsalon,
                                                        r.idusuario AS idusuario,
                                                        r.fechaInicio AS fechaInicio,
                                                        r.fechafin AS fechafin,
                                                        (SELECT u.nombre 
                                                        FROM usuarios u 
                                                        WHERE r.idUsuario = u.idusuario ) AS nombre,
                                                        u.telefono AS telefono,
                                                        s.nombresalon AS nombresalon
                                                FROM reservas r
                                                INNER JOIN usuarios u ON u.idusuario = r.idusuario
                                                INNER JOIN salones s ON s.idsalon = r.idsalon
                                                WHERE r.idsalon IN (
                                                    SELECT s2.idsalon 
                                                    FROM salones s2 
                                                    WHERE s2.idusuario = ?
                                                )");
            
            $sentenciaSQL->execute([$idusuario]);
                
            
            
            
            $resultado = $sentenciaSQL->fetchAll(PDO::FETCH_OBJ);


            foreach($resultado as $fila)
            {
                $obj = new Reservas();
                $obj->idreserva = $fila->idreserva;
                $obj->idsalon = $fila->idsalon;
                $obj->idusuario = $fila->idusuario;
                $obj->fechainicio = $fila->fechainicio;
                $obj->fechafin = $fila->fechafin;
                $obj->nombresalon = $fila->nombresalon;
                $obj->usuarioTel = $fila->telefono;
                $obj->usuario = $fila->nombre;
                $lista[] = $obj;
            }
            return $lista;
        }
        catch(PDOException $e){
            $lista[] = "";
            return $e->getMessage();
        }finally{
            Conexion::desconectar();
        }
    }

    public function eliminar($id)
	{
		try 
		{
			$this->conectar();
            
            $sentenciaSQL = $this->conexion->prepare("delete from reservas where idreserva = ?;");			          
			$resultado=$sentenciaSQL->execute(array($id));
			return $resultado;
		} catch (PDOException $e) 
		{
			//Si quieres acceder expecíficamente al numero de error
			//se puede consultar la propiedad errorInfo
			return false;	
		}finally{
            Conexion::desconectar();
        }

		
        
	}
    
}
?>
