<?php
error_reporting(0);
require_once(RUTA_BASE."class_API.php");
class pelao extends API{
	##### VARIABLES #####
    private $carro_avisos = array();	
	private $ciclos;
	private $idAviso;
	private $aviso;
	private $tipo_aviso;
	private $ciudad;
	private $fecha_aviso;
	private $resultado;
    //Iniciacion de productos
	function __construct($session){
		$this->carro_avisos	= $session;
		$this->idAviso		= "";
		$this->aviso		= "";
		$this->tipo_aviso	= "";
		$this->ciudad		= "";
		$this->fecha_aviso	= "";
		$this->resultado	= "";
	}
	//Agregar aviso al carro
	final public function agregarAviso($id_aviso,$aviso,$tipo_aviso,$ciudad,$fecha_aviso){		
		#Llenar arreglo con valores del aviso
		if(isset($id_aviso)){
			if($this->validarAviso($id_aviso)!=true){
				array_push(
							$this->carro_avisos, array(
														"id"=>$id_aviso,
														"aviso"=>$aviso,
														"tipo_aviso"=>$tipo_aviso,
														"ciudad"=>$ciudad,
														"fecha_aviso"=>$fecha_aviso
														)
							);
				$_SESSION['avisos'] = $this->carro_avisos;
				$this->resultado = 1; #exito
			}else{
				$this->resultado = 2; #ya existe este aviso!
			}
		}else{
			$this->resultado = 3; #problema grave al ingreso.
		}
		return $this->resultado;
	}
	//Mostrar avisos
	final public function mostrarAvisos(){ 
      	$data 				= NULL;
      	$this->carro_avisos	= $_SESSION['avisos'];
      	$this->ciclos 		= max(array_keys($this->carro_avisos));
		for($i=0; $i <= $this->ciclos; $i++){
			foreach($this->carro_avisos[$i] as $key => $value){
				if($key == 'id'){$this->idAviso				= $value;}
		 		if($key == 'aviso'){$this->aviso				= $value;}
				if($key == 'tipo_aviso'){$this->tipo_aviso		= $value;}
				if($key == 'ciudad'){$this->ciudad				= $value;}
				if($key == 'fecha_aviso'){$this->fecha_aviso	= $value;}		 		
		 	}		 	
		 	if($this->carro_avisos[$i]['id']!= null){		 		
		 		$data .= "<div id='listado'>
								<div class='txtgris' style='width:55px; float:left; '>". $this->fecha_aviso ."</div>
								<div style='margin:0px 0px 0px 0px; width:285px; float:left; padding-left:5px'>
									<a href='./aviso.html/id/". $this->idAviso ."/". $this->traduceTextoaFriendlyURL($this->aviso) ."/' class='txtazul'>". $this->aviso ."</a>
								</div>
								<div class='txtgris' style='width:130px; float:left; padding-left:5px'> ". $this->tipo_aviso ." </div>
								<div class='txtgris' style='width:125px; float:left; padding-left:5px'> ". $this->ciudad ." </div>
							</div>
							<div style='width:10px; height:20px; float:left; padding-left: 5px;'>
								<a class='delete' href='./mis_avisos.html?e=". $this->idAviso ."'></a>
							</div>
							<!--BOTON VER AVISO IMPRESO, SOLO ALGUNOS AVISOS-->
							<div id='separador'></div>";
		 	}		 			
		 }
			#echo "<div id='listado'><pre>";
			#var_dump($this->carro_avisos); #o print_r($this->carro_avisos);
			#echo "</pre></div>";
		return $data;     	 
   	}
   	//Elimina un aviso
   	final public function eliminarAviso($codigo){ 
   		$this->ciclos = max(array_keys($this->carro_avisos));
   		for($i=0; $i <= $this->ciclos; $i++){
   			if($this->carro_avisos[$i]["id"]== $codigo){
   				unset($this->carro_avisos[$i]);
      	 		$_SESSION['avisos'] = $this->carro_avisos;   				
   			}
   		}    	 
   	}
   	//Eliminar un aviso por codigo
   	final public function eliminarAvisoBuscado($codigo){
   		$this->ciclos = max(array_keys($this->carro_avisos));
   		for($i=0; $i <= $this->ciclos; $i++){
   			if($this->carro_avisos[$i]["id"]== $codigo){
   				unset($this->carro_avisos[$i]);
      	 		$_SESSION['avisos'] = $this->carro_avisos;
      	 		$this->redirect("./mis_avisos.php");
      	 		exit();   				
   			}
   		}
   	}
   	//Validar si un aviso ya existe
   	final public function validarAviso($codigo){
   		$estado = false;
   		$this->ciclos = max(array_keys($this->carro_avisos));   		   		
   		for($i=0; $i <= $this->ciclos; $i++){
   			if($this->carro_avisos[$i]["id"] == $codigo){
   				$estado = true;
   				break;   				
   			}else{
   				$estado = false;
   			}
   			#echo $this->aviso[$i]["id"]."<br />";
   		}
   		#echo "Estado : ".$estado;
   		return $estado;
   	}
   	//total de avisos en el carro
   	final public function totalElementosCarro(){
   		return count($this->carro_avisos);
   	}
	#metodo que borra todo
	final public function truncateAll($act){
		if(isset($act) && $act == "borrar"){
			unset($_SESSION['avisos']);
			unset($this->carro_avisos);
			session_unset();
			session_destroy();
		}
		return $this->redirect("./mis_avisos.html");
	}
	#metodo que redirecciona
	final public function redirect($url){
		return header("Location: ".$url);
	}
   	//destruccion de objetos
	function __destruct(){
		unset($this);
	}
	/*******************************/
	/************** Aqui las nuevas funciones ****************/
}
?>