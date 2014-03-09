<?php
class conexion{
	
	private $mysql_user 	= DBUSER;
	private $mysql_pass 	= DBPASSWD;	
	private $mysql_host 	= DBSERVER;
	protected  $mysql_db	= DBASE;
	private $conexion;
	
	/*
	 * metodo constructor de la clase
	 */
	function __construct(){
		$this->conexion_db();
	}
	/*
	 * metodo destructor de la clase
	 */
	function __destruct(){
		unset($this);
	}
	/* Objeto de conexion con el servidor mysql*/
	final public function conexion_db(){
		$resultado	= NULL;
		try{
			if($this->conexion = @mysql_connect($this->mysql_host,$this->mysql_user,$this->mysql_pass)){
				if(@mysql_select_db($this->mysql_db,$this->conexion)){
					return $this->conexion;
				}else{
					$resultado = "Error al momento de seleccionar la Base de Datos! <br />";
					throw new Exception($resultado);
					echo $resultado;					
				}
			}else{
				$resultado = "Error en la conexion con el Servidor! <br />";
				throw new Exception($resultado);
				echo $resultado;				
			}
		}catch(Exception $e){
			echo "Error: ".$e->getMessage()."";
		}
	}
	/* Fin de la declaracion del objeto conexion.-*/
	/* Objeto cerrar conexion*/
	final public function cerrar_conexion($conexion){
		$resultado	= NULL;
		try{
			if(!@mysql_close($conexion)){
				$resultado = "Error al momento de cerrar la conexion con el Servidor! <br />";
				throw new Exception($resultado);
				echo $resultado;
			}
		}catch(Exception $e){
			echo "Error: ".$e->getMessage()."";
		}
	}
	/* Fin de la declaracion del Objeto cerrar conexion.-*/
}
?>