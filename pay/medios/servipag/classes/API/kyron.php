<?php
require_once(RUTA_BASE."class_API.php");
class kyron extends API
{
	##### VARIABLES #####

	##### FUNCIONES GLOBALES ####
	function __construct()
	{

	}

	public function dame_empresa($idEmpresa)
	{
		$res = $this->qry("select empresa from empresas where idEmpresa = '$idEmpresa' limit 1", 0);
		$row = mysql_fetch_array($res);

		return $row["empresa"];
	}

	function error($titulo, $mensaje, $url)
	{
		$t = $this->crear_template(RUTA_PLANTILLAS);
		$t->set_file("plantilla","error.html");

		$t->set_var("titulo", $titulo );
		$t->set_var("mensaje", $mensaje );
		$t->set_var("url", $url );

		$this->cerrar($t, "plantilla");
		exit();
	}

	// recibe un arreglo de id's de oficios. Retorna otro arreglo con listado de terminos
	// de busqueda para esos id's.
	function dameTerminosBusqueda ( $arreglo )
	{
		$i = 0;
		$i2 = 0;
		$total = count( $arreglo );

		$terminos = array();
		while ($i < $total){
			if ( trim($arreglo[$i]) != ""){
				$consulta = $this->qry("select termBusqueda from oficios where ". 
								" idOficio = '".$arreglo[$i]."' limit 1");
				$totalEncontrados = mysql_num_rows( $consulta );
				$terminoBusqueda = mysql_fetch_array($consulta);
				if ($totalEncontrados > 0) {
					$terminos[$i2] = trim($terminoBusqueda["termBusqueda"]);
					$i2++;
				} else {
					$terminos[$i2] = trim($arreglo[$i]);
					$i2++;
				}

			}
			$i++;
		}

		return $terminos;
	}

	// retorna arreglo de valores que venian en URI. Los keys del arreglo son los nombres
	// de las variables. Y el valor contenido por ese key, es el valor de la variable como tal.
	function dameParametros( $uri ) 
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

	public function dame_ciudad($idCiudad)
	{
		$res = $this->qry("select b.ciudad from ciudades b where b.idCiudad = '$idCiudad' limit 1", 0);
		$row = mysql_fetch_array($res);

		return $row["ciudad"];
	}


	public function dame_tipoAviso($idTipoAviso)
	{
		switch($idTipoAviso){
			case 1:
				$tipo = "Ofrece Servicio";
				break;
			case 2:
				$tipo = "Busca Servicio";
				break;
			default:
				$tipo = "Sin Definir";
				break;
		}

		return $tipo;
	}



	public function valida_texto_javascript($texto) 
	{
        $texto = str_replace( "á", "�", $texto);
        $texto = str_replace( "é", "�", $texto);
        $texto = str_replace( "�*", "�", $texto);
        $texto = str_replace( "í", "�", $texto);
        $texto = str_replace( "ó", "�", $texto);
        $texto = str_replace( "ú", "�", $texto);
        $texto = str_replace( "ñ", "�", $texto);
        $texto = str_replace( "Ñ", "�", $texto);

        $texto = str_replace( "�", "&#225;", $texto);
        $texto = str_replace( "�", "&#193;", $texto);
        $texto = str_replace( "�", "&#233;", $texto);
        $texto = str_replace( "�", "&#201;", $texto);
        $texto = str_replace( "�", "&#237;", $texto);
        $texto = str_replace( "�", "&#205;", $texto);
        $texto = str_replace( "�", "&#243;", $texto);
        $texto = str_replace( "�", "&#211;", $texto);
        $texto = str_replace( "�", "&#250;", $texto);
        $texto = str_replace( "�", "&#218;", $texto);
        $texto = str_replace( "�", "&#241;", $texto);
        $texto = str_replace( "�", "&#209;", $texto);

        $texto = str_replace ('\$', "$ ", $texto);
		$texto = str_replace ('&quot;', "\"", $texto );
		$texto = str_replace ("�", "'", $texto);
		$texto = str_replace ("`", "'", $texto);
		$texto = str_replace ("�", "&ordm;", $texto);
		$texto=iconv("ISO-8859-1","UTF-8",$texto);
		return $texto;
	}	


}
?>