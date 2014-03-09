<?php
require_once("../../lib/autoload.php");

use integrador\integrador as lib;

##-- validar usuario --##
lib::MISC()->valUsuario(4);

## recepcion de parametros ##
$parametros=lib::MISC()->checkVars($_POST);
$IDP=$parametros["ID"];
#var_dump($_POST);
#echo "<br>";
#var_dump($_FILES);
#echo "<br>";
#exit();
if(count($_FILES)<=0){
	echo "resize@:".$parametros["imgs"][0]."@:fail";
	exit();
}
#lib::MISC()->generaLog("1","iniciando ".$parametros["imgs"][0]);
######## GUARDAR IMAGEN(ES) #####################
foreach ($_FILES["images"]["error"] as $key => $error){
	$ext = explode("." , $_FILES["images"]["name"][$key]);
	$ext=$ext[count($ext)-1];
	if(in_array($ext,lib::CFG(null)->fpermitidos)){ // valida la extension del archivo
		#lib::MISC()->generaLog("2","foreach");
		if($error == UPLOAD_ERR_OK) {
		 #lib::MISC()->generaLog("3","OK - $IDP -.- $nEmp");
		 $lsimgs=lib::DB()->myQuery("select * from propiedades_copy where idPropiedad='$IDP' limit 1;",0,1);
		 $imgs=explode(";",$lsimgs["imagenes"]);
		 $cont=($imgs[0]!="")?($lsimgs["indiceImg"]+1):1;
		 #lib::MISC()->generaLog("total","imgs : ".$imgs[0]."-".$cont);
		 if(count($imgs)<lib::CFG("TOTAL_IMAGENES_PERMITIDAS")){
				$imagen=$_FILES["images"][$key];
				$imagen_userfile  = $_FILES["images"]["tmp_name"][$key];
				$imagen_extension = explode("." , $_FILES["images"]["name"][$key]);
				$imagen_nombre = lib::MISC()->friendlyURL($IDP."-".lib::MISC()->dame_tipoPropiedad($lsimgs["idTipo"])."-".lib::MISC()->dame_nombre_comuna($lsimgs["idComuna"]))."-".$cont.".".$imagen_extension[count($imagen_extension)-1];
			
				#lib::MISC()->generaLog("4","img: ".$_FILES["images"]["tmp_name"][$key]);
				if(lib::IMG()->resizeImage($_FILES["images"]["tmp_name"][$key],$imagen_nombre,lib::CFG("repoImagenesPropiedades")."usuarios/",false)){
					if($lsimgs["imagenes"]!=""){
					 $updImagen=$lsimgs["imagenes"].";".$imagen_nombre;
					 #lib::MISC()->generaLog("n","lista imagen ".$updImagen);
					}else{
					 $updImagen=$imagen_nombre;
					 #lib::MISC()->generaLog("n","lista imagen ".$updImagen);
					}
				 #lib::MISC()->generaLog("5","uploaded");
				 if($res=lib::DB()->myQuery("update propiedades_copy set imagenes='$updImagen',indiceImg='$cont' where idPropiedad='$IDP' limit 1;")){
					 #lib::MISC()->generaLog("6","registrada ".$imagen_nombre);
					 echo "finish@:".$parametros["imgs"][0];
				 }
				}else{ // falla en resize
				 echo "resize@:".$parametros["imgs"][0]."@:resize";
				}
			}else{ // falla en total de imágenes
				echo "resize@:".$parametros["imgs"][0]."@:max";
			}
		}else{ // falla en el archivo (post,peso,etc)
			echo "resize@:".$parametros["imgs"][0]."@:falla";
		}
	}else{
		echo "resize@:".$parametros["imgs"][0]."@:nopermitido";
	}
}
?>