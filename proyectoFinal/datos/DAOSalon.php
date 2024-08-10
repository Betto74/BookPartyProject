<?php
//importa la clase conexión y el modelo para usarlos
require_once 'conexion.php'; 
require_once '../modelos/Salon.php'; 

class DAOSalon
{
    
	private $conexion; 
    
    /**
     * Permite obtener la conexión a la BD
     */
    private function conectar(){
        try{
			$this->conexion = Conexion::conectar(); 
		}
		catch(Exception $e)
		{
			die($e->getMessage()); /*Si la conexion no se establece se cortara el flujo enviando un mensaje con el error*/
		}
    }
    

   /**
    * Metodo que obtiene todos los usuarios de la base de datos y los
    * retorna como una lista de objetos  
    */
	public function obtenerTodos()
	{
		try
		{
            $this->conectar();
            
			$lista = array();
            /*Se arma la sentencia sql para seleccionar todos los registros de la base de datos*/
			$sentenciaSQL = $this->conexion->prepare("SELECT idsalon,idusuario,nombresalon,ubicacion,descripcion,imagenprincipal FROM salones ");
			
            //Se ejecuta la sentencia sql, retorna un cursor con todos los elementos
			$sentenciaSQL->execute();
            
            //$resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
            $resultado = $sentenciaSQL->fetchAll(PDO::FETCH_OBJ);
            /*Podemos obtener un cursor (resultado con todos los renglones como 
            un arreglo de arreglos asociativos o un arreglo de objetos*/
            /*Se recorre el cursor para obtener los datos*/
			
            foreach($resultado as $fila)
			{
				$obj = new Salon();
                $obj->idsalon = $fila->idsalon;
                $obj->idusuario = $fila->idusuario;
	            $obj->nombresalon = $fila->nombresalon;
                $obj->ubicacion = $fila->ubicacion;
                $obj->descripcion = $fila->descripcion;
	            $obj->imagenprincipal = $fila->imagenprincipal;
               
				//Agrega el objeto al arreglo, no necesitamos indicar un índice, usa el próximo válido
                $lista[] = $obj;
			}
            
			return $lista;
		}
		catch(PDOException $e){
			return null;
		}finally{
            Conexion::desconectar();
        }
	}
    
    /**
     * Metodo que obtiene un registro de la base de datos, retorna un objeto  
     */
    public function obtenerUno($id)
	{
		try
		{ 
            $this->conectar();
            
            //Almacenará el registro obtenido de la BD
			$obj = null; 
            
			$sentenciaSQL = $this->conexion->prepare("SELECT idsalon,idusuario,nombresalon,ubicacion,descripcion,imagenprincipal  FROM salones WHERE idsalon=?"); 
			//Se ejecuta la sentencia sql con los parametros dentro del arreglo 
            $sentenciaSQL->execute([$id]);
            
            /*Obtiene los datos*/
			$fila=$sentenciaSQL->fetch(PDO::FETCH_OBJ);
			
            $obj = null;
            if($fila){            
                $obj = new Salon();
                $obj->idsalon = $fila->idsalon;
                $obj->idusuario = $fila->idusuario;
	            $obj->nombresalon = $fila->nombresalon;
                $obj->ubicacion = $fila->ubicacion;
                $obj->descripcion = $fila->descripcion;
	            $obj->imagenprincipal = $fila->imagenprincipal;
            }
           
            return $obj;
		}
		catch(Exception $e){
            return null;
		}finally{
            Conexion::desconectar();
        }
	}
        
    /**
     * Elimina el usuario con el id indicado como parámetro
     */
	public function eliminar($id)
	{
		try 
		{
			$this->conectar();
            
            $sentenciaSQL = $this->conexion->prepare("DELETE FROM usuarios WHERE id = ?");			          
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

	/**
     * Función para editar al empleado de acuerdo al objeto recibido como parámetro
     */
	public function editar(Usuario $obj)
	{
		try 
		{
			$sql = "UPDATE usuarios
                    SET
                    nombre = ?,
                    apellido1 = ?,
                    apellido2 = ?,
                    email = ?,
                    genero = ?,
                    telefono = ?,
                    rol = ?,
                    contrasenia = sha224(?)
                    WHERE id = ?;";

            $this->conectar();
            
            $sentenciaSQL = $this->conexion->prepare($sql);
			$sentenciaSQL->execute(
				array($obj->nombre,
                      $obj->apellido1,
                      $obj->apellido2,
					  $obj->correo,
                      $obj->genero,
                      $obj->telefono,
                      $obj->rol,
                      $obj->contrasenia,
					  $obj->id)
					);
            return true;
		} catch (PDOException $e){
            
            //var_dump($e);
			//Si quieres acceder expecíficamente al numero de error
			//se puede consultar la propiedad errorInfo
			return false;
		}finally{
            Conexion::desconectar();
        }
	}

	
	/**
     * Agrega un nuevo usuario de acuerdo al objeto recibido como parámetro
     */
    public function agregar(Usuario $obj)
	{
        $clave=0;
		try 
		{
            $sql = "INSERT INTO Usuarios
                (nombre,
                apellido1,
                apellido2,
                email,
                genero,
                telefono,
                contrasenia,
                rol)
                VALUES
                (:nombre,
                :apellido1,
                :apellido2,
                :email,
                :genero,
                :telefono,
                sha224(:contrasenia),
                :rol);";
                
            $this->conectar();
            $this->conexion->prepare($sql)
                 ->execute(array(
                    ':nombre'=>$obj->nombre,
                 ':apellido1'=>$obj->apellido1,
                 
                 ':email'=>$obj->correo,
                 ':genero'=>$obj->genero,
                 ':telefono'=>$obj->telefono,
                 ':apellido2'=>$obj->apellido2,
                 ':contrasenia'=>$obj->contrasenia,
                 ':rol'=>$obj->rol));
                 
            $clave=$this->conexion->lastInsertId();
            return $clave;
		} catch (Exception $e){
			
            return $clave;
		}finally{
            
            /*En caso de que se necesite manejar transacciones, 
			no deberá desconectarse mientras la transacción deba 
			persistir*/
            
            Conexion::desconectar();
        }
	}
}