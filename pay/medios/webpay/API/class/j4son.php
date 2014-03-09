<?php
require_once(RUTA_BASE."class_API.php");
class j4son extends API
{
	##### VARIABLES #####
	private $_impresos=array(array("Webs","0"),array("Impresos","1"));
	private $_publica=array(array("Aviso de Particular","0"),array("Aviso de Empresa","1"));
	private $_destacados=array(array("Destacado","1"),array("No Destacado","0"));
	
	##### FUNCIONES GLOBALES ####
	function __construct()
	{
		$this->CONX=$this->conexion_db();
	}
	/*******************************/
	/************** Aqui las nuevas funciones ****************/
	public function dame_estado_aviso($idEstado)
	{
		$res=$this->qry("select descripcion from estados where idEstado='$idEstado' limit 1;");
		$row=mysql_fetch_array($res);

		return $row["descripcion"];
	}
	/***************************************/
	public function strSiNo($var)
	{
		$ans="";
		if($var) $ans="Si";
		else $ans="No";
		return $ans;
	}
	/***************************************/
	public function iconv($value)
	{
		return iconv("ISO-8859-1","UTF-8",$value);
	}
	/***************************************/
	public function dame_options_impresos($obj,$plantilla,$bloque,$select="")
	{
		if($obj==null)
			$obj=$this->crear_template(RUTA_PLANTILLAS);

		$blck="lst_impresos";
		$obj->set_block($plantilla,$bloque,$blck);
		foreach($this->_impresos as $key =>$value):
			$data=array("value_impreso"=>$value[1],"texto_impreso"=>$value[0],"select"=>($select==$value[1])?"selected=\"selected\"":"");
			$this->set_data($obj,$data);
			$obj->parse($blck,$bloque,true);
		endforeach;
	}
	/***************************************/
	public function dame_options_publica($obj,$plantilla,$bloque,$select="")
	{
		if($obj==null)
			$obj=$this->crear_template(RUTA_PLANTILLAS);

		$blck="lst_publica";
		$obj->set_block($plantilla,$bloque,$blck);
		foreach($this->_publica as $key =>$value):
			$data=array("value_publica"=>$value[1],"texto_publica"=>$value[0],"select"=>($select==$value[1])?"selected=\"selected\"":"");
			$this->set_data($obj,$data);
			$obj->parse($blck,$bloque,true);
		endforeach;
		
	}
	/***************************************/
	public function dame_options_destacados($obj,$plantilla,$bloque,$select="")
	{
		if($obj==null)
			$obj=$this->crear_template(RUTA_PLANTILLAS);

		$blck="lst_destacados";
		$obj->set_block($plantilla,$bloque,$blck);
		foreach($this->_destacados as $key =>$value):
			$data=array("value_destacado"=>$value[1],"texto_destacado"=>$value[0],"select"=>($select==$value[1])?"selected=\"selected\"":"");
			$this->set_data($obj,$data);
			$obj->parse($blck,$bloque,true);
		endforeach;
	}
	/***************************************/
	public function dame_options_tipoAviso($obj,$plantilla,$bloque,$select="")
	{
		if($obj==null)
			$obj=$this->crear_template(RUTA_PLANTILLAS);

		$blck="lst_Tipos";
		$obj->set_block($plantilla,$bloque,$blck);
		$res=$this->qry("select * from tipoAvisos;");
		while($row=mysql_fetch_array($res))
		{
			$data=array("value_tipos"=>$row["idTipoAviso"],"texto_tipos"=>$row["descripcion"],"select"=>($select==$row["idTipoAviso"])?"selected=\"selected\"":"");
			$this->set_data($obj,$data);
			$obj->parse($blck,$bloque,true);
		}
	}
	/***************************************/
	public function dame_options_html_tipoAviso($select="")
	{
		$res=$this->qry("select * from tipoAvisos;");
		while($row=mysql_fetch_array($res))
		{
			$sel=($select==$row["idTipoAviso"])?"selected=\"selected\"":"";
			$options.="<option value=\"".$row["idTipoAviso"]."\" $sel>".$row["descripcion"]."</option>\n";
		}
		
		return $options;
	}
	/***************************************/
	public function dame_options_estadoAviso($obj,$plantilla,$bloque,$select="")
	{
		if($obj==null)
			$obj=$this->crear_template(RUTA_PLANTILLAS);

		$blck="lst_estado";
		$obj->set_block($plantilla,$bloque,$blck);
		$res=$this->qry("select * from estados;");
		while($row=mysql_fetch_array($res))
		{
			$data=array("value_estado"=>$row["idEstado"],"texto_estado"=>$row["descripcion"],"select"=>($select==$row["idEstado"])?"selected=\"selected\"":"");
			$this->set_data($obj,$data);
			$obj->parse($blck,$bloque,true);
		}
	}
	/***************************************/
	public function dame_options_estadoAviso_de_empresas($obj,$plantilla,$bloque,$select="")
	{
		if($obj==null)
			$obj=$this->crear_template(RUTA_PLANTILLAS);

		$blck="lst_estado";
		$obj->set_block($plantilla,$bloque,$blck);
		$res=$this->qry("select * from estados;");
		while($row=mysql_fetch_array($res))
		{
			if($row["idEstado"]==3 || $row["idEstado"]==7){
				$data=array("value_estado"=>$row["idEstado"],"texto_estado"=>$row["descripcion"],"select"=>($select==$row["idEstado"])?"selected=\"selected\"":"");
				$this->set_data($obj,$data);
				$obj->parse($blck,$bloque,true);
			}
		}
	}
	/***************************************/
	public function dame_options_empresas($obj,$plantilla,$bloque,$select="")
	{
		if($obj==null)
			$obj=$this->crear_template(RUTA_PLANTILLAS);

		$blck="lst_empresas";
		$obj->set_block($plantilla,$bloque,$blck);
		$res=$this->qry("select * from empresas;");
		while($row=mysql_fetch_array($res))
		{
			$data=array("value_empresa"=>$row["idEmpresa"],"texto_empresa"=>$row["empresa"],"select"=>($select==$row["idEmpresa"])?"selected=\"selected\"":"");
			$this->set_data($obj,$data);
			$obj->parse($blck,$bloque,true);
		}
	}
	/***************************************/
	public function dame_options_comunas($obj,$plantilla,$bloque,$select="")
	{
		if($obj==null)
			$obj=$this->crear_template(RUTA_PLANTILLAS);

		$blck="lst_comunas";
		$obj->set_block($plantilla,$bloque,$blck);
		$res=$this->qry("select * from comunas order by comuna;");
		while($row=mysql_fetch_array($res))
		{
			$data=array("value_comuna"=>$row["idComuna"],"texto_comuna"=>$this->iconv($row["comuna"]),"select"=>($select==$row["idComuna"])?"selected=\"selected\"":"");
			$this->set_data($obj,$data);
			$obj->parse($blck,$bloque,true);
		}
	}
	/***************************************/
	public function dame_checkboxs_comunasXregion($obj,$plantilla,$bloque,$region,$select=array())
	{
		if($obj==null)
			$obj=$this->crear_template(RUTA_PLANTILLAS);

		if(is_numeric($region) && $region>0)
		{
			if(count($select)==52)
				$obj->set_var("checked_todas_filtro","checked");
			$blck="lst_comunas";
			$obj->set_block($plantilla,$bloque,$blck);
			$res=$this->qry("select * from comunas where idRegion='$region' order by comuna;");
			while($row=mysql_fetch_array($res))
			{
				$data=array("value_comuna"=>$row["idComuna"],"texto_comuna"=>$this->iconv($row["comuna"]),"select"=>(in_array($row["idComuna"],$select))?"checked=\"checked\"":"");
				$this->set_data($obj,$data);
				$obj->parse($blck,$bloque,true);
			}
		}else{
			$this->showError("ERR:comunasXregion(Param:<region>)");
		}
		
	}
	/***************************************/
	public function dame_checkboxs_html_comunasXregion($region,$select=array())
	{
		if(is_numeric($region) && $region>0)
		{
			$res=$this->qry("select * from comunas where idRegion='$region' order by comuna;");
			while($row=mysql_fetch_array($res))
			{
				$sel=(in_array($row["idComuna"],$select))?"checked=\"checked\"":"";
				$options.="<div class=\"optComunas\"><input type=\"checkbox\" value=\"".$row["idComuna"]."\" name=\"comunas_{secuencia}\" class=\"ck_{secuencia}\" $sel>".$this->iconv($row["comuna"])."</div>";
			}
		}else{
			$this->showError("ERR:comunasXregion(Param:<region>)");
		}
		
		return $options;
	}
	/***************************************/
	public function dame_options_ciudades($obj,$plantilla,$bloque,$select="")
	{
		if($obj==null)
			$obj=$this->crear_template(RUTA_PLANTILLAS);

		$blck="lst_ciudad";
		$obj->set_block($plantilla,$bloque,$blck);
		$res=$this->qry("select * from ciudades order by idCiudad;");
		while($row=mysql_fetch_array($res))
		{
			$data=array("value_ciudad"=>$row["idCiudad"],"texto_ciudad"=>$this->iconv($row["ciudad"]),"select"=>($select==$row["idCiudad"])?"selected=\"selected\"":"");
			$this->set_data($obj,$data);
			$obj->parse($blck,$bloque,true);
		}
	}
	/***************************************/
	public function dame_options_html_ciudades($select="")
	{
		$res=$this->qry("select * from ciudades order by idCiudad;");
		while($row=mysql_fetch_array($res))
		{
			$sel=($select==$row["idCiudad"])?"selected=\"selected\"":"";
			$options.="<option value=\"".$row["idCiudad"]."\" $sel>".$this->iconv($row["ciudad"])."</option>\n";
		}

		return $options;
	}
	/***************************************/
	public function dame_options_avisos($obj,$plantilla,$bloque,$select="")
	{
		if($obj==null)
			$obj=$this->crear_template(RUTA_PLANTILLAS);

		$blck="lst_avisos";
		$obj->set_block($plantilla,$bloque,$blck);
		$res=$this->qry("select * from avisos where (idEstado='3' or idEstado='7') and idEmpresa=\"".$_COOKIE["bkofEmpresa"]["idEmp"]."\" ");
		while($row=mysql_fetch_array($res))
		{
			$data=array("value_aviso"=>$row["idAviso"],"texto_aviso"=>$row["oficio"],"select"=>($select==$row["idAviso"])?"selected=\"selected\"":"");
			$this->set_data($obj,$data);
			$obj->parse($blck,$bloque,true);
		}
	}
	/***************************************/
	public function dame_comunas_aviso($id)
	{
		$array=array();
		$res=$this->qry("select idComuna from avisosComunas where idAviso='$id';");
		while($row=mysql_fetch_array($res))
		{	
			array_push($array,$row["idComuna"]);
		}
		return $array;
	}
	/***************************************/
	public function checked($stat)
	{
		$check=null;
		if($stat==1)	
			$check="checked=\"checked\"";
		else
			$check="";

		return $check;
	}
	/***************************************/
	public function dame_fecha_domingo()
	{
		$i=1;
		
		while($i<7)
		{
			$timestamp=time() + ($i * 24 * 60 * 60);
			if(date("D",$timestamp)=="Sun")
			{
				$fecha_domingo=date("Y-m-d",$timestamp);		
			}
			$i++;
		}	

		return $fecha_domingo;
	}
	/***************************************/
	public function dame_nombre_ciudad($id)
	{
		$res=$this->qry("select ciudad from ciudades where idCiudad='$id' limit 1;");
		$row=mysql_fetch_array($res);
	
		return $row["ciudad"];
	}
	/***************************************/
	public function permiso_ingresar_aviso($idEmpresa)
	{
		$res=$this->qry("select count(idAviso)as total from avisos where idEmpresa='$idEmpresa' and idEstado='3' ");
		$row=mysql_fetch_array($res);
		$numero_avisos_pub=$row["total"];
		$res=$this->qry("select b.avisos, a.idPlan from planesContratados a, planes b where b.idPlan=a.idPlan and a.idEmpresa='$idEmpresa' and a.activo='1' and a.fechaTermino>=now() limit 1;");   
		$row=mysql_fetch_array($res);
		$avisos_para_pub=$row["avisos"];
		if($numero_avisos_pub>=$avisos_para_pub)
		{
			return false;
		}
		elseif($numero_avisos_pub<$avisos_para_pub && $avisos_para_pub>=0)
		{
			return true;
		}
	}
	/***************************************/
	public function dame_plan_empresa($idEmpresa)
	{
		$res=$res=$this->qry("select b.avisos as TOTAL, a.activo as ACTIVO, b.descripcion as DESCRIPCION from planesContratados a, planes b where b.idPlan=a.idPlan and a.idEmpresa='$idEmpresa' limit 1;");
		$row=mysql_fetch_array($res);

		return $row;
	}
	/***************************************/
	public function emptyVal($value)
	{
		if(isset($value) && !is_null($value) && $value!="")
			return true;
		else
			return false;
	}
	/***************************************/
	public function nValNulo($value,$noNulo,$nulo)
	{
		$string="";
		if($this->emptyVal($value))
			$string = $noNulo;
		else
			$string = $nulo;

		return $string;
	}
	/***************************************/
	public function getValorTag($tag,$cadena)
	{
		$valor="";
		$tagC=($this->emptyVal($tag))?"</".substr($tag,1):"";
		if($this->emptyVal($cadena))
			if($this->emptyVal($tag))
				$valor=substr($cadena,strrpos($cadena,$tag)+strlen($tag),(strrpos($cadena,$tagC) - (strrpos($cadena,$tag)+strlen($tag))));
			else
				$this->showError("ERR:getValorTag(PARAM:tag)");
		else
			$this->showError("ERR:getValorTag(PARAM:cadena)");
		return $valor;
	}
	/***************************************/
	public function dame_fecha_proxima($dias)
	{
		$fecha="";
		if($this->emptyVal($dias) && is_numeric($dias))
			$fecha=date("Y-m-d 00:00:00", mktime(0, 0, 0, date("m"), date("d")+$dias, date("Y") ));
		else
			$this->showError("ERR:dame_fecha_proxima(PARAM:dias)");

		return $fecha;
	}
	/***************************************/
 	public function GeneraLogg($numero, $texto){
 		
		//se agrega ruta donde se guarda log
		
		$realtime =  $matriz_ini['rutaLog']['ruta']."log_zoom.log"; 
 		$ddf = fopen($realtime,'a');
 		fwrite($ddf,"[".date("r")."]     $numero: $texto \r\n");
 		fclose($ddf);
 	}
 	/***************************************/
	public function getValorFinal($precio,$iva)
	{
		$valor=0;
		$valor=$precio+($precio*($iva/100));

		return $valor;
	}
	/***************************************/
	public function valida_rut($r,$d)
	{
		$r=strtoupper(ereg_replace('\.|,|-','',$r));
		$sub_rut=substr($r,0,strlen($r));
		$sub_dv=$d;
		$x=2;
		$s=0;
		for ( $i=strlen($sub_rut)-1;$i>=0;$i-- )
		{
			if ( $x >7 ){
				$x=2;
			}
			$s += $sub_rut[$i]*$x;
			$x++;
		}
		$dv=11-($s%11);
		if ( $dv==10 )	{
			$dv='K';
		}
		if ( $dv==11 )	{
			$dv='0';
		}
		
		if ( $dv==$sub_dv ){
			return true;
		}
		else	{
			return false;
		}
	}
	/***************************************/
	public function permiso_publicar($rut,$dv)
	{
		#echo "init: $rut";
		if($rut>=50000000)
		{
			$res=$this->qry("select count(idAviso) as total from avisos where RUT='$rut' and RUTDV='$dv' and idEmpresa='0' and idEstado='3';");
			$row=mysql_fetch_array($res);
			if($row["total"]>=5)
			{
				header("location: info.html");	
// 				echo "stop";
				exit();
			}
		}
// 		else
// 		{
// 				echo "menor";
// 				exit();
// 		}
	}
	/***************************************/
	public function dame_datos_plan($ID)
	{
		$resPlan=$this->qry("select * from planes where idPlan='$ID' limit 1;");
		$row=mysql_fetch_array($resPlan);
		
		return $row;
	}
	####################################
	public function dameParametros( $uri ) 
	{
		$array = explode("/",$uri);
		$num = count($array);
		$arreglo_variables = array();

		$base = SUBCARPETA_HOME + 2;
		$i = $base;
		while ($i < $num){
			if (trim($array[$i]) != "")
				$arreglo_variables[ $array[$i] ] = mysql_real_escape_string($array[$i+1]);
			$i = $i + 2;
		}
		return $arreglo_variables;
	}	
	/***************************************/
	public function valida_edicion_aviso($ID,$KEY)
	{
		$resAviso=$this->qry("select * from avisos where idAviso='$ID' limit 1;");
		$row=mysql_fetch_array($resAviso);

		if(!$this->emptyVal($ID) || !$this->emptyVal($row["contrasena"]) || !$this->emptyVal($KEY) || $row["contrasena"]!=$KEY)
		{
				header("location: ".URLAUTOS."?err=478");
				exit();
		}
	}
	/***************************************/
	######## nombre_tipoVehiculo #####################
	public function strVehiculo($id)
	{
		switch($id):
			case "1": 
			$valor="Ambulancia"; 
			break;
			case "2": 
			$valor="Automóvil"; 
			break;
			case "3": 
			$valor="Avión"; 
			break;
			case "4": 
			$valor="Bicicleta"; 
			break;
			case "5": 
			$valor="Bus"; 
			break;
			case "6": 
			$valor="Camión"; 
			break;
			case "7": 
			$valor="Camioneta"; 
			break;
			case "8": 
			$valor="Cargador Frontal"; 
			break;
			case "9": 
			$valor="Carro de Arrastre"; 
			break;
			case "10": 
			$valor="Casa Rodante"; 
			break;
			case "11": 
			$valor="Clasicos o de Coleccion"; 
			break;
			case "12": 
			$valor="Contenedor"; 
			break;
			case "13": 
			$valor="Cuadrimoto"; 
			break;
			case "14": 
			$valor="Excavadora Hidráulica"; 
			break;
			case "15": 
			$valor="Furgón"; 
			break;
			case "16": 
			$valor="Grúa"; 
			break;
			case "17": 
			$valor="Grúa Horquilla"; 
			break;
			case "18": 
			$valor="Grupo Electrógeno"; 
			break;
			case "19": 
			$valor="Maquinaria"; 
			break;
			case "20": 
			$valor="Mini Bus"; 
			break;
			case "21": 
			$valor="Minicargador"; 
			break;
			case "22": 
			$valor="Moto"; 
			break;
			case "23": 
			$valor="Motoniveladora"; 
			break;
			case "24": 
			$valor="Motor Home"; 
			break;
			case "25": 
			$valor="Náutico"; 
			break;
			case "26": 
			$valor="Remolque"; 
			break;
			case "27": 
			$valor="Retroexcavadora"; 
			break;
			case "28": 
			$valor="Semirremolque"; 
			break;
			case "29": 
			$valor="Todo Terreno"; 
			break;
			case "30": 
			$valor="Tractor"; 
			break;
			case "31": 
			$valor="Van"; 
			break;
			default:
				$valor="No definida";
			break;
			endswitch;

	return $valor;
	}
	####################################
######## dame_nombre_marca #####################
	public function strMarca($id)
	{
		switch($id):
			case "2": 
			$valor="BYD"; 
			break;
			case "3": 
			$valor="Chevrolet"; 
			break;
			case "4": 
			$valor="Chrysler"; 
			break;
			case "5": 
			$valor="Dodge"; 
			break;
			case "6": 
			$valor="Honda"; 
			break;
			case "7": 
			$valor="JAC"; 
			break;
			case "8": 
			$valor="Jaguar"; 
			break;
			case "9": 
			$valor="Jeep"; 
			break;
			case "10": 
			$valor="LIFAN"; 
			break;
			case "11": 
			$valor="PORSCHE"; 
			break;
			case "12": 
			$valor="SsangYong"; 
			break;
			case "13": 
			$valor="Subaru"; 
			break;
			case "14": 
			$valor="AUDI"; 
			break;
			case "15": 
			$valor="DFM"; 
			break;
			case "16": 
			$valor="Hyundai"; 
			break;
			case "17": 
			$valor="Mercedes Benz"; 
			break;
			case "18": 
			$valor="MG"; 
			break;
			case "19": 
			$valor="TATA"; 
			break;
			case "20": 
			$valor="VOLVO"; 
			break;
			case "21": 
			$valor="Chery"; 
			break;
			case "22": 
			$valor="Daihatsu"; 
			break;
			case "23": 
			$valor="Jinbei"; 
			break;
			case "24": 
			$valor="Lexus"; 
			break;
			case "25": 
			$valor="Mahindra"; 
			break;
			case "26": 
			$valor="MINI"; 
			break;
			case "27": 
			$valor="Samsung"; 
			break;
			case "28": 
			$valor="Saab"; 
			break;
			case "29": 
			$valor="Skoda"; 
			break;
			case "30": 
			$valor="Suzuki"; 
			break;
			case "31": 
			$valor="Volkswagen"; 
			break;
			case "32": 
			$valor="Zotye"; 
			break;
			case "33": 
			$valor="Changan"; 
			break;
			case "34": 
			$valor="Citroen"; 
			break;
			case "35": 
			$valor="Geely"; 
			break;
			case "36": 
			$valor="Land Rover"; 
			break;
			case "37": 
			$valor="Nissan"; 
			break;
			case "38": 
			$valor="Peugeot"; 
			break;
			case "39": 
			$valor="Aston Martin"; 
			break;
			case "40": 
			$valor="BMW"; 
			break;
			case "41": 
			$valor="Ferrari"; 
			break;
			case "42": 
			$valor="Ford"; 
			break;
			case "43": 
			$valor="Great Wall"; 
			break;
			case "44": 
			$valor="Mazda"; 
			break;
			case "45": 
			$valor="Mitsubishi"; 
			break;
			case "46": 
			$valor="Fiat"; 
			break;
			case "47": 
			$valor="Kia"; 
			break;
			case "48": 
			$valor="MASERATI"; 
			break;
			case "49": 
			$valor="Renault"; 
			break;
			case "50": 
			$valor="Toyota"; 
			break;
			case "51": 
			$valor="ZX Autos"; 
			break;
			case "52": 
			$valor="3-Star"; 
			break;
			case "53": 
			$valor="ABC"; 
			break;
			case "54": 
			$valor="Agrale"; 
			break;
			case "55": 
			$valor="Alba Motors"; 
			break;
			case "56": 
			$valor="Blitz"; 
			break;
			case "57": 
			$valor="Brilliance"; 
			break;
			case "58": 
			$valor="Brilliant"; 
			break;
			case "59": 
			$valor="Derbi"; 
			break;
			case "60": 
			$valor="Deutz Agrale"; 
			break;
			case "61": 
			$valor="Dimex"; 
			break;
			case "62": 
			$valor="Gardella"; 
			break;
			case "63": 
			$valor="Garelli"; 
			break;
			case "64": 
			$valor="Gas Gas"; 
			break;
			case "65": 
			$valor="International"; 
			break;
			case "66": 
			$valor="Isuzu"; 
			break;
			case "67": 
			$valor="Iveco"; 
			break;
			case "68": 
			$valor="Lifeng"; 
			break;
			case "69": 
			$valor="Loncin"; 
			break;
			case "70": 
			$valor="Longstar"; 
			break;
			case "71": 
			$valor="MSK"; 
			break;
			case "72": 
			$valor="MV Agusta"; 
			break;
			case "73": 
			$valor="Orion"; 
			break;
			case "74": 
			$valor="Sanxings"; 
			break;
			case "75": 
			$valor="Sanya"; 
			break;
			case "76": 
			$valor="Scania"; 
			break;
			case "77": 
			$valor="Torito"; 
			break;
			case "78": 
			$valor="Triumph"; 
			break;
			case "79": 
			$valor="TV5"; 
			break;
			case "80": 
			$valor="Ying-Yang"; 
			break;
			case "81": 
			$valor="Yuejin"; 
			break;
			case "82": 
			$valor="Zanella"; 
			break;
			case "83": 
			$valor="Zanello"; 
			break;
			case "84": 
			$valor="Zna"; 
			break;
			default: 
			$valor="No definida";
			break;
			endswitch;
	return $valor;
	}
	####################################
}
?>