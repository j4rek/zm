<?php
class webpay extends consultas{
	//Definicion de la clase.-
	#Parametros de la clase.-
	private $sql_select;
	private $sql_select_2;
	private $sql_insert;
	private $sql_update;
	private $listar_datos;
	private $listar_datos_2;
	private $resultado;
	private $anno;
	private $mes;
	private $dia;
	private $hora;
	private $minuto;
	private $segundo;
	/*
	 * Metodo constructor de la clase.-
	 */
	function __construct(){
		$this->sql_select 		= NULL;
		$this->sql_select_2		= NULL;
		$this->sql_insert		= NULL;
		$this->sql_update		= NULL;
		$this->listar_datos		= NULL;
		$this->listar_datos_2	= NULL;
		$this->resultado 		= NULL;
		$this->anno 			= date("Y");
		$this->mes 				= date("m");
		$this->dia 				= date("d");
		$this->hora				= date("h");
		$this->minuto 			= date("i");
		$this->segundo 			= date("s");
	}
	#Metodo que guarda los datos de webpay para cierre y validaciones.-
		final public function gurdarDatosWebpay(
		$trs_transaccion,
		$trs_respuesta,
		$trs_orden_compra,
		$trs_id_session,
		$trs_cod_autorizacion,
		$trs_monto,
		$trs_nro_final_tarjeta,
		$trs_fecha_expiracion,
		$trs_fecha_contable,
		$trs_fecha_transaccion,
		$trs_hora_transaccion,
		$trs_id_transaccion,
		$trs_tipo_pago,
		$trs_nro_cuotas,
		$trs_mac,
		$trs_tasa_interes_max
	){
		if(($trs_orden_compra)!="" && $this->validarOrdenCompra($trs_orden_compra) == true){
			$this->sql_insert = "update webpay 
									SET 
									Tbk_Tipo_Transaccion 		= '".$trs_transaccion."',
									Tbk_Respuesta 				= '".$trs_respuesta."', 
									
									
									Tbk_Id_sesion 				= '".$trs_id_session."', 
									Tbk_Codigo_autorizacion 	= '".$trs_cod_autorizacion."', 
									Tbk_Monto 					= '".$trs_monto."',
									Tbk_Final_Numero_Tarjeta 	= '".$trs_nro_final_tarjeta."', 
									Tbk_Fecha_Expiracion 		= '".$trs_fecha_expiracion."', 
									Tbk_Fecha_Contable 			= '".$trs_fecha_contable."', 
									Tbk_Fecha_Transaccion 		= '".$trs_fecha_transaccion."', 
									Tbk_Hora_Transaccion 		= '".$trs_hora_transaccion."',
									Tbk_Id_Transaccion 			= '".$trs_id_transaccion."0001',
									Tbk_Codigo_Transaccion 		= '".$trs_id_transaccion."',  
									Tbk_Tipo_Pago 				= '".$trs_tipo_pago."', 
									Tbk_Numero_Cuotas 			= '".$trs_nro_cuotas."', 
									Tbk_Mac 					= '".$trs_mac."',  
									Tbk_Tasa_Interes_Max 		= '".$trs_tasa_interes_max."',
									FechaCompleta				= now(),
									Estado_webpay				= 'Pendiente'
									where
									Tbk_Orden_compra 			= '".$trs_orden_compra."' limit 1;";
			$this->getEjecuta($this->sql_insert);
			$this->guardarDebugWebpay("LOG DATOS WEBPAY PASO 2 : ", "OCC : ".$trs_orden_compra." ID_TRS : ".$trs_id_transaccion, "log para chekeo de transacciones PASO 2");
		}else{
			return false;
		}
		/*$this->resultado = @mysql_affected_rows();
		if($this->resultado > 0){
			;
		}else{
			;
		}*/
	}
	//final public function gurdarDatosWebpay(
	//	$trs_transaccion,
	//	$trs_respuesta,
	//	$trs_orden_compra,
	//	$trs_id_session,
	//	$trs_cod_autorizacion,
	//	$trs_monto,
	//	$trs_nro_final_tarjeta,
	//	$trs_fecha_expiracion,
	//	$trs_fecha_contable,
	//	$trs_fecha_transaccion,
	//	$trs_hora_transaccion,
	//	$trs_id_transaccion,
	//	$trs_tipo_pago,
	//	$trs_nro_cuotas,
	//	$trs_mac,
	//	$trs_tasa_interes_max
	//){
	//	if(($trs_orden_compra)!="" && $this->validarOrdenCompra($trs_orden_compra) == true){
	//		$this->sql_insert = "INSERT INTO webpay 
	//								SET 
	//								Tbk_Tipo_Transaccion 		= '".$trs_transaccion."',
	//								Tbk_Respuesta 				= '".$trs_respuesta."', 
	//								Tbk_Orden_compra 			= '".$trs_orden_compra."',
	//								Tbk_idAviso 				= '".$trs_id_session."', 
	//								Tbk_Id_sesion 				= '".$trs_id_session."', 
	//								Tbk_Codigo_autorizacion 	= '".$trs_cod_autorizacion."', 
	//								Tbk_Monto 					= '".$trs_monto."',
	//								Tbk_Final_Numero_Tarjeta 	= '".$trs_nro_final_tarjeta."', 
	//								Tbk_Fecha_Expiracion 		= '".$trs_fecha_expiracion."', 
	//								Tbk_Fecha_Contable 			= '".$trs_fecha_contable."', 
	//								Tbk_Fecha_Transaccion 		= '".$trs_fecha_transaccion."', 
	//								Tbk_Hora_Transaccion 		= '".$trs_hora_transaccion."',
	//								Tbk_Id_Transaccion 			= '".$trs_id_transaccion."0001',
	//								Tbk_Codigo_Transaccion 		= '".$trs_id_transaccion."',  
	//								Tbk_Tipo_Pago 				= '".$trs_tipo_pago."', 
	//								Tbk_Numero_Cuotas 			= '".$trs_nro_cuotas."', 
	//								Tbk_Mac 					= '".$trs_mac."',  
	//								Tbk_Tasa_Interes_Max 		= '".$trs_tasa_interes_max."',
	//								FechaCompleta				= now(),
	//								Estado_webpay				= 'Pendiente';";
	//		$this->getEjecuta($this->sql_insert);
	//		$this->guardarDebugWebpay("LOG DATOS WEBPAY PASO 2 : ", "OCC : ".$trs_orden_compra." ID_TRS : ".$trs_id_transaccion, "log para chekeo de transacciones PASO 2");
	//	}else{
	//		return false;
	//	}
	//	/*$this->resultado = @mysql_affected_rows();
	//	if($this->resultado > 0){
	//		;
	//	}else{
	//		;
	//	}*/
	//}
	#Metodo que updatea el estado de la transaccion de webpay.- [Estado: Pendiente, Aprobada, Rechazada].-
	final public function updateDatosWebpay($orden_compra, $id_sesion, $operacion="Rechazada"){
		if(trim($orden_compra)!="" && trim($id_sesion)!=""){
			$this->sql_update = "UPDATE webpay
									SET
									Estado_webpay = '".$operacion."'
								 WHERE
									Tbk_orden_compra = '".$orden_compra."'
									AND Tbk_id_sesion = '".$id_sesion."'
								 LIMIT 1;";
			$this->getEjecuta($this->sql_update);
		}else{
			return false;
		}
	}
	#Metodo que update el id de estado de la tabla de avisos y deja el aviso como publicado (idEstado = 3)
	final public function updateEstadoAviso($id_aviso, $estado=0){
		if(trim($id_aviso)!="" && isset($id_aviso)){
			if(is_numeric($estado) && $estado == 0){$estado_aviso = 3;}else{$estado_aviso = $estado;}
			$this->sql_update = "UPDATE avisos
									SET
									idEstado = '".$estado."'
								 WHERE
									idAviso = '".$id_aviso."'
								 LIMIT 1;";
			$this->getEjecuta($this->sql_update);
		}else{
			return false;
		}
	}
	#Metodo que update el id de estado de la tabla de avisos y deja el aviso como publicado (idEstado = 3)
	final public function updateEstadoLegaltec($id_aviso, $estado=0){
		if(trim($id_aviso)!="" && isset($id_aviso)){
			if(is_numeric($estado) && $estado == 0){$estado_aviso = 3;}else{$estado_aviso = $estado;}
			$this->sql_update = "UPDATE avisos
									SET
									marcaLegaltec = '".$estado."'
								 WHERE
									idAviso = '".$id_aviso."'
								 LIMIT 1;";
			$this->getEjecuta($this->sql_update);
		}else{
			return false;
		}
	}
	#Metodo que update el campo destacado de la tabla de avisos 
	final public function updateDestacadoAviso($id_aviso, $destacado=0){
		if(trim($id_aviso)!="" && isset($id_aviso)){
			//if(is_numeric($estado) && $estado == 0){$estado_aviso = 3;}else{$estado_aviso = $estado;}
			$this->sql_update = "UPDATE avisos
									SET
									destacado = '".$destacado."'
								 WHERE
									idAviso = '".$id_aviso."'
								 LIMIT 1;";
			$this->getEjecuta($this->sql_update);
		}else{
			return false;
		}
	}
	#Metodo que update el campo aDestacar de la tabla de avisos 
	final public function updateaDestacarAviso($id_aviso, $destacar=0){
		if(trim($id_aviso)!="" && isset($id_aviso)){
			//if(is_numeric($estado) && $estado == 0){$estado_aviso = 3;}else{$estado_aviso = $estado;}
			$this->sql_update = "UPDATE avisos
									SET
									aDestacar = '".$destacar."'
								 WHERE
									idAviso = '".$id_aviso."'
								 LIMIT 1;";
			$this->getEjecuta($this->sql_update);
		}else{
			return false;
		}
	}
	#Metodo que permite guaradar un debug de las transacciones al cierre de la misma.
	final public function guardarDebugWebpay($variable, $valor, $comentario){
		if($variable!='' && $valor!=''){
			$this->sql_insert = "INSERT INTO debugWebpay SET Variable = '".$variable."', Valor = '".$valor."', Comentario = '".$comentario."';";
			$this->getEjecuta($this->sql_insert);
		}
	}
	#Metodo que genera la orden de compra.- BUSCO_MI_AUTO
	final public function getOrdenCompra($parametro_unico){
		if(isset($parametro_unico) && is_numeric($parametro_unico) && $parametro_unico != ""){
			return $this->resultado = ("OC_BMA".$parametro_unico."_".($this->anno).($this->mes).($this->dia).($this->hora).($this->minuto).($this->segundo)); 
		}else{
			return $this->resultado = ("OC_BMA_".($this->anno).($this->mes).($this->dia).($this->hora).($this->minuto).($this->segundo)); 
		}
	}
	#Metodo que genera el ID de sesion.-
	final public function getIdSSesion(){
		return $this->resultado = ("SS_BMA_".($this->hora).($this->minuto).($this->segundo).($this->anno).($this->mes).($this->dia)); 
	}
	#Metodo que arma los datos del detalle de pago de webpay.-
	final public function getDatosPagoWebpay($orden_compra){
		try{
			if(isset($orden_compra) && $orden_compra != ""){
				$this->resultado = $this->getComprobantePagoWebpay($orden_compra)."\n".$this->getDetallePagoWebpay($orden_compra);
			}else{
				throw new Exception("La orden de compra viene sin datos!");				
			}
		}catch(Exception $ex){
			$this->resultado = "Error: ".$ex->getMessage()."";
		}
		return $this->resultado;
		
	}
	#-Metodo que retorna el encabezado del comprobante de pago.-
	final public function getComprobantePagoWebpay($orden_compra){
		try{
			if(isset($orden_compra) && $orden_compra != ""){
				$this->sql_select 		= "SELECT * FROM webpay WHERE Tbk_Orden_Compra = '".$orden_compra."' ORDER BY Tbk_Orden_Compra DESC LIMIT 1;";				
				$this->listar_datos 	= $this->getListar($this->sql_select);				
				if(is_array($this->listar_datos)){
					$id_aviso				= $this->listar_datos[0]['Tbk_idAviso'];
					$tbk_orden_compra	 	= $this->listar_datos[0]['Tbk_Orden_Compra'];
					$tbk_id_transaccion		= $this->listar_datos[0]['Tbk_Id_Transaccion'];
					$tbk_monto	 			= number_format($this->listar_datos[0]['Tbk_Monto'], 0, ",", "."); 
					$tbk_final_tarjeta 		= $this->listar_datos[0]['Tbk_Final_numero_Tarjeta']; 
					$tbk_numero_cuotas 		= $this->listar_datos[0]['Tbk_Numero_Cuotas']; 
					$tbk_cod_autorizacion	= $this->listar_datos[0]['Tbk_Codigo_Autorizacion']; 
					$tbk_tipo_pago			= $this->listar_datos[0]['Tbk_Tipo_Pago'];
					$tbk_tipo_cuota			= $this->tipoPagos($tbk_tipo_pago);
					$tbk_medio_pago			= $this->medioDePago($tbk_tipo_pago);
				}else{
					throw new Exception("No existen datos de pago para la orden de compra : ".$orden_compra);
				}
				$this->sql_select_2 	= "SELECT * FROM avisos WHERE idAviso = '".$id_aviso."' ORDER BY idAviso DESC LIMIT 1;";
				$this->listar_datos_2 	= $this->getListar($this->sql_select_2);
				if(is_array($this->listar_datos_2)){
					$RUT_cliente	 		= number_format($this->listar_datos_2[0]['RUT'], 0, ",",".")."-".$this->listar_datos_2[0]['RUTDV'];
					$NOMBRE_cliente			= $this->listar_datos_2[0]['nombres']." ".$this->listar_datos_2[0]['apellidos'];
					$EMAIL_cliente			= $this->listar_datos_2[0]['correo'];					
					$this->resultado		= "<div id='comprobante_pago'>
													<h4>Comprobante de Pago:</h4>
													<ul>
														<li><label>Nombre : </label><span>".$NOMBRE_cliente."</span></li>
														<li><label>RUT : </label><span>".$RUT_cliente."</span></li>
														<li><label>N&uacute;mero de Comprobante : </label><span>".$tbk_id_transaccion."</span></li>
														<li><label>URL Comercio : </label><span style='color:#32ABD8;'>". URL_DOMINIO ."</span></li>
														<li><label>Emisor de Pago : </label><span>Web Pay / Transbank</span></li>
														<li><label>Medio de Pago : </label><span>".$tbk_medio_pago."</span></li> 
														<li><label>Numero de Tarjeta : </label><span>XXXXXXXXXXXX-".$tbk_final_tarjeta."</span></li> 
														<li><label>Cantidad de Cuotas : </label><span>".$tbk_numero_cuotas."</span></li>
														<li><label>Tipo de Cuotas : </label><span>".$tbk_tipo_cuota."</span></li>
														<li><label>Codigo de Autorizaci&oacute;n : </label><span>".$tbk_cod_autorizacion."</span></li>
														<li><label>Orden de Transaccion : </label><span>".$orden_compra."</span></li>
													</ul>
												</div>";
				}else{
					throw new Exception("No existen datos de pago para el cliente : ".$id_aviso);
				}
			}else{
				throw new Exception("La orden de compra viene sin datos!");				
			}
		}catch(Exception $ex){
			$this->resultado = "Error: ".$ex->getMessage()."";
		}
		return $this->resultado;
	}
	#Metodo que retorna el detalle del pago.-
	final public function getDetallePagoWebpay($orden_compra){
		try{
			if(isset($orden_compra) && $orden_compra != ""){
				$this->sql_select 		= "SELECT * FROM webpay WHERE Tbk_Orden_Compra = '".$orden_compra."' ORDER BY Tbk_Orden_Compra DESC LIMIT 1;";
				$this->listar_datos 	= $this->getListar($this->sql_select);
				if(is_array($this->listar_datos)){					
					$tbk_id_aviso		 	= $this->listar_datos[0]['Tbk_idAviso']; 
					$tbk_orden_compra	 	= $this->listar_datos[0]['Tbk_Orden_Compra'];					
					$tbk_monto	 			= number_format($this->listar_datos[0]['Tbk_Monto'], 0, ",", ".");
					$tbk_fecha_completa		= $this->listar_datos[0]['FechaCompleta'];
					$tbk_respuesta			= $this->getRespuestaTransbank($this->listar_datos[0]['Tbk_Respuesta']);
					$this->sql_select_2 	= "SELECT * FROM avisos WHERE idAviso = '".$tbk_id_aviso."' ORDER BY idAviso DESC LIMIT 1;";
					$this->listar_datos_2 	= $this->getListar($this->sql_select_2);
					if(is_array($this->listar_datos_2)){
						$titulo_aviso 		= $this->traduceTextoaFriendlyURL($this->listar_datos_2[0]['oficio']);
					}else{
						throw new Exception("No existen datos de pago para el cliente!");
					}
					if($this->listar_datos[0]['Tbk_Respuesta'] != 0){
						$class_alerta = "alerta";
					}else{
						$class_alerta = NULL;
					}
					$this->resultado		= "<div id='detalle_pago'>
													<h4 style='margin:0px 0px 10px 0px'>Detalle del Pago:</h4>
													<table width='100%' cellspacing='0' cellpadding='0' class='General'>
														<thead>
															<tr>
																<th class='ini'>Servicio</th>
																<th>Monto</th>
																<th>Fecha y Hora Pago</th>
																<th>Tipo TRX</th>
																<th class='fin'>Estado</th>
															</tr>
														</thead>
														<tbody>
															<tr class='center'>
																<td class='pieIni'>
																	<a href='http://www.autoalaventa.cl/aviso.html/id/".$tbk_id_aviso."/".$titulo_aviso."/'>Ver Aviso...</a>
																</td>
																<td>$ ".$tbk_monto."</td>
																<td>".$tbk_fecha_completa."</td>
																<td>Pago</td>
																<td class='".$class_alerta."'>".$tbk_respuesta."</td>
															</tr>
														</tbody>
													</table>
												</div>";					
				}else{
					throw new Exception("No existen datos de pago para la orden de compra : ".$orden_compra);
				}
			}else{
				throw new Exception("La orden de compra viene sin datos!");				
			}
		}catch(Exception $ex){
			$this->resultado = "Error: ".$ex->getMessage()."";
		}
		return $this->resultado;
	}
	#Metodo que entrega los datos de un aviso segun orden de compra
	final public function dameDatosAviso($tbk_orden_compra, $dato){
		$this->sql_select	= "SELECT * FROM webpay WHERE Tbk_Orden_Compra = '".$tbk_orden_compra."' ORDER BY Tbk_Orden_Compra DESC LIMIT 1;";
		$this->listar_datos	= $this->getListar($this->sql_select);
		
		return $this->listar_datos;
	}
	#Metodo que entrega el dato de un aviso segun orden de compra
	final public function dameDatoAviso($tbk_orden_compra, $dato){
		try{
			if(isset($tbk_orden_compra) && $tbk_orden_compra != ""){
				$this->sql_select	= "SELECT * FROM webpay WHERE Tbk_Orden_Compra = '".$tbk_orden_compra."' ORDER BY Tbk_Orden_Compra DESC LIMIT 1;";
				$this->listar_datos	= $this->getListar($this->sql_select);
				if(is_array($this->listar_datos)){					
					$id_aviso = $this->listar_datos[0]['Tbk_idAviso'];
					if(isset($id_aviso) && $id_aviso != "" && isset($dato) && $dato != ""){		
						$this->sql_select	= "SELECT * FROM avisos WHERE idAviso = '".$id_aviso."' LIMIT 1;";
						$this->listar_datos	= $this->getListar($this->sql_select);
						if(is_array($this->listar_datos)){
							$this->resultado = $this->listar_datos[0][$dato];
						}else{
							throw new Exception("No existen datos para el aviso!");
						}
					}else{
						throw new Exception("No se definio un dato a entregar!");		
					}
				}else{
					throw new Exception("No existen codigo de aviso para esta orden de compra!");
				}
			}else{
				throw new Exception("No se definio orden de compra para entregar el dato del aviso!");
			}	
		}catch(Exception $ex){
			$this->resultado = "Error: ".$ex->getMessage()."";
		}
		return $this->resultado;	
	}
	#Metodo que retorna el tipo de pago
	final private function tipoPagos($pago){
		try{
			if(isset($pago) && $pago != ""){				
				switch($pago){
					case 'VN':
					  $this->resultado = "Sin Cuotas";
					  break;
					case 'SI':
					  $this->resultado = "Sin Intereses";
					  break;
					case 'VC':
					  $this->resultado = "Cuotas Comercio";
					  break;
					case 'CI':
					  $this->resultado = "Cuotas Comercio";
					  break;
					case 'VD':
					  $this->resultado = "Red Compra"; //Red Compra o Venta Debito
					  break;  
				}  
			}else{
				throw new Exception("El tipo de pago no fue definido");		
			}
		}catch(Exception $ex){
			$this->resultado = "Error: ".$ex->getMessage()."";
		}
		return $this->resultado;	
	}
	#Metodo que retorna el medio de pago
	final private function medioDePago($pago){
		try{
			if(isset($pago) && $pago != ""){				
				switch($pago){
					case 'VN':
					  $this->resultado = "Tarjeta de Credito";
					  break;
					case 'SI':
					  $this->resultado = "Tarjeta de Credito";
					  break;
					case 'VC':
					  $this->resultado = "Tarjeta de Credito";
					  break;
					case 'CI':
					  $this->resultado = "Tarjeta de Credito";
					  break;
					case 'VD':
					  $this->resultado = "Tarjeta de Debito"; //Red Compra o Venta Debito
					  break;  
				}  
			}else{
				throw new Exception("El medio de pago no esta definido");		
			}
		}catch(Exception $ex){
			$this->resultado = "Error: ".$ex->getMessage()."";
		}
		return $this->resultado;	
	}
	#Metodo Comprobacion de orden de compra
	#Definir tabla donde estara el valor del producto
	final public function getComprobacionOrden($orden_compra){
		try{
			if(isset($orden_compra) && $orden_compra != ""){				
				$this->sql_select 	= "SELECT * FROM webpay WHERE TBK_ORDEN_COMPRA = '".$orden_compra."' ORDER BY TBK_ORDEN_COMPRA DESC;";
				$num_rows 			= $this->getNumRows($this->sql_select);
				$this->resultado	= ($num_rows>1) ? "RECHAZADO" : "ACEPTADO";
			}else{
				throw new Exception("La orden de compra no fue definida! ");		
			}
		}catch(Exception $ex){
			$this->resultado = "Error: ".$ex->getMessage()."";
		}
		return $this->resultado;
	}
	#Metodo que valida una orden de compra existente
	final public function validarOrdenCompra($orden_compra){
		try{
			if(isset($orden_compra) && $orden_compra != ""){				
				$validar 			= $this->getComprobacionOrden($orden_compra); 
				$this->resultado	= ($validar == "ACEPTADO") ? true : false;
			}else{
				throw new Exception("La orden de compra no fue definida!");		
			}
		}catch(Exception $ex){
			$this->resultado = "Error: ".$ex->getMessage()."";
		}
		return $this->resultado;
	}
	#Metodo Comprobacion del monto de la orden de compra
	final public function getComprobacionMonto($orden_compra, $trs_monto){
		try{
			if(isset($orden_compra) && $orden_compra != "" && isset($trs_monto) && $trs_monto != ""){				
				$this->sql_select 	= "SELECT * FROM webpay WHERE TBK_ORDEN_COMPRA ='".$orden_compra."' ORDER BY TBK_ORDEN_COMPRA DESC;";
				$fetch_array 		= $this->getFetchAssoc($this->sql_select);
				$this->resultado	= ($trs_monto!=$fetch_array['Tbk_Monto']) ? "RECHAZADO" : "ACEPTADO";
			}else{
				throw new Exception("La orden de compra y el monto no fueron definidos! ");		
			}
		}catch(Exception $ex){
			$this->resultado = "Error: ".$ex->getMessage()."";
		}
		return $this->resultado;
	}
	#Obtener verificacion de mac por metodo CURL.-
	final public function getDatosCurl($url){
		try{
			if(isset($url) && $url != ""){				
				$user_agent		= "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1";
				$curl_init 		= curl_init();    
				// set user agent
				curl_setopt ($curl_init, CURLOPT_USERAGENT, $user_agent);
				curl_setopt ($curl_init, CURLOPT_URL, $url);
				curl_setopt ($curl_init, CURLOPT_HEADER, 0);
				curl_setopt ($curl_init, CURLOPT_RETURNTRANSFER, TRUE);    
				$resultado_curl		= curl_exec ($curl_init);    
				curl_close ($curl_init);
				$this->resultado 	= trim($resultado_curl);
			}else{
				throw new Exception("La url no fue definida! ");		
			}
		}catch(Exception $ex){
			$this->resultado = "Error: ".$ex->getMessage()."";
		}
		return $this->resultado;
	}
	#Obtener verificacion de mac por URL con metodo file_get_contents.-
	final public function getDatosUrl($url){
        try{
			if(isset($url) && $url != ""){
				$resultado_url		= trim(file_get_contents($url));
				$this->resultado 	= $resultado_url;
			}else{
				throw new Exception("La url no fue definida! ");		
			}
		}catch(Exception $ex){
			$this->resultado = "Error: ".$ex->getMessage()."";
		}
        return $this->resultado;
    }
	#-Metodo que entrega la respuesta que envia Transbank.-
	final public function getRespuestaTransbank($codigo){
		try{
			if(isset($codigo) && $codigo != ""){
				switch($codigo){
					case 0:
						$respuesta = "Transaccion Aprobada";
						break;
					case -1:
						$respuesta = "Rechazo de tx. en B24, No autorizada.";
						break;
					case -2:
						$respuesta = "Transaccion debe reintentarse.";
						break;
					case -3:
						$respuesta = "Error en tx.";
						break;
					case -4:
						$respuesta = "Rechazo de tx. en B24, No autorizada.";
						break;
					case -5:
						$respuesta = "Rechazo por error de tasa.";
						break;
					case -6:
						$respuesta = "Excede cupo maximo mensual.";
						break;
					case -7:
						$respuesta = "Excede limite diario por transaccion.";
						break;
					case -8:
						$respuesta = "Rubro no autorizado.";
						break;
				}
				$this->resultado 	= $respuesta;
			}else{
				throw new Exception("No se definio la respuesta entregada por Transbank! ");		
			}
		}catch(Exception $ex){
			$this->resultado = "Error: ".$ex->getMessage()."";
		}
        return $this->resultado;
	}
	#metodo que valida rut
	public function valida_rut($r,$d){
		$r=strtoupper(ereg_replace('\.|,|-','',$r));
		$sub_rut=substr($r,0,strlen($r));
		$sub_dv=$d;
		$x=2;
		$s=0;
		for( $i=strlen($sub_rut)-1;$i>=0;$i-- ){
			if ( $x >7 ){
				$x=2;
			}
			$s += $sub_rut[$i]*$x;
			$x++;
		}
		$dv=11-($s%11);
		if( $dv==10 ){
			$dv='K';
		}
		if( $dv==11 ){
			$dv='0';
		}		
		if( $dv==$sub_dv ){
			return true;
		}
		else{
			return false;
		}
	}
	#Metodo para FriendlyURL
	public function traduceTextoaFriendlyURL($texto){
		$this->resultado = strtolower($texto);
		$this->resultado = preg_replace("/[^a-z0-9\s-]/", "", $this->resultado);
		$this->resultado = trim(preg_replace("/[\s-]+/", " ", $this->resultado));
		$this->resultado = preg_replace("/\s/", "-", $this->resultado);
		return $this->resultado;
	}
	#Metodo que construye el correo de comprobacion de pago.
	public final function comprobantePagoHTML($orden_compra, $nombre_cliente){
		try{
			if(isset($orden_compra) && $orden_compra != "" && isset($nombre_cliente) && $nombre_cliente != ""){
				$this->sql_select = "SELECT * FROM avisos WHERE idAviso = '".$orden_compra."' LIMIT 1;";
				$datos            = $this->getFetchAssoc($this->sql_select);
				$this->resultado="
				<html>
					<head>
					<title>Contacto autoalaventa.cl</title>
					</head>
					<body style='margin:0px;padding:0px;background-color:#efefef;'>
						<div style='width:488px; height:auto;padding:0px;text-align:center;'>
						<center>
						<div style='width:488px;height:auto;margin: 0px 0px 0px 0px;text-align:center;border: 1px solid #000000;'>
						<div style='width:488px;height:auto;margin:0px auto 0px auto; padding:0px;position:relative;text-align:center;top:0px;'>
						<img src='http://www.autoalaventa.cl/img/mail_header.jpeg' border='0' width='488' height='83' alt='autoalaventa.cl' /></div>
						<div style='background-color:#ffffff;width:488px;margin:0px auto 0px auto;padding:0px;top:0px; position:relative;'>
						<div style='width:400px;margin:0px auto 0px auto;padding:0px;position:relative;text-align:left;'><br />
						<span style='font: normal 12px Tahoma, Arial, Verdana, sans-serif;color:#666; text-align:justify;'>
						<p><strong>&iexcl;Felicitaciones!</strong></p>
						<p>Usted ha finalizado con &eacute;xito el proceso publicaci&oacute;n de su aviso en autoalaventa.cl.</p>
						<p>No olvide revisar peri&oacute;dicamente su casilla de email por contactos a su aviso y visitar nuestro sitio autoalaventa.cl.</p>
						<p>Si lo desea, puede modificar su aviso totalmente gratis, haciendo <a href=\"http://www.autoalaventa.cl/editar.html/ID/".$datos[idAviso]."/CODE/".$datos[contrasena]."\" style=\"text-decoration:none;\">click aqu&iacute;</a>.
						</p>
						</span>
						<span style='font: normal 12px Tahoma, Arial, Verdana, sans-serif;color:#666; text-align:justify;'>
						<br /><br />
						Atentamente,<br /><span style='color:black;'><b>Equipo Autoalaventa.cl</b></span>
						<br />&nbsp;<br />
						</span>
						</div>
						</div>
						<div style='width:488px;height:42px;margin:0px auto 0px auto; padding:0px;position:relative;text-align:center;'>
						<img src='http://www.autoalaventa.cl/img/mail_footer.jpg' border='0' width='488' height='42' alt='autoalaventa.cl' /></div>
						</div></center>
						</div>
					</body>
				</html>";
			}else{
				throw new Exception("Problemas al generar HTML para el comprobante de pago");		
			}
		}catch(Exception $ex){
			$this->resultado = "Error: ".$ex->getMessage()."";
		}
        return $this->resultado;
	}
	/*
	 * Metodo destructor
	 */
	function __destruct(){
		unset($this);
	}	
}
?>