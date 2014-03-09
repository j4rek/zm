<?php
require("class_conexion_db.php");
class consultas extends conexion{
	//Definicion de la clase.-
	
	/*
	 * Metodo constructor de la clase.-
	 */
	function __construct(){
		parent::__construct();
	}
	/*
	 * Metodo destructor
	 */
	function __destruct(){
		unset($this);
	}
	/*
	 Metodo para rescatar el digito verificador de un rut.
		*/
	final public function getDigitoVerificador($r){
		$s=1;
		for($m=0;$r!=0;$r/=10)
		$s=($s+$r%10*(9-$m++%6))%11;
		return chr($s?$s+47:75);
	}
	/*
	 Metodo para rescatar datos de una consulta y expresarlos en una tabla.
		*/
	final public function getListar($varSql){
		$conectar 		 = $this->conexion_db();
		$retorno 		 	 = NULL;
		try{
			(string)$query 	 = $varSql;
			$resultado		 = @mysql_query($query, $conectar) or die ("<b>Problemas con la consulta:</b><br /><span style='color:red;'><b>".mysql_error()."</b></span><br />".$query);
			if($resultado){
				if (@mysql_num_rows($resultado) > 0){
					for((int)$i = 0; $datos = @mysql_fetch_assoc($resultado); $i++) {
						$retorno[$i] = $datos;
					}
				}else{
					$retorno = "No se encontraron registros!";
					throw new Exception($retorno);
				}
			}else{
				$retorno = "No se pudo conectar a la base de datos <br />";
				throw new Exception($retorno);
			}
		}catch(Exception $ex){
			$retorno = "Error: ".$ex->getMessage()."";
		}		
		return $retorno;
		$this->cerrar_conexion($conectar);		
	}
	/*
	 Metodo para ejecutar una consulta.-
		*/
	final public function getEjecuta($varSql){
		$resultado	= NULL;
		$conectar	= $this->conexion_db();
		try{
			$sql		= $varSql;
			$query		= @mysql_query($sql, $conectar) or die ("<b>Problemas con la consulta:</b><br /><span style='color:red;'><b>".mysql_error()."</b></span><br />".$sql);
			if($query){
				$resultado = $query;
			}else{
				$resultado = NULL;
				throw new Exception('No se puede ejecutar la accion');
			}
		}catch(Exception $ex){
			$resultado = "Error: ".$ex->getMessage()."";
		}		
		return $resultado;
		$this->cerrar_conexion($conectar);		
	}
	/*
	 Metodo paradevolver numero de registros afectados por la consulta
		*/
	final public function getNumRegistros($varSql){
		$resultado 	= NULL;
		$conectar 	= $this->conexion_db();
		try{
			(string)$sql 		= $varSql;
			(int)$registros 	= mysql_affected_rows($sql, $conectar);
			if($registros){
				$resultado = $registros;
			}else{
				$resultado = NULL;
				throw new Exception('No se puede ejecutar la accion');
			}
		}catch(Exception $ex){
			$resultado = "Error : ".$ex->getMessage()."";
		}		
		return $resultado;
		$this->cerrar_conexion($conectar);		
	}
	/* contador de registros*/
	final public function getContar($varSql, $varContar){
		$resultado 			= NULL;
		$conectar			= $this->conexion_db();
		(string)$sql 		= $varSql;
		(string)$cContar	= $varContar;
		try{
			if(isset($sql) && isset($cContar) && $sql != "" && $cContar != "" ){
				$consulta = $this->getListar($sql);
				if(is_array($consulta)){
					for((int)$i = 0; $i <= count($consulta)-1; $i++){
						$resultado = $consulta[$i][$cContar];
					}
				}else{
					$resultado = "Sin resultados!";
					throw new Exception($resultado);
				}
			}else{
				$resultado = "Sin realizar nada.";
				throw new Exception($resultado);
			}
		}catch(Exception $ex){
			$resultado = "Error : ".$ex->getMessage()."";
		}		
		return $resultado;
		$this->cerrar_conexion($conectar);		
	}
	/* numero de propiedades de un cliente*/
	final public function getNumProp($varSql, $varCookie){
		$resultado 				= NULL;
		$conectar 				= $this->conexion_db();
		(int)$codigoCliente 	= $varSql;
		(int)$codEmpresa		= $varCookie;
		(string)$error			= "Error : ";
		(string)$sql 				= "SELECT COUNT(*) AS numProp
												 FROM propiedades 
												 WHERE propIdCliente = '".$codigoCliente."'
												 AND propIdEmpresa = '".$codEmpresa."' 
												 AND propCubo = '1' 
												 AND propBorrada = '0' 
												 LIMIT 1;";
		try{
			if(isset($codigoCliente) && $codigoCliente != ""){
				if(isset($codEmpresa) && $codEmpresa != ""){
					$consultar = $this->getListar($sql);
					if(is_array($consultar)){
						$resultado = $consultar[0]['numProp'];
					}else{
					}
				}else{
					$resultado = $error."No se definio el codigo de la empresa!";
					throw new Exception($resultado);
				}
			}else{
				$resultado = $error."No se definio el codigo del cliente!";
				throw new Exception($resultado);
			}
		}catch(Exception $ex){
			$resultado = "Error : ".$ex->getMessage()."";
		}		
		return $resultado;
		$this->cerrar_conexion($conectar);		
	}
	#Metodo fecth_assoc
	final public function getFetchAssoc($varSql){
 		$resultado 			= NULL;
 		$conectar 			= $this->conexion_db();
 		(string)$error		= "<b>Error : </b>";
 		try{
 			if(isset($varSql) && $varSql!= ""){
				$retorno	= @mysql_query($varSql, $conectar) or die ("<b>Problemas con la consulta:</b><br /><span style='color:red;'><b>".mysql_error()."</b></span><br />".$varSql);
 				$resultado  = mysql_fetch_array($retorno);
 			}else{
 				throw new Exception('No se puede ejecutar la accion');
 				$resultado = $error."Faltan parametros para la accion";
 			}
 		}catch(Exception $ex){
 			$resultado = "Error : ".$ex->getMessage()."";
 		} 		 
  		return $resultado;
  		$this->cerrar_conexion($conectar);    
  	}
	#Metodo fecth_array
 	final public function getDatosArray($varSql){
 		$resultado 			= NULL;
 		$conectar 			= $this->conexion_db();
 		(string)$error		= "<b>Error : </b>";
 		try{
 			if(isset($varSql) && $varSql!= ""){
 				$resultado = mysql_fetch_array($varSql);
 			}else{
 				throw new Exception('No se puede ejecutar la accion');
 				$resultado = $error."Faltan parametros para la accion";
 			}
 		}catch(Exception $ex){
 			$resultado = "Error : ".$ex->getMessage()."";
 		} 		 
  		return $resultado;
  		$this->cerrar_conexion($conectar);    
  	}
	#Metodo num_rows
 	final public function getNumRows($varSql){   
  		$resultado 			= NULL;
 		$conectar 			= $this->conexion_db();
 		(string)$error		= "<b>Error : </b>";
 		try{
 			if(isset($varSql) && $varSql!= ""){
				$retorno	= @mysql_query($varSql, $conectar) or die ("<b>Problemas con la consulta:</b><br /><span style='color:red;'><b>".mysql_error()."</b></span><br />".$varSql);
 				$resultado 	= mysql_num_rows($retorno);
 			}else{
 				throw new Exception('No se puede ejecutar la accion');
 				$resultado = $error."Faltan parametros para la accion";
 			}
 		}catch(Exception $ex){
 			$resultado = "Error : ".$ex->getMessage()."";
 		} 		   
  		return $resultado;
  		$this->cerrar_conexion($conectar);  
  	}
}
?>