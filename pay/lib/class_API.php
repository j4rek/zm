<?php
#doc
#	classname:	API
#	scope:		PUBLIC
# clase base para la aplicacion
#/doc
# requieres #
require_once("./API/config.inc.php");
require_once("./API/API.php");
class API 
{
	#	internal variables
	## BASE DE DATOS ##
	private $mysql_user 	= DBUSER;
	private $mysql_pass 	= DBPASSWD;
	private $mysql_db 		= DBASE;
	private $mysql_host 	= DBSERVER;
	public $TEMPLATE		= null;
	public $PREVIEW			= null;
	public $CODIFICAR		= null;
	public $NOTIFICACIONES	= null;
	public $ACCESO			= null;
	public $PAGO				=null;
	public $EMAIL				=null;
	public $CONX				=null;
	private $_ERROR="<span style=\"background:black;color:#55FF55;font-weight:bold;width:100%;float:left;position:absolute;\"><!-- ERROR --></span><br>";
	
	#	Constructor
	function __construct ()
	{
		# code...
	}
	/*
	 * metodo destructor de la clase
	 */
	function __destruct(){
		unset($this);
	}
	### [FUNCIONES BASE DE DATOS] ###
	public function conexion_db()
	{
		$resultado	= NULL;
		try{
			if($conexion = @mysql_connect($this->mysql_host,$this->mysql_user,$this->mysql_pass)){
				if(@mysql_select_db($this->mysql_db,$conexion)){
					return $conexion;
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
	/***************************************/
	public function cerrar_conexion($conexion)
	{
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
	/*	****** Metodo para rescatar el digito verificador de un rut.		**********/
	final public function getDigitoVerificador($r){
		$s=1;
		for($m=0;$r!=0;$r/=10)
		$s=($s+$r%10*(9-$m++%6))%11;
		return chr($s?$s+47:75);
	}
	/**********	 Metodo para rescatar datos de una consulta y expresarlos en una tabla.		*************/
	final public function getListar($varSql){
		$conectar 		 = $this->conexion_db();
		$retorno 		 = NULL;
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
	/*	**********  Metodo para ejecutar una consulta.-		************/
	final public function getEjecuta($varSql){
		$resultado	= NULL;
		$conectar	= $this->conexion_db();
		try{
			$sql				= $varSql;
			$query			= @mysql_query($sql, $conectar) or die ("<b>Problemas con la consulta:</b><br /><span style='color:red;'><b>".mysql_error()."</b></span><br />".$sql);
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
	/**********	 Metodo para devolver numero de registros afectados por la consulta		**********/
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
	/******** contador de registros **********/
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
	/***************************************/
 	final private function getDatosArray($varSql){
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
	/***************************************/
 	final private function getNumRows($varSql){   
  		$resultado 			= NULL;
 		$conectar 			= $this->conexion_db();
 		(string)$error		= "<b>Error : </b>";
 		try{
 			if(isset($varSql) && $varSql!= ""){
 				$resultado = mysql_num_rows($varSql);
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
	/***************************************/
	public function qry($sqlstr,$mostrar=0) 
	{
		#$conx=$this->conexion_db();
		$res = mysql_query($sqlstr,$this->CONX);
		if($mostrar==1)
			echo "<span style=\"background:black;color:#55FF55;font-weight:bold;width:100%;float:left;\">".$sqlstr."</span><br>";

		if(!$res) {
			if(DEBUG){
				die('Error en Consulta: </br>' . mysql_error()."</br><span style='color:#2d85d7;'>".$sqlstr."</span>");
			//echo "1.Error en la Base de Datos, contacte al Admin.";
			#header("location: ./");
			}
			else
				{
					header("location: ./");
				}
			exit;
		}
		return $res;
		#$this->cerrar_conexion($conx);
		
	}
	/***************************************/
	### [FIN FUNCIONES BASE DE DATOS] ###
	
	### [FUNCIONES GENERALES] ###
	public function dame_nombre_comuna( $id_comuna )
	{
	// devuelvo el nombre de comuna, segun el id.. lo hago en funcion para evitar una consulta
	// innecesaria a BD... se supone que las comunas no cambian, salvo un casos muy raros
		switch($id_comuna){
			case 0: $nombre="No definida";	 break;
			case 1: $nombre="General Lagos";	 break;
			case 2: $nombre="Putre";	 break;
			case 3: $nombre="Arica";	 break;
			case 4: $nombre="Camarones";	 break;
			case 5: $nombre="Huara";	 break;
			case 6: $nombre="Cami&ntilde;a";	 break;
			case 7: $nombre="Colchane";	 break;
			case 8: $nombre="Iquique";	 break;
			case 9: $nombre="Pozo Almonte";	 break;
			case 10: $nombre="Pica";	 break;
			case 11: $nombre="Tocopilla";	 break;
			case 12: $nombre="Mar&iacute;a Elena";	 break;
			case 13: $nombre="Calama";	 break;
			case 14: $nombre="Ollagüe";	 break;
			case 15: $nombre="San Pedro de Atacama";	 break;
			case 16: $nombre="Mejillones";	 break;
			case 17: $nombre="Sierra Gorda";	 break;
			case 18: $nombre="Antofagasta";	 break;
			case 19: $nombre="Taltal";	 break;
			case 20: $nombre="Cha&ntilde;aral";	 break;
			case 21: $nombre="Diego de Almagro";	 break;
			case 22: $nombre="Caldera";	 break;
			case 23: $nombre="Copiap&oacute;";	 break;
			case 24: $nombre="Tierra Amarilla";	 break;
			case 25: $nombre="Huasco";	 break;
			case 26: $nombre="Vallenar";	 break;
			case 27: $nombre="Freirina";	 break;
			case 28: $nombre="Alto del Carmen";	 break;
			case 29: $nombre="La Higuera";	 break;
			case 30: $nombre="Vicu&ntilde;a";	 break;
			case 31: $nombre="La Serena";	 break;
			case 32: $nombre="Coquimbo";	 break;
			case 33: $nombre="Andacollo";	 break;
			case 34: $nombre="Paiguano";	 break;
			case 35: $nombre="Ovalle";	 break;
			case 36: $nombre="R&iacute;o Hurtado";	 break;
			case 37: $nombre="Punitaqui";	 break;
			case 38: $nombre="Monte Patria";	 break;
			case 39: $nombre="Combarbal&aacute;";	 break;
			case 40: $nombre="Canela";	 break;
			case 41: $nombre="Illapel";	 break;
			case 42: $nombre="Los Vilos";	 break;
			case 43: $nombre="Salamanca";	 break;
			case 44: $nombre="Algarrobo";	 break;
			case 45: $nombre="El Quisco";	 break;
			case 46: $nombre="El Tabo";	 break;
			case 47: $nombre="Cartagena";	 break;
			case 48: $nombre="San Antonio";	 break;
			case 49: $nombre="Santo Domingo";	 break;
			case 50: $nombre="Isla de Pascua";	 break;
			case 51: $nombre="Petorca";	 break;
			case 52: $nombre="La Ligua";	 break;
			case 53: $nombre="Cabildo";	 break;
			case 54: $nombre="Papudo";	 break;
			case 55: $nombre="Zapallar";	 break;
			case 56: $nombre="Putaendo";	 break;
			case 57: $nombre="Catemu";	 break;
			case 58: $nombre="San Felipe";	 break;
			case 59: $nombre="Santa Mar&iacute;a";	 break;
			case 60: $nombre="Panquehue";	 break;
			case 61: $nombre="Llaillay";	 break;
			case 62: $nombre="Nogales";	 break;
			case 63: $nombre="Calera";	 break;
			case 64: $nombre="La Cruz";	 break;
			case 65: $nombre="Quillota";	 break;
			case 66: $nombre="Hijuelas";	 break;
			case 67: $nombre="Limache";	 break;
			case 68: $nombre="Olmu&eacute;";	 break;
			case 69: $nombre="San Esteban";	 break;
			case 70: $nombre="Rinconada";	 break;
			case 71: $nombre="Calle Larga";	 break;
			case 72: $nombre="Los Andes";	 break;
			case 73: $nombre="Puchuncav&iacute;";	 break;
			case 74: $nombre="Juan Fern&aacute;ndez";	 break;
			case 75: $nombre="Quintero";	 break;
			case 76: $nombre="Vi&ntilde;a del Mar";	 break;
			case 77: $nombre="Villa Alemana";	 break;
			case 78: $nombre="Valpara&iacute;so";	 break;
			case 79: $nombre="Quilpu&eacute;";	 break;
			case 80: $nombre="Casablanca";	 break;
			case 81: $nombre="Conc&oacute;n";	 break;
			case 82: $nombre="Mostazal";	 break;
			case 83: $nombre="Graneros";	 break;
			case 84: $nombre="Codegua";	 break;
			case 85: $nombre="Rancagua";	 break;
			case 86: $nombre="Machal&iacute;";	 break;
			case 87: $nombre="Las Cabras";	 break;
			case 88: $nombre="Coltauco";	 break;
			case 89: $nombre="Do&ntilde;ihue";	 break;
			case 90: $nombre="Olivar";	 break;
			case 91: $nombre="Coinco";	 break;
			case 92: $nombre="Requ&iacute;noa";	 break;
			case 93: $nombre="Peumo";	 break;
			case 94: $nombre="Quinta de Tilcoco";	 break;
			case 95: $nombre="Pichidegua";	 break;
			case 96: $nombre="San Vicente";	 break;
			case 97: $nombre="Malloa";	 break;
			case 98: $nombre="Rengo";	 break;
			case 99: $nombre="Navidad";	 break;
			case 100: $nombre="Litueche";	 break;
			case 101: $nombre="La Estrella";	 break;
			case 102: $nombre="Pichilemu";	 break;
			case 103: $nombre="Marchihue";	 break;
			case 104: $nombre="Paredones";	 break;
			case 105: $nombre="Peralillo";	 break;
			case 106: $nombre="Palmilla";	 break;
			case 107: $nombre="San Fernando";	 break;
			case 108: $nombre="Pumanque";	 break;
			case 109: $nombre="Santa Cruz";	 break;
			case 110: $nombre="Nancagua";	 break;
			case 111: $nombre="Placilla";	 break;
			case 112: $nombre="Lolol";	 break;
			case 113: $nombre="Ch&eacute;pica";	 break;
			case 114: $nombre="Chimbarongo";	 break;
			case 115: $nombre="San Javier";	 break;
			case 116: $nombre="Villa Alegre";	 break;
			case 117: $nombre="Yerbas Buenas";	 break;
			case 118: $nombre="Colb&uacute;n";	 break;
			case 119: $nombre="Linares";	 break;
			case 120: $nombre="Retiro";	 break;
			case 121: $nombre="Longav&iacute;";	 break;
			case 122: $nombre="Parral";	 break;
			case 123: $nombre="Teno";	 break;
			case 124: $nombre="Vichuqu&eacute;n";	 break;
			case 125: $nombre="Huala&ntilde;e";	 break;
			case 126: $nombre="Rauco";	 break;
			case 127: $nombre="Curic&oacute;";	 break;
			case 128: $nombre="Romeral";	 break;
			case 129: $nombre="Licant&eacute;n";	 break;
			case 130: $nombre="Sagrada Familia";	 break;
			case 131: $nombre="Molina";	 break;
			case 132: $nombre="Chanco";	 break;
			case 133: $nombre="Cauquenes";	 break;
			case 134: $nombre="Pelluhue";	 break;
			case 135: $nombre="Curepto";	 break;
			case 136: $nombre="R&iacute;o Claro";	 break;
			case 137: $nombre="Constituci&oacute;n";	 break;
			case 138: $nombre="Pencahue";	 break;
			case 139: $nombre="Talca";	 break;
			case 140: $nombre="Pelarco";	 break;
			case 141: $nombre="San Clemente";	 break;
			case 142: $nombre="Maule";	 break;
			case 143: $nombre="Empedrado";	 break;
			case 144: $nombre="San Rafael";	 break;
			case 145: $nombre="Arauco";	 break;
			case 146: $nombre="Curanilahue";	 break;
			case 147: $nombre="Lebu";	 break;
			case 148: $nombre="Los Álamos";	 break;
			case 149: $nombre="Ca&ntilde;ete";	 break;
			case 150: $nombre="Contulmo";	 break;
			case 151: $nombre="Tir&uacute;a";	 break;
			case 152: $nombre="Cobquecura";	 break;
			case 153: $nombre="Quirihue";	 break;
			case 154: $nombre="Ninhue";	 break;
			case 155: $nombre="San Carlos";	 break;
			case 156: $nombre="Ñiqu&eacute;n";	 break;
			case 157: $nombre="San Fabi&aacute;n";	 break;
			case 158: $nombre="San Nicol&aacute;s";	 break;
			case 159: $nombre="Treguaco";	 break;
			case 160: $nombre="Portezuelo";	 break;
			case 161: $nombre="Chill&aacute;n";	 break;
			case 162: $nombre="Coihueco";	 break;
			case 163: $nombre="Coelemu";	 break;
			case 164: $nombre="Ranquil";	 break;
			case 165: $nombre="Pinto";	 break;
			case 166: $nombre="Quill&oacute;n";	 break;
			case 167: $nombre="Bulnes";	 break;
			case 168: $nombre="San Ignacio";	 break;
			case 169: $nombre="El Carmen";	 break;
			case 170: $nombre="Pemuco";	 break;
			case 171: $nombre="Yungay";	 break;
			case 172: $nombre="Chill&aacute;n Viejo";	 break;
			case 173: $nombre="San Rosendo";	 break;
			case 174: $nombre="Yumbel";	 break;
			case 175: $nombre="Cabrero";	 break;
			case 176: $nombre="Laja";	 break;
			case 177: $nombre="Los Ángeles";	 break;
			case 178: $nombre="Tucapel";	 break;
			case 179: $nombre="Antuco";	 break;
			case 180: $nombre="Quilleco";	 break;
			case 181: $nombre="Nacimiento";	 break;
			case 182: $nombre="Negrete";	 break;
			case 183: $nombre="Mulch&eacute;n";	 break;
			case 184: $nombre="Santa B&aacute;rbara";	 break;
			case 185: $nombre="Quilaco";	 break;
			case 186: $nombre="Tom&eacute;";	 break;
			case 187: $nombre="Talcahuano";	 break;
			case 188: $nombre="Penco";	 break;
			case 189: $nombre="Florida";	 break;
			case 190: $nombre="Concepci&oacute;n";	 break;
			case 191: $nombre="Coronel";	 break;
			case 192: $nombre="Hualqui";	 break;
			case 193: $nombre="Lota";	 break;
			case 194: $nombre="Santa Juana";	 break;
			case 195: $nombre="San Pedro de la Paz";	 break;
			case 196: $nombre="Chiguayante";	 break;
			case 197: $nombre="Angol";	 break;
			case 198: $nombre="Renaico";	 break;
			case 199: $nombre="Collipulli";	 break;
			case 200: $nombre="Pur&eacute;n";	 break;
			case 201: $nombre="Los Sauces";	 break;
			case 202: $nombre="Ercilla";	 break;
			case 203: $nombre="Lonquimay";	 break;
			case 204: $nombre="Lumaco";	 break;
			case 205: $nombre="Traigu&eacute;n";	 break;
			case 206: $nombre="Victoria";	 break;
			case 207: $nombre="Curacaut&iacute;n";	 break;
			case 208: $nombre="Galvarino";	 break;
			case 209: $nombre="Perquenco";	 break;
			case 210: $nombre="Carahue";	 break;
			case 211: $nombre="Nueva Imperial";	 break;
			case 212: $nombre="Temuco";	 break;
			case 213: $nombre="Lautaro";	 break;
			case 214: $nombre="Vilc&uacute;n";	 break;
			case 215: $nombre="Melipeuco";	 break;
			case 216: $nombre="Saavedra";	 break;
			case 217: $nombre="Teodoro Schmidt";	 break;
			case 218: $nombre="Freire";	 break;
			case 219: $nombre="Cunco";	 break;
			case 220: $nombre="Tolt&eacute;n";	 break;
			case 221: $nombre="Pitrufqu&eacute;n";	 break;
			case 222: $nombre="Gorbea";	 break;
			case 223: $nombre="Loncoche";	 break;
			case 224: $nombre="Villarrica";	 break;
			case 225: $nombre="Puc&oacute;n";	 break;
			case 226: $nombre="Curarrehue";	 break;
			case 227: $nombre="Padre las Casas";	 break;
			case 228: $nombre="Mariquina";	 break;
			case 229: $nombre="Lanco";	 break;
			case 230: $nombre="Panguipulli";	 break;
			case 231: $nombre="M&aacute;fil";	 break;
			case 232: $nombre="Valdivia";	 break;
			case 233: $nombre="Los Lagos";	 break;
			case 234: $nombre="Corral";	 break;
			case 235: $nombre="Paillaco";	 break;
			case 236: $nombre="Futrono";	 break;
			case 237: $nombre="La Uni&oacute;n";	 break;
			case 238: $nombre="Lago Ranco";	 break;
			case 239: $nombre="R&iacute;o Bueno";	 break;
			case 240: $nombre="San Juan de la Costa";	 break;
			case 241: $nombre="San Pablo";	 break;
			case 242: $nombre="Osorno";	 break;
			case 243: $nombre="Puyehue";	 break;
			case 244: $nombre="R&iacute;o Negro";	 break;
			case 245: $nombre="Puerto Octay";	 break;
			case 246: $nombre="Purranque";	 break;
			case 247: $nombre="Fresia";	 break;
			case 248: $nombre="Frutillar";	 break;
			case 249: $nombre="Puerto Varas";	 break;
			case 250: $nombre="Llanquihue";	 break;
			case 251: $nombre="Los Muermos";	 break;
			case 252: $nombre="Puerto Montt";	 break;
			case 253: $nombre="Cocham&oacute;";	 break;
			case 254: $nombre="Maull&iacute;n";	 break;
			case 255: $nombre="Calbuco";	 break;
			case 256: $nombre="Ancud";	 break;
			case 257: $nombre="Quemchi";	 break;
			case 258: $nombre="Dalcahue";	 break;
			case 259: $nombre="Castro";	 break;
			case 260: $nombre="Curaco de V&eacute;lez";	 break;
			case 261: $nombre="Quinchao";	 break;
			case 262: $nombre="Chonchi";	 break;
			case 263: $nombre="Puqueld&oacute;n";	 break;
			case 264: $nombre="Queil&eacute;n";	 break;
			case 265: $nombre="Quell&oacute;n";	 break;
			case 266: $nombre="Hualaihu&eacute;";	 break;
			case 267: $nombre="Chait&eacute;n";	 break;
			case 268: $nombre="Futaleuf&uacute;";	 break;
			case 269: $nombre="Palena";	 break;
			case 270: $nombre="Guaitecas";	 break;
			case 271: $nombre="Cisnes";	 break;
			case 272: $nombre="Ais&eacute;n";	 break;
			case 273: $nombre="Lago Verde";	 break;
			case 274: $nombre="Coihaique";	 break;
			case 275: $nombre="R&iacute;o Iba&ntilde;ez";	 break;
			case 276: $nombre="Chile Chico";	 break;
			case 277: $nombre="Cochrane";	 break;
			case 278: $nombre="Tortel";	 break;
			case 279: $nombre="O`Higgins";	 break;
			case 280: $nombre="Natales";	 break;
			case 281: $nombre="Torres del Paine";	 break;
			case 282: $nombre="Laguna Blanca";	 break;
			case 283: $nombre="San Gregorio";	 break;
			case 284: $nombre="R&iacute;o Verde";	 break;
			case 285: $nombre="Punta Arenas";	 break;
			case 286: $nombre="Primavera";	 break;
			case 287: $nombre="Porvenir";	 break;
			case 288: $nombre="Timaukel";	 break;
			case 289: $nombre="Cabo de Hornos";	 break;
			case 290: $nombre="Ant&aacute;rtica";	 break;
			case 291: $nombre="Tiltil";	 break;
			case 292: $nombre="Colina";	 break;
			case 293: $nombre="Lampa";	 break;
			case 294: $nombre="San Jos&eacute; de Maipo";	 break;
			case 295: $nombre="Puente Alto";	 break;
			case 296: $nombre="Pirque";	 break;
			case 297: $nombre="Curacav&iacute;";	 break;
			case 298: $nombre="Mar&iacute;a Pinto";	 break;
			case 299: $nombre="Melipilla";	 break;
			case 300: $nombre="San Pedro";	 break;
			case 301: $nombre="Alhue";	 break;
			case 302: $nombre="Pe&ntilde;aflor";	 break;
			case 303: $nombre="El Monte";	 break;
			case 304: $nombre="Talagante";	 break;
			case 305: $nombre="Isla de Maipo";	 break;
			case 306: $nombre="Padre Hurtado";	 break;
			case 307: $nombre="Calera de Tango";	 break;
			case 308: $nombre="San Bernardo";	 break;
			case 309: $nombre="Bu&iacute;n";	 break;
			case 310: $nombre="Paine";	 break;
			case 311: $nombre="Lo Barnechea";	 break;
			case 312: $nombre="Vitacura";	 break;
			case 313: $nombre="Las Condes";	 break;
			case 314: $nombre="La Reina";	 break;
			case 315: $nombre="Pe&ntilde;alol&eacute;n";	 break;
			case 316: $nombre="La Florida";	 break;
			case 317: $nombre="Pudahuel";	 break;
			case 318: $nombre="Cerro Navia";	 break;
			case 319: $nombre="Lo Prado";	 break;
			case 320: $nombre="Maip&uacute;";	 break;
			case 321: $nombre="Cerrillos";	 break;
			case 322: $nombre="Renca";	 break;
			case 323: $nombre="Quilicura";	 break;
			case 324: $nombre="Independencia";	 break;
			case 325: $nombre="Recoleta";	 break;
			case 326: $nombre="Conchal&iacute;";	 break;
			case 327: $nombre="Huechuraba";	 break;
			case 328: $nombre="Lo Espejo";	 break;
			case 329: $nombre="P. Aguirre Cerda";	 break;
			case 330: $nombre="La Cisterna";	 break;
			case 331: $nombre="San Ram&oacute;n";	 break;
			case 332: $nombre="La Granja";	 break;
			case 333: $nombre="El Bosque";	 break;
			case 334: $nombre="La Pintana";	 break;
			case 335: $nombre="San Miguel";	 break;
			case 336: $nombre="Quinta Normal";	 break;
			case 337: $nombre="Providencia";	 break;
			case 338: $nombre="Estaci&oacute;n Central";	 break;
			case 339: $nombre="Santiago";	 break;
			case 340: $nombre="Ñu&ntilde;oa";	 break;
			case 341: $nombre="San Joaqu&iacute;n ";	 break;
			case 342: $nombre="Macul";	 break;
			case 343: $nombre="Alto Hospicio";	 break;
			case 344: $nombre="Miraflores";	 break;
			case 345: $nombre="Re&ntilde;aca";	 break;
		}
		return $nombre;
	}
	/***************************************/
	public function dame_nombre_region($id_region)
	{
		switch($id_region)
		{
			case 1:	$str = "Tarapac&aacute;";				break;
			case 2:	$str = "Antofagasta";						break;
			case 3:	$str = "Atacama";							break;
			case 4:	$str = "Coquimbo";							break;
			case 5:	$str = "Valpara&iacute;so";			break;
			case 6:	$str = "Bernardo O'higgins";			break;
			case 7:	$str = "Maule";								break;
			case 8:	$str = "B&iacute;o B&iacute;o";		break;
			case 9:	$str = "Araucan&iacute;a";				break;
			case 10:$str = "Los Lagos";							break;
			case 11:$str = "Ays&eacute;n";					break;
			case 12:$str = "Magallanes y Ant&aacute;rtica Chilena";	break;
			case 13:$str = "R.M.";									break;
			case 14:$str = "Los R&iacute;os";				break;
			case 15:$str = "Arica y Parinacota";			break;
		}

		return $str;
	}
	/***************************************/
	public function nombre_mes($mes) 
	{
		$fecha = "";
		switch ($mes) {

			case "1": 	$fecha = "Enero";		break;
			case "2":	$fecha = "Febrero";		break;
			case "3":   $fecha = "Marzo";		break;
			case "4":	$fecha = "Abril";		break;
			case "5":	$fecha = "Mayo";		break;
			case "6":	$fecha = "Junio";		break;
			case "7":	$fecha = "Julio";		break;
			case "8":	$fecha = "Agosto";		break;
			case "9":	$fecha = "Septiembre";	break;
			case "10":	$fecha = "Octubre";		break;
			case "11":	$fecha = "Noviembre";	break;
			case "12":	$fecha = "Diciembre";	break;
		}
		return $fecha;
	}
	/***************************************/
	function nombre_mes_corto($mes) 
	{
		$fecha = "";
		switch ($mes) {
			case "1":		$fecha = "Ene";		break;
			case "2":		$fecha = "Feb";	break;
			case "3":		$fecha = "Mar";		break;
			case "4":		$fecha = "Abr";		break;
			case "5":		$fecha = "May";		break;
			case "6":		$fecha = "Jun";		break;
			case "7":		$fecha = "Jul";		break;
			case "8":		$fecha = "Ago";		break;
			case "9":		$fecha = "Sep";		break;
			case "10":		$fecha = "Oct";		break;
			case "11":		$fecha = "Nov";		break;
			case "12":		$fecha = "Dic";		break;
		}
		return $fecha;
	}
	/***************************************/
	public function valida_texto($texto) 
	{
/*	    $texto = str_replace ('<br />', '\n\n', $texto);
        $texto = str_replace( "Ã¡", "á", $texto);
        $texto = str_replace( "Ã©", "é", $texto);
        $texto = str_replace( "Ã*", "í", $texto);
        $texto = str_replace( "Ã­", "í", $texto);
        $texto = str_replace( "Ã³", "ó", $texto);
        $texto = str_replace( "Ãº", "ú", $texto);
        $texto = str_replace( "Âª", " ", $texto);
        $texto = str_replace( "Ã‘", "Ñ", $texto);
        $texto = str_replace( "Ã±", "ñ", $texto);
        $texto = str_replace( "ÿþ", " ", $texto);
*/

        $texto = str_replace ('\$', "$ ", $texto);
		$texto = str_replace ('&quot;', "\"", $texto );
		$texto = str_replace ("´", "'", $texto);
		$texto = str_replace ("`", "'", $texto);
		$texto = str_replace ("°", "&ordm;", $texto);
		$texto=iconv("ISO-8859-1","UTF-8",$texto);
		return $texto;
	}	
	/***************************************/
	public function corta_texto($texto, $largo) {
		// funcion que corta texto y agrega "..." al final, pero
		// solo si el texto a cortar es mas largo que el largo a dejar.
		$largoTotal = strlen($texto);
		if ($largoTotal > $largo) { // solo si largo de string es mayor a largo a dejar.
			$texto = substr( $texto, 0, $largo);
			$pos = strrpos($texto, " "); // busco ultimo espacio para alicar corte ahi
			$texto = substr( $texto, 0, $pos);
			$texto .= "...";
		} 
		return $texto;
	}
	/***************************************/
	public function despliega_string_fecha( $timestamp )
	{
			$ano  = substr($timestamp, 0, 4);
			$mes  = substr($timestamp, 4, 2);
			$dia  = substr($timestamp, 6, 2);
			$hora = substr($timestamp, 8, 2);
			$minutos = substr($timestamp, 10, 2);

			return $dia ."/". $this->nombre_mes($mes) ."/". $ano ."&nbsp;&nbsp;". $hora .":". $minutos;
	}
	/***************************************/
	public function formatea_fecha_corta( $datetime )
	{

	$ano  = substr($datetime, 0, 4);
	$mes  = substr($datetime, 5, 2);
	$dia  = substr($datetime, 8, 2);

	return $dia."/".$this->nombre_mes_corto($mes)."/".$ano;
	}
	/***************************************/
	public function formatea_fecha( $timestamp ){

	$ano  = substr($timestamp, 0, 4);
	$mes  = substr($timestamp, 4, 2);
	$dia  = substr($timestamp, 6, 2);
	//$hora = substr($timestamp, 8, 2);
	//$minutos = substr($timestamp, 10, 2);
	//return $dia ."/". nombre_mes($mes) ."/". $ano ."&nbsp;&nbsp;". $hora .":". $minutos;
	return $dia ." / ". $this->nombre_mes($mes)." / ".$ano;
	}
	/***************************************/
	public function dame_imagen( $imagen, $subcarpeta )
	{
		// si imagen no tiene ruta absoluta, la genero.
		// si la tiene, la mantengo.
		if ( $this->url_absoluta($imagen) ){
			$archivo = $imagen;
		} else {
			$archivo = URL_ARCHIVOS . $subcarpeta . $imagen;
		}
		return $archivo;
	}
	/***************************************/
	public function dame_tipo_operacion($propOperacion)
	{
		switch($propOperacion) {
			case 1: // ofrece servicio
				$txt = "Ofrece Servicio";
				break;
			case 2: // busca servicio
				$txt = "Busca Servicio";
				break;
		}
		return $txt;
	}
	/***************************************/
	public function url_absoluta( $url )
	{
		// cuantas veces encuentra http en string
		$tag = substr_count( $url, "http:"); ;

		// Si tag es menor de 1 es porque no viene absoluta, retorno false
		if ($tag < 1)
		return FALSE;
		else // es url absoluta
		return TRUE;
	}
	/***************************************/
	public function valida_origen( $referer, $string )
	{
		$pos = stripos( $referer, $string);

		// se usan 3 === para que si resultado es 0 no lo asuma como false.
		if ($pos === false)
		return false;
		else
		return true;
	}
	/***************************************/
	public function dame_archivo($idAviso, $idTipoArchivo)
	{
		$res = $this->qry("select ubicacion from archivosAdjuntos where idAviso = '$idAviso' and idTipoArchivo = '$idTipoArchivo' limit 1");
		$row = mysql_fetch_array($res);
		if ($row["ubicacion"] == "")
			return 0;
		else
			return $row["ubicacion"];
	}
	/***************************************/
/*	public function dame_region($idComuna)
	{
		$res = $this->qry("select comIdRegion from ubicComunas where idComuna = '$idComuna' limit 1");
		$row = mysql_fetch_array($res);
		return $row["comIdRegion"];
	}
	/***************************************/
	public function dame_tipo_publicador($idAviso)
	{
		$res = $this->qry("select idEmpresa from avisos where idAviso = '$idAviso' limit 1");
		$row = mysql_fetch_array($res);
		if ($row["idEmpresa"] == "0")
			return "Aviso de Particular";
		else
			return "Aviso de Empresa";
	}
	/***************************************/
	public function nombre_corto_region($id)
	{
		$region="";
		switch($id)
		{
			case 1: $region="I"; break;
			case 2: $region="II"; break;
			case 3: $region="III"; break;
			case 4: $region="IV"; break;
			case 5: $region="V"; break;
			case 6: $region="VI"; break;
			case 7: $region="VII"; break;
			case 8: $region="VIII"; break;
			case 9: $region="IX"; break;
			case 10: $region="X"; break;
			case 11: $region="XI"; break;
			case 12: $region="XII"; break;
			case 13: $region="R.M."; break;
			case 14: $region="XIV"; break;
			case 15: $region="XV"; break;
		}
		return $region;
	}
	/***************************************/
	public function comuna_a_id($comuna)
	{
		switch($comuna)
		{
			case "general lagos": $idc="1";	 break;
			case "putre": $idc="2";	 break;
			case "arica": $idc="3";	 break;
			case "camarones": $idc="4";	 break;
			case "huara": $idc="5";	 break;
			case "camina": $idc="6";	 break;
			case "colchane": $idc="7";	 break;
			case "iquique": $idc="8";	 break;
			case "pozo almonte": $idc="9";	 break;
			case "pica": $idc="10";	 break;
			case "tocopilla": $idc="11";	 break;
			case "maria elena": $idc="12";	 break;
			case "calama": $idc="13";	 break;
			case "ollagüe": $idc="14";	 break;
			case "san pedro de atacama": $idc="15";	 break;
			case "mejillones": $idc="16";	 break;
			case "sierra gorda": $idc="17";	 break;
			case "antofagasta": $idc="18";	 break;
			case "taltal": $idc="19";	 break;
			case "chanaral": $idc="20";	 break;
			case "diego de almagro": $idc="21";	 break;
			case "caldera": $idc="22";	 break;
			case "copiapo": $idc="23";	 break;
			case "tierra amarilla": $idc="24";	 break;
			case "huasco": $idc="25";	 break;
			case "vallenar": $idc="26";	 break;
			case "freirina": $idc="27";	 break;
			case "alto del carmen": $idc="28";	 break;
			case "la higuera": $idc="29";	 break;
			case "vicuna": $idc="30";	 break;
			case "la serena": $idc="31";	 break;
			case "coquimbo": $idc="32";	 break;
			case "andacollo": $idc="33";	 break;
			case "paiguano": $idc="34";	 break;
			case "ovalle": $idc="35";	 break;
			case "rio hurtado": $idc="36";	 break;
			case "punitaqui": $idc="37";	 break;
			case "monte patria": $idc="38";	 break;
			case "combarbala": $idc="39";	 break;
			case "canela": $idc="40";	 break;
			case "illapel": $idc="41";	 break;
			case "los vilos": $idc="42";	 break;
			case "salamanca": $idc="43";	 break;
			case "algarrobo": $idc="44";	 break;
			case "el quisco": $idc="45";	 break;
			case "el tabo": $idc="46";	 break;
			case "cartagena": $idc="47";	 break;
			case "san antonio": $idc="48";	 break;
			case "santo domingo": $idc="49";	 break;
			case "isla de pascua": $idc="50";	 break;
			case "petorca": $idc="51";	 break;
			case "la ligua": $idc="52";	 break;
			case "cabildo": $idc="53";	 break;
			case "papudo": $idc="54";	 break;
			case "zapallar": $idc="55";	 break;
			case "putaendo": $idc="56";	 break;
			case "catemu": $idc="57";	 break;
			case "san felipe": $idc="58";	 break;
			case "santa maria": $idc="59";	 break;
			case "panquehue": $idc="60";	 break;
			case "llaillay": $idc="61";	 break;
			case "nogales": $idc="62";	 break;
			case "calera": $idc="63";	 break;
			case "la cruz": $idc="64";	 break;
			case "quillota": $idc="65";	 break;
			case "hijuelas": $idc="66";	 break;
			case "limache": $idc="67";	 break;
			case "olmue": $idc="68";	 break;
			case "san esteban": $idc="69";	 break;
			case "rinconada": $idc="70";	 break;
			case "calle larga": $idc="71";	 break;
			case "los andes": $idc="72";	 break;
			case "puchuncavi": $idc="73";	 break;
			case "juan fernandez": $idc="74";	 break;
			case "quintero": $idc="75";	 break;
			case "vina del mar": $idc="76";	 break;
			case "villa alemana": $idc="77";	 break;
			case "valparaiso": $idc="78";	 break;
			case "quilpue": $idc="79";	 break;
			case "casablanca": $idc="80";	 break;
			case "concon": $idc="81";	 break;
			case "mostazal": $idc="82";	 break;
			case "graneros": $idc="83";	 break;
			case "codegua": $idc="84";	 break;
			case "rancagua": $idc="85";	 break;
			case "machali": $idc="86";	 break;
			case "las cabras": $idc="87";	 break;
			case "coltauco": $idc="88";	 break;
			case "donihue": $idc="89";	 break;
			case "olivar": $idc="90";	 break;
			case "coinco": $idc="91";	 break;
			case "requinoa": $idc="92";	 break;
			case "peumo": $idc="93";	 break;
			case "quinta de tilcoco": $idc="94";	 break;
			case "pichidegua": $idc="95";	 break;
			case "san vicente": $idc="96";	 break;
			case "malloa": $idc="97";	 break;
			case "rengo": $idc="98";	 break;
			case "navidad": $idc="99";	 break;
			case "litueche": $idc="100";	 break;
			case "la estrella": $idc="101";	 break;
			case "pichilemu": $idc="102";	 break;
			case "marchihue": $idc="103";	 break;
			case "paredones": $idc="104";	 break;
			case "peralillo": $idc="105";	 break;
			case "palmilla": $idc="106";	 break;
			case "san fernando": $idc="107";	 break;
			case "pumanque": $idc="108";	 break;
			case "santa cruz": $idc="109";	 break;
			case "nancagua": $idc="110";	 break;
			case "placilla": $idc="111";	 break;
			case "lolol": $idc="112";	 break;
			case "chepica": $idc="113";	 break;
			case "chimbarongo": $idc="114";	 break;
			case "san javier": $idc="115";	 break;
			case "villa alegre": $idc="116";	 break;
			case "yerbas buenas": $idc="117";	 break;
			case "colbun": $idc="118";	 break;
			case "linares": $idc="119";	 break;
			case "retiro": $idc="120";	 break;
			case "longavi": $idc="121";	 break;
			case "parral": $idc="122";	 break;
			case "teno": $idc="123";	 break;
			case "vichuquen": $idc="124";	 break;
			case "hualane": $idc="125";	 break;
			case "rauco": $idc="126";	 break;
			case "curico": $idc="127";	 break;
			case "romeral": $idc="128";	 break;
			case "licanten": $idc="129";	 break;
			case "sagrada familia": $idc="130";	 break;
			case "molina": $idc="131";	 break;
			case "chanco": $idc="132";	 break;
			case "cauquenes": $idc="133";	 break;
			case "pelluhue": $idc="134";	 break;
			case "curepto": $idc="135";	 break;
			case "rio claro": $idc="136";	 break;
			case "constitucion": $idc="137";	 break;
			case "pencahue": $idc="138";	 break;
			case "talca": $idc="139";	 break;
			case "pelarco": $idc="140";	 break;
			case "san clemente": $idc="141";	 break;
			case "maule": $idc="142";	 break;
			case "empedrado": $idc="143";	 break;
			case "san rafael": $idc="144";	 break;
			case "arauco": $idc="145";	 break;
			case "curanilahue": $idc="146";	 break;
			case "lebu": $idc="147";	 break;
			case "los Álamos": $idc="148";	 break;
			case "canete": $idc="149";	 break;
			case "contulmo": $idc="150";	 break;
			case "tirua": $idc="151";	 break;
			case "cobquecura": $idc="152";	 break;
			case "quirihue": $idc="153";	 break;
			case "ninhue": $idc="154";	 break;
			case "san carlos": $idc="155";	 break;
			case "Ñiquen": $idc="156";	 break;
			case "san fabian": $idc="157";	 break;
			case "san nicolas": $idc="158";	 break;
			case "treguaco": $idc="159";	 break;
			case "portezuelo": $idc="160";	 break;
			case "chillan": $idc="161";	 break;
			case "coihueco": $idc="162";	 break;
			case "coelemu": $idc="163";	 break;
			case "ranquil": $idc="164";	 break;
			case "pinto": $idc="165";	 break;
			case "quillon": $idc="166";	 break;
			case "bulnes": $idc="167";	 break;
			case "san ignacio": $idc="168";	 break;
			case "el carmen": $idc="169";	 break;
			case "pemuco": $idc="170";	 break;
			case "yungay": $idc="171";	 break;
			case "chillan viejo": $idc="172";	 break;
			case "san rosendo": $idc="173";	 break;
			case "yumbel": $idc="174";	 break;
			case "cabrero": $idc="175";	 break;
			case "laja": $idc="176";	 break;
			case "los Ángeles": $idc="177";	 break;
			case "tucapel": $idc="178";	 break;
			case "antuco": $idc="179";	 break;
			case "quilleco": $idc="180";	 break;
			case "nacimiento": $idc="181";	 break;
			case "negrete": $idc="182";	 break;
			case "mulchen": $idc="183";	 break;
			case "santa barbara": $idc="184";	 break;
			case "quilaco": $idc="185";	 break;
			case "tome": $idc="186";	 break;
			case "talcahuano": $idc="187";	 break;
			case "penco": $idc="188";	 break;
			case "florida": $idc="189";	 break;
			case "concepcion": $idc="190";	 break;
			case "coronel": $idc="191";	 break;
			case "hualqui": $idc="192";	 break;
			case "lota": $idc="193";	 break;
			case "santa juana": $idc="194";	 break;
			case "san pedro de la paz": $idc="195";	 break;
			case "chiguayante": $idc="196";	 break;
			case "angol": $idc="197";	 break;
			case "renaico": $idc="198";	 break;
			case "collipulli": $idc="199";	 break;
			case "puren": $idc="200";	 break;
			case "los sauces": $idc="201";	 break;
			case "ercilla": $idc="202";	 break;
			case "lonquimay": $idc="203";	 break;
			case "lumaco": $idc="204";	 break;
			case "traiguen": $idc="205";	 break;
			case "victoria": $idc="206";	 break;
			case "curacautin": $idc="207";	 break;
			case "galvarino": $idc="208";	 break;
			case "perquenco": $idc="209";	 break;
			case "carahue": $idc="210";	 break;
			case "nueva imperial": $idc="211";	 break;
			case "temuco": $idc="212";	 break;
			case "lautaro": $idc="213";	 break;
			case "vilcun": $idc="214";	 break;
			case "melipeuco": $idc="215";	 break;
			case "saavedra": $idc="216";	 break;
			case "teodoro schmidt": $idc="217";	 break;
			case "freire": $idc="218";	 break;
			case "cunco": $idc="219";	 break;
			case "tolten": $idc="220";	 break;
			case "pitrufquen": $idc="221";	 break;
			case "gorbea": $idc="222";	 break;
			case "loncoche": $idc="223";	 break;
			case "villarrica": $idc="224";	 break;
			case "pucon": $idc="225";	 break;
			case "curarrehue": $idc="226";	 break;
			case "padre las casas": $idc="227";	 break;
			case "mariquina": $idc="228";	 break;
			case "lanco": $idc="229";	 break;
			case "panguipulli": $idc="230";	 break;
			case "mafil": $idc="231";	 break;
			case "valdivia": $idc="232";	 break;
			case "los lagos": $idc="233";	 break;
			case "corral": $idc="234";	 break;
			case "paillaco": $idc="235";	 break;
			case "futrono": $idc="236";	 break;
			case "la union": $idc="237";	 break;
			case "lago ranco": $idc="238";	 break;
			case "rio bueno": $idc="239";	 break;
			case "san juan de la costa": $idc="240";	 break;
			case "san pablo": $idc="241";	 break;
			case "osorno": $idc="242";	 break;
			case "puyehue": $idc="243";	 break;
			case "rio negro": $idc="244";	 break;
			case "puerto octay": $idc="245";	 break;
			case "purranque": $idc="246";	 break;
			case "fresia": $idc="247";	 break;
			case "frutillar": $idc="248";	 break;
			case "puerto varas": $idc="249";	 break;
			case "llanquihue": $idc="250";	 break;
			case "los muermos": $idc="251";	 break;
			case "puerto montt": $idc="252";	 break;
			case "cochamo": $idc="253";	 break;
			case "maullin": $idc="254";	 break;
			case "calbuco": $idc="255";	 break;
			case "ancud": $idc="256";	 break;
			case "quemchi": $idc="257";	 break;
			case "dalcahue": $idc="258";	 break;
			case "castro": $idc="259";	 break;
			case "curaco de velez": $idc="260";	 break;
			case "quinchao": $idc="261";	 break;
			case "chonchi": $idc="262";	 break;
			case "puqueldon": $idc="263";	 break;
			case "queilen": $idc="264";	 break;
			case "quellon": $idc="265";	 break;
			case "hualaihue": $idc="266";	 break;
			case "chaiten": $idc="267";	 break;
			case "futaleufu": $idc="268";	 break;
			case "palena": $idc="269";	 break;
			case "guaitecas": $idc="270";	 break;
			case "cisnes": $idc="271";	 break;
			case "aisen": $idc="272";	 break;
			case "lago verde": $idc="273";	 break;
			case "coihaique": $idc="274";	 break;
			case "rio ibanez": $idc="275";	 break;
			case "chile chico": $idc="276";	 break;
			case "cochrane": $idc="277";	 break;
			case "tortel": $idc="278";	 break;
			case "o`higgins": $idc="279";	 break;
			case "natales": $idc="280";	 break;
			case "torres del paine": $idc="281";	 break;
			case "laguna blanca": $idc="282";	 break;
			case "san gregorio": $idc="283";	 break;
			case "rio verde": $idc="284";	 break;
			case "punta arenas": $idc="285";	 break;
			case "primavera": $idc="286";	 break;
			case "porvenir": $idc="287";	 break;
			case "timaukel": $idc="288";	 break;
			case "cabo de hornos": $idc="289";	 break;
			case "antartica": $idc="290";	 break;
			case "tiltil": $idc="291";	 break;
			case "colina": $idc="292";	 break;
			case "lampa": $idc="293";	 break;
			case "san jose de maipo": $idc="294";	 break;
			case "puente alto": $idc="295";	 break;
			case "pirque": $idc="296";	 break;
			case "curacavi": $idc="297";	 break;
			case "maria pinto": $idc="298";	 break;
			case "melipilla": $idc="299";	 break;
			case "san pedro": $idc="300";	 break;
			case "alhue": $idc="301";	 break;
			case "penaflor": $idc="302";	 break;
			case "el monte": $idc="303";	 break;
			case "talagante": $idc="304";	 break;
			case "isla de maipo": $idc="305";	 break;
			case "padre hurtado": $idc="306";	 break;
			case "calera de tango": $idc="307";	 break;
			case "san bernardo": $idc="308";	 break;
			case "buin": $idc="309";	 break;
			case "paine": $idc="310";	 break;
			case "lo barnechea": $idc="311";	 break;
			case "vitacura": $idc="312";	 break;
			case "las condes": $idc="313";	 break;
			case "la reina": $idc="314";	 break;
			case "penalolen": $idc="315";	 break;
			case "la florida": $idc="316";	 break;
			case "pudahuel": $idc="317";	 break;
			case "cerro navia": $idc="318";	 break;
			case "lo prado": $idc="319";	 break;
			case "maipu": $idc="320";	 break;
			case "cerrillos": $idc="321";	 break;
			case "renca": $idc="322";	 break;
			case "quilicura": $idc="323";	 break;
			case "independencia": $idc="324";	 break;
			case "recoleta": $idc="325";	 break;
			case "conchali": $idc="326";	 break;
			case "huechuraba": $idc="327";	 break;
			case "lo espejo": $idc="328";	 break;
			case "p. aguirre cerda": $idc="329";	 break;
			case "la cisterna": $idc="330";	 break;
			case "san ramon": $idc="331";	 break;
			case "la granja": $idc="332";	 break;
			case "el bosque": $idc="333";	 break;
			case "la pintana": $idc="334";	 break;
			case "san miguel": $idc="335";	 break;
			case "quinta normal": $idc="336";	 break;
			case "providencia": $idc="337";	 break;
			case "estacion central": $idc="338";	 break;
			case "santiago": $idc="339";	 break;
			case "Ñunoa": $idc="340";	 break;
			case "san joaquin ": $idc="341";	 break;
			case "macul": $idc="342";	 break;
			case "alto hospicio": $idc="343";	 break;
			case "no definida": $idc="0";	 break;
			case "miraflores": $idc="344";	 break;
			case "renaca": $idc="345";	 break;
		}
		return $idc;
	}
	/***************************************/
	
	public function vermas($cadena,$L)
	{
		$tmp_cadena="";
		if(strlen($cadena)>$L)
		{
			for($i=0;$i<=$L;$i++)
			{
				if($cadena[$i]==" ")
				{
					$cortar=$i;
				}
			}
			$tmp_cadena=substr($cadena,0,$cortar)."...";
		}else{
			$tmp_cadena = $cadena;
		}
		return $tmp_cadena;
	}
	/***************************************/
	public function valnulo($cadena,$mensaje) /***Retonar mensaje si valor nulo***/
	{
		$str="";
		if($cadena=="")
		$str=$mensaje;
		else
		$str=$cadena;

		return $str;
	}
	/***************************************/
	
	// traduce texto a algo valido para friendly urls
	public function traduceTextoaFriendlyURL($texto)
	{
		$resultado = strtolower($texto);
		$resultado = preg_replace("/[^a-z0-9\s-]/", "", $resultado);
		$resultado = trim(preg_replace("/[\s-]+/", " ", $resultado));
		$resultado = preg_replace("/\s/", "-", $resultado);
		return $resultado;
	}
	/***************************************/
	public function set_data($obj,$data=array())
	{
		if($obj!=null && !is_array($obj))
		{
			if(is_array($data))
			{
				foreach($data as $key =>$value)
				{
					$obj->set_var($key,$value);
				}
			}
		}
		else
		{		
			echo "err=set_data";
		}
	}
	/***************************************/
	
	public function paginador($hoja, $url,$lenPaginador,$numRegistrosTotales,$regXpagina)
	{
		$paginas="";
		$url=$this->attr_page($url,"Q");
		$hojas=ceil($numRegistrosTotales/$regXpagina);	
		$destino=(substr($url,-1)!="&" && substr($url,-1)!="?")?$url."&h=":$url."h=";
		$anterior=(substr($url,-1)!="&" && substr($url,-1)!="?")?$url."&h=".($hoja-1):$url."h=".($hoja-1);
		$siguiente=(substr($url,-1)!="&" && substr($url,-1)!="?")?$url."&h=".($hoja+1):$url."h=".($hoja+1);
		$ultimo=(substr($url,-1)!="&" && substr($url,-1)!="?")?$url."&h=".($hojas-1):$url."h=".($hojas-1);
		$separador=$lenPaginador-($lenPaginador-floor($lenPaginador/2));

		if($hoja>($hojas-1))$hoja=($hojas-1);
		$puntero=$hoja;

		if($numRegistrosTotales>0)
		{
			if($puntero>=$lenPaginador)	{	
				$max=(($puntero+$separador)>=$hojas)? $hojas : ($puntero+$separador)+1;
				$min=$max-$lenPaginador;
				$min=(($max-$min)>$lenPaginador)?$min+($max-$min):($min+1);	
			}	else	{
				$max=(($puntero+$separador)>=$hojas)? $hojas : ($puntero+$separador)+1;
				$max=($max<$lenPaginador)?$lenPaginador:$max;
				$max=($max>$hojas)?$hojas:$max;
				$min=$max-$lenPaginador;
				$min=(($max-$min)>$lenPaginador)?$min+($max-$min):($min+1);	
				$min=($min<=0)?1:$min;
			}
			for($i=$min;$i<=$max;$i++)
			{

				### retroceder ###
				if($hoja>0 && $i==$min)
					$paginas.="<li class=\"LiAnterior\"><a href=\"$url\" class=\"primero\"></a></li><li class=\"LiAnterior\"><a href=\"$anterior\" class=\"anterior\" ></a></li>";

				### paginas ###
				$class=(($hoja+1)==$i)?"actual":"pagina";
				$paginas.="<li><a href=\"$destino".($i-1)."\" class=\"$class\" >$i</a></li>";

				### avanzar ###
				if($hoja<($hojas-1) && $i==$max)
					$paginas.="<li class=\"LiAnterior\"><a href=\"$siguiente\" class=\"siguiente\"></a></li><li class=\"LiAnterior\"><a href=\"$ultimo\" class=\"ultima\"></a></li>";
		
			}
		}
		return $paginas;
	}
	/***************************************/
	public function make_header()
	{
		$header="<!--HEADER--> <div id=\"header\"> </div> <!--HEADER-->
						<!--MENU-->
						<div id=\"mainmenu\">
						<a href=\"".URLHOME."\" class=\"mainboton\">AUTOS</a>
						<a href=\"".URLNOTICIAS."\" class=\"mainboton\">NOTICIAS</a>
						<a href=\"".URLTESTDRIVE."\" class=\"mainboton\">TEST DRIVE</a>
						<a href=\"".URLAUTOS."comparativas.html\" class=\"mainboton\">COMPARATIVAS</a>
						<a href=\"".URLVIDEOS."\" class=\"mainboton\">VIDEOS</a>
						<a href=\"http://rentacar.zoomautomotriz.com/\" target=\"_blank\" class=\"mainboton\">RENTACAR</a>
						</div>
						<!--MENU-->";
		return $header;
	}
	/***************************************/
	protected function showError($error)
	{
		if(DEBUG)
			echo str_replace("<!-- ERROR -->",$error,$this->_ERROR);
	}
	/***************************************/
	public function cerrar($obj,$plantilla)
	{
		if($this==null)
			$obj=$this->crear_template(RUTA_PLANTILLAS);

		$obj->set_var("URLBASE", URLBASE );
		$obj->parse("out", $plantilla, true); 
		$obj->p("out"); 

		if($this->CONX!=null)
		{
			$this->cerrar_conexion($this->CONX);
		}
	}
	/***************************************/
	public function get_footer(){
		
			$footer="<div id=\"elcontenido\">
			<table width=\"985\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			<tr>
				<td><div id=\"footer\">
				<div style=\"margin-left:10px; width: ; float:left\"><a href=\"#\" class=\"footxt1\">INICIO</a></div>
				<div style=\"margin-left:10px; width: ; float:left\"><a href=\"#\" class=\"footxt1\">AUTOS</a></div>
				<div style=\"margin-left:10px; width: ; float:left\"><a href=\"#\" class=\"footxt1\">MOTOS</a></div>
				<div style=\"margin-left:10px; width: ; float:left\"><a href=\"#\" class=\"footxt1\">NÁUTICA</a></div>
				<div style=\"margin-left:10px; width: ; float:left\"><a href=\"#\" class=\"footxt1\">CAMIONES</a></div>
				<div style=\"margin-left:10px; width: ; float:left\"><a href=\"#\" class=\"footxt1\">NOTICIAS</a></div>
				<div style=\"margin-left:10px; width: ; float:left\"><a href=\"#\" class=\"footxt1\">VIDEOS</a></div>
				<div style=\"margin-left:10px; width: ; float:left\"><a href=\"#\" class=\"footxt1\">RENT A CAR</a></div>
				<div style=\"margin-left:10px; width: ; float:left\"><a href=\"#\" class=\"footxt1\">CONSEJOS DE SEGURIDAD</a></div>
				<div style=\"margin-left:10px; width: ; float:left\"><a href=\"#\" class=\"footxt1\">VENDER</a></div>
				<div style=\"margin-left:10px; width: ; float:left\"><a href=\"#\" class=\"footxt1\">MI CUENTA</a></div>
				<div style=\"margin-left:10px; width: ; float:left\"><a href=\"#\" class=\"footxt1\">AYUDA</a></div>
				<div style=\"margin-left:310px; width:280px; float:left; margin-top:12px\">
				<div style=\"width:280px; float:left;\"><a href=\"#\" class=\"footit\">AUTOS Y CAMIONETAS</a></div>
				<div style=\"width: ; float:left; list-style:none; margin-top:8px\">
				<li><a href=\"#\" class=\"footxt2\">Alfa Romeo</a></li>
				<li><a href=\"#\" class=\"footxt2\">Audi</a></li>
				<li><a href=\"#\" class=\"footxt2\">Austin</a></li>
				<li><a href=\"#\" class=\"footxt2\">BMW</a></li>
				<li><a href=\"#\" class=\"footxt2\">Chery</a></li>
				<li><a href=\"#\" class=\"footxt2\">Chevrolet</a></li>
				<li><a href=\"#\" class=\"footxt2\">Chrysler</a></li>
				<li><a href=\"#\" class=\"footxt2\">Citroen</a></li>
				</div>
				<div style=\"width: ; float:left; list-style:none ; margin-left:10px; margin-top:8px\">
				<li><a href=\"#\" class=\"footxt2\">Datsun</a></li>
				<li><a href=\"#\" class=\"footxt2\">Dodge</a></li>
				<li><a href=\"#\" class=\"footxt2\">Fiat</a></li>
				<li><a href=\"#\" class=\"footxt2\">Ford</a></li>
				<li><a href=\"#\" class=\"footxt2\">Honda</a></li>
				<li><a href=\"#\" class=\"footxt2\">Hyundai</a></li>
				<li><a href=\"#\" class=\"footxt2\">Jeep</a></li>
				<li><a href=\"#\" class=\"footxt2\">Kia</a></li>
				</div>
				<div style=\"width: ; float:left; list-style:none ; margin-left:10px; margin-top:8px\">
				<li><a href=\"#\" class=\"footxt2\">Mazda</a></li>
				<li><a href=\"#\" class=\"footxt2\">Mercedes Benz</a></li>
				<li><a href=\"#\" class=\"footxt2\">Mini</a></li>
				<li><a href=\"#\" class=\"footxt2\">Mitsubishi</a></li>
				<li><a href=\"#\" class=\"footxt2\">Nissan</a></li>
				<li><a href=\"#\" class=\"footxt2\">Opel</a></li>
				<li><a href=\"#\" class=\"footxt2\">Peugeot</a></li>
				<li><a href=\"#\" class=\"footxt2\">Renault</a></li>
				</div>
				<div style=\"width: ; float:left; list-style:none ; margin-left:10px; margin-top:8px\">
				<li><a href=\"#\" class=\"footxt2\">Seat</a></li>
				<li><a href=\"#\" class=\"footxt2\">Skoda</a></li>
				<li><a href=\"#\" class=\"footxt2\">Ssangyong</a></li>
				<li><a href=\"#\" class=\"footxt2\">Subaru</a></li>
				<li><a href=\"#\" class=\"footxt2\">Suzuki</a></li>
				<li><a href=\"#\" class=\"footxt2\">Toyota</a></li>
				<li><a href=\"#\" class=\"footxt2\">Volkswagen</a></li>
				<li><a href=\"#\" class=\"footxt2\">Volvo</a></li>
				</div>
				</div>
				<div style=\"margin-left:5px; width:130px; float:left; margin-top:12px\">
				<div style=\"width:130px; float:left\"><a href=\"#\" class=\"footit\">MOTOS</a></div>
				<div style=\"width: ; float:left; list-style:none; margin-top:8px\">
				<li><a href=\"#\" class=\"footxt2\">Calle y Naked</a></li>
				<li><a href=\"#\" class=\"footxt2\">Cross y Enduro</a></li>
				<li><a href=\"#\" class=\"footxt2\">Cuatriciclos y Triciclos</a></li>
				<li><a href=\"#\" class=\"footxt2\">Custom y Choppers</a></li>
				<li><a href=\"#\" class=\"footxt2\">Deportivas</a></li>
				<li><a href=\"#\" class=\"footxt2\">Otros (mini, trial, etc)</a></li>
				<li><a href=\"#\" class=\"footxt2\">Scooters y Ciclomotores</a></li>
				<li><a href=\"#\" class=\"footxt2\">Touring y Trails</a></li>
				</div>
				</div>
				<div style=\"margin-left:5px; width:125px; float:left; margin-top:12px\">
				<div style=\"width:130px; float:left\"><a href=\"#\" class=\"footit\">NAUTICA</a></div>
				<div style=\"width: ; float:left; list-style:none; margin-top:8px\">
				<li><a href=\"#\" class=\"footxt2\">Bote</a></li>
				<li><a href=\"#\" class=\"footxt2\">Cruceros</a></li>
				<li><a href=\"#\" class=\"footxt2\">Embarcaciones a vela</a></li>
				<li><a href=\"#\" class=\"footxt2\">Kayak</a></li>
				<li><a href=\"#\" class=\"footxt2\">Lanchas</a></li>
				<li><a href=\"#\" class=\"footxt2\">Motos de Agua y Jet Ski</a></li>
				<li><a href=\"#\" class=\"footxt2\">Semirrígidos</a></li>
				</div>
				</div>
				<div style=\"margin-left:5px; width:125px; float:left; margin-top:12px\">
				<div style=\"width:130px; float:left\"><a href=\"#\" class=\"footit\">CAMIONES</a></div>
				<div style=\"width: ; float:left; list-style:none; margin-top:8px\">
				<li><a href=\"#\" class=\"footxt2\">Volkswagen</a></li>
				<li><a href=\"#\" class=\"footxt2\">Chevrolet</a></li>
				<li><a href=\"#\" class=\"footxt2\">KIA</a></li>
				<li><a href=\"#\" class=\"footxt2\">Ford</a></li>
				<li><a href=\"#\" class=\"footxt2\">Mercedez Benz</a></li>
				</div>
				</div>
				<div id=\"Botones\">
					<div id=\"Botones1\">
					<ul>
					<li><div id=\"bot01\"><a href=\"http://www.latercera.com/\" target=\"_new\"></a></div></li>
					<li><div id=\"bot02\"><a href=\"http://www.lahora.cl/\" target=\"_new\"></a></div></li>
					<li><div id=\"bot03\"><a href=\"http://www.lacuarta.cl/\" target=\"_new\"></a></div></li>
					<li><div id=\"bot04\"><a href=\"http://diariodeconcepcion.cl/\" target=\"_new\"></a></div></li>
					<li><div id=\"bot05\"><a href=\"http://www.quepasa.cl/\" target=\"_new\"></a></div></li>
					<li><div id=\"bot06\"><a href=\"http://www.paula.cl/\" target=\"_new\"></a></div></li>
					<li><div id=\"bot07\"><a href=\"http://www.duna.cl/web/\" target=\"_new\"></a></div></li>
					<li><div id=\"bot08\"><a href=\"http://www.carolina.cl/onfire/\" target=\"_new\"></a></div></li>
					<li><div id=\"bot09\"><a href=\"http://www.beethovenfm.cl/\" target=\"_new\"></a></div></li>
					<li><div id=\"bot10\"><a href=\"http://www.radiozero.cl/\" target=\"_new\"></a></div></li>
					<li><div id=\"bot11\"><a href=\"http://www.paulafm.cl/\" target=\"_new\"></a></div></li>
					<li><div id=\"bot12\"><a href=\"http://www.radiodisney.cl/\" target=\"_new\"></a></div></li>
					<li><div id=\"bot13\"><a href=\"http://www.zoominmobiliario.com\" target=\"_new\"></a></div></li>
					<li><div id=\"bot14\"><a href=\"http://www.zoomautomotriz.com/\" target=\"_new\"></a></div></li>
					<li><div id=\"bot15\"><a href=\"http://www.laborum.cl/\" target=\"_new\"></a> </div></li>
					<li><div id=\"bot16\"><a href=\"http://www.promoservice.cl/\" target=\"_new\"></a></div></li>
					<li><div id=\"bot17\"><a href=\"http://www.biut.cl/\" target=\"_new\"></a></div></li>
				<!--<li><div id=\"bot18\">  <a href=\"http://www.ciperchile.cl/\" target=\"_new\"></a></div></li> -->
					</ul>
					</div>
				</div>
			</div><div class=\"SubFoot\">
			<div id=\"facebook\">
			<p>S&iacute;guenos Tambi&eacute;n en: </p>
			<a href=\"http://www.facebook.com/zoominmobiliario\" target=\"_blank\">
				<img src=\"http://www.zoominmobiliario.com/img/footer/Face.png\" width=\"23\" height=\"24\" alt=\"Facebook\" />Facebook
			</a>
			</div>
			<div id=\"navegador\">
			<li> <a href=\"http://www.opera.com/download/\" target=\"_new\"><img src=\"http://www.zoominmobiliario.com/img/footer/opera.png\" width=\"19\" height=\"24\" /></a><p><a href=\"http://www.opera.com/download/\" target=\"_new\">Opera</a></p></li>
			<li> <a href=\"http://www.apple.com/es/safari/download/\" target=\"_new\"><img src=\"http://www.zoominmobiliario.com/img/footer/safari.png\" width=\"19\" height=\"24\" /></a><p><a href=\"http://www.apple.com/es/safari/download/\" target=\"_new\">Safari</a></p></li>
			<li> <a href=\"http://www.google.com/chrome?hl=es\" target=\"_new\"><img src=\"http://www.zoominmobiliario.com/img/footer/Chrome.png\" width=\"19\" height=\"24\" /></a><p><a href=\"http://www.google.com/chrome?hl=es\" target=\"_new\">Chrome</a></p></li>
			<li> <a href=\"http://www.mozilla-europe.org/es/firefox/\" target=\"_new\"><img src=\"http://www.zoominmobiliario.com/img/footer/firefox.png\" width=\"19\" height=\"24\" /></a><p><a href=\"http://www.mozilla-europe.org/es/firefox/\" target=\"_new\">Firefox</a></p></li>
			<li> <a href=\"http://www.discover.msn.com/get/download-ie8-optimized-for-bing-and-msn-3/\" target=\"_new\"><img src=\"http://www.zoominmobiliario.com/img/footer/explorer.png\" width=\"19\" height=\"24\" /></a><p><a href=\"http://www.discover.msn.com/get/download-ie8-optimized-for-bing-and-msn-3/\" target=\"_new\">Explorer</a></p></li>
			<li><p> Actualiza tu Navegador:</p></li>
			</div>
			<div  id=\"derechos\">
					ZoomAutomotriz.com. Consorcio Period&iacute;stico de Chile S.A. Todos los Derechos reservados <BR /> 
					Se proh&iacute;be expresamente la reproducci&oacute;n o copia de los contenidos de este sitio sin el expreso consentimiento del Consorcio Period&iacute;stico de Chile.S.A. Powered by TOTAL MARKETING S.A.<br /><br /><br /><br />
			</div>
			</div></td>
			</tr>
			</table>
			</div>";
	
		return $footer;
	}
	/***************************************/
	public static function mysql_clean_string($variable){
	 $palabras=array("select","update","insert","delete","drop table","drop database","alter table","alter database","sleep (","sleep(","grant");
	 $found=false;
	 foreach($palabras as $valor)
	 {
	  if(stripos($variable,$valor)!==false){
		$found=true;
	  }
	 }
	 if($found==true)
	 {
	   if(config::debug)
	   {
		 msgs::alerta("parametros no validos");    
	   }else{
		 self::redirect();  
	   }
	 }else{
	  return $variable; 
	 }
	}
	
	##------------------------------##
	public static function redirect($destino=""){
	 if($destino=="")
	  {
		header("location: ".config::URLBASE);
	  }
	  else{
	   header("location: ".$destino);
	  }
	  
	  exit();
	}
	
	### [FIN FUNCIONES GENERALES] ###
	
	### [INSTANCIAS DE CLASES] ###
	public function crear_template($path)
	{
		$this->TEMPLATE=new Template($path);
		return $this->TEMPLATE;
	}
	/***************************************/
	public function crear_pago()
	{
		$this->PAGO=new BotonPago();
		return $this->PAGO;
	}
	/***************************************/
	public function crea_mail($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $subject, $body)
	{
		$this->EMAIL=new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass,$from, $to,  $subject, $body);
		return $this->EMAIL;
	}
	/***************************************/
	### [FIN INSTANCIAS DE CLASES] ###

}### CIERRE CLASE ###
###
?>