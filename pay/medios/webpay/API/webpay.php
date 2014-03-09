<?php
/*
 * CLASE WEBPAY
 * ============
 *
 * *requiere archivo "lib.php"
 * 
 * Utiliza como base las funciones de la clase "lib". Entre estas la conexion a DB, consultas sql, validaciones, etc.
 * 
 */

require_once("lib.php");
class webpay extends lib{
 
 function __construct(){
  parent::__construct();
 }
 
 ## ----------------------------- ##
 #Metodo que actualiza los datos de webpay para cierre y validaciones.-
 public static function gurdarDatosWebpay($trs_transaccion, $trs_respuesta, $trs_orden_compra, $trs_id_session, $trs_cod_autorizacion, $trs_monto, $trs_nro_final_tarjeta, $trs_fecha_expiracion, $trs_fecha_contable,	$trs_fecha_transaccion,	$trs_hora_transaccion, $trs_id_transaccion,$trs_tipo_pago, $trs_nro_cuotas, $trs_mac, $trs_tasa_interes_max){
		if(($trs_orden_compra)!=""){
			$actualizacion = "update webpay 
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
			self::qry($actualizacion);
            self::guardarDebugWebpay("LOG DATOS WEBPAY PASO 2 : ", "OCC : ".$trs_orden_compra." ID_TRS : ".$trs_id_transaccion, "log para chekeo de transacciones PASO 2");
		}else{
			return false;
		}
 }
 
 ## ----------------------------- ##
 #Metodo que permite guaradar un debug de las transacciones al cierre de la misma.
 public static function guardarDebugWebpay($variable, $valor, $comentario){
     if($variable!='' && $valor!=''){
         $res=self::qry("INSERT INTO debugWebpay SET Variable = '".$variable."', Valor = '".$valor."', Comentario = '".$comentario."';");
     }
 }
 
 ## ----------------------------- ##
 #Metodo que updatea el estado de la transaccion de webpay.- [Estado: Pendiente, Aprobada, Rechazada].-
 public static function updateDatosWebpay($orden_compra, $id_sesion, $operacion="Rechazada"){
     if(trim($orden_compra)!="" && trim($id_sesion)!=""){
         $sqlupdate = "UPDATE webpay
                                 SET
                                 Estado_webpay = '".$operacion."'
                              WHERE
                                 Tbk_orden_compra = '".$orden_compra."'
                                 AND Tbk_id_sesion = '".$id_sesion."'
                              LIMIT 1;";
         self::qry($sqlupdate);
     }else{
         return false;
     }
 }
 
 ## ----------------------------- ##
 #Metodo que update el id de estado de la tabla de avisos y deja el aviso como publicado (idEstado = 3)
 public static function updateEstadoAviso($id_aviso, $estado=0){
     if(trim($id_aviso)!="" && isset($id_aviso)){
         $sqlupdate = "UPDATE avisos
                                 SET
                                 idEstado = '".$estado."'
                              WHERE
                                 idAviso = '".$id_aviso."'
                              LIMIT 1;";
         self::qry($sqlupdate);
     }else{
         return false;
     }
 }
 
 ## ----------------------------- ##
 #Metodo que update el id de estado de la tabla de avisos y deja el aviso como publicado (idEstado = 3)
 public static function updateEstadoLegaltec($id_aviso, $estado=0){
     if(trim($id_aviso)!="" && isset($id_aviso)){
         $sqlupdate = "UPDATE avisos
                                 SET
                                 marcaLegaltec = '".$estado."'
                              WHERE
                                 idAviso = '".$id_aviso."'
                              LIMIT 1;";
         self::qry($sqlupdate);
     }else{
         return false;
     }
 }
 
 ## ----------------------------- ##
 #Metodo que update el campo destacado de la tabla de avisos 
 public static function updateDestacadoAviso($id_aviso, $destacado=0){
     if(trim($id_aviso)!="" && isset($id_aviso)){
         $sqlupdate= "UPDATE avisos
                                 SET
                                 destacado = '".$destacado."'
                              WHERE
                                 idAviso = '".$id_aviso."'
                              LIMIT 1;";
         self::qry($sqlupdate);
     }else{
         return false;
     }
 }
 
 ## ----------------------------- ##
 #Metodo que update el campo aDestacar de la tabla de avisos 
 public static function updateaDestacarAviso($id_aviso, $destacar=0){
     if(trim($id_aviso)!="" && isset($id_aviso)){
         $sqlupdate = "UPDATE avisos
                                 SET
                                 aDestacar = '".$destacar."'
                              WHERE
                                 idAviso = '".$id_aviso."'
                              LIMIT 1;";
         self::qry($sqlupdate);
     }else{
         return false;
     }
 }
 
 ## ----------------------------- ##
 #Metodo que genera la orden de compra.-
 public static function getOrdenCompra($parametro_unico){
     if(isset($parametro_unico) && is_numeric($parametro_unico) && $parametro_unico != ""){
         return "OC_BMA".$parametro_unico."_".date("YmdHis"); 
     }else{
         return "OC_BMA_".date("YmdHis"); 
     }
 }
 
 ## ----------------------------- ##
 #Metodo que genera el ID de sesion.-
 public static function getIdSSesion(){
     return "SS_BMA_".date("HisYmd"); 
 }
 
 ## ----------------------------- ##
 #Metodo que arma los datos del detalle de pago de webpay.-
 public static function getDatosPagoWebpay($orden_compra){
     $resultado="";
     if(isset($orden_compra) && $orden_compra != ""){
         $resultado = self::getComprobantePagoWebpay($orden_compra)."\n".self::getDetallePagoWebpay($orden_compra);
     }     
     return $resultado;
 }
 
 ## ----------------------------- ## 
 #Metodo que entrega los datos de un aviso segun orden de compra
 public static function dameDatosAviso($tbk_orden_compra, $dato){
     $res	= self::qry("SELECT * FROM webpay WHERE Tbk_Orden_Compra = '".$tbk_orden_compra."' ORDER BY Tbk_Orden_Compra DESC LIMIT 1;",0,1);
     return $res;
 }
 
 ## ----------------------------- ##
 #Metodo que entrega el dato de un aviso segun orden de compra
 public static function dameDatoAviso($tbk_orden_compra, $dato){
     $resultado=null;
     if(isset($tbk_orden_compra) && $tbk_orden_compra != ""){
         $res	= self::qry("SELECT * FROM webpay WHERE Tbk_Orden_Compra = '".$tbk_orden_compra."' ORDER BY Tbk_Orden_Compra DESC LIMIT 1;",0,1);
         $id_aviso = $res['Tbk_idAviso'];
         if(isset($id_aviso) && $id_aviso != "" && isset($dato) && $dato != ""){
             $res_2	= self::qry("SELECT * FROM avisos WHERE idAviso = '".$id_aviso."' LIMIT 1;",0,1);
             $resultado = $res_2[$dato];
         }         
     }	

     return $resultado;
 }
 
 ## ----------------------------- ##
 #Metodo que retorna el tipo de pago
 private static function tipoPagos($pago){
     $tpago="";
     if(isset($pago) && $pago != ""){				
         switch($pago){
             case 'VN':
               $tpago = "Sin Cuotas";
               break;
             case 'SI':
               $tpago = "Sin Intereses";
               break;
             case 'VC':
               $tpago = "Cuotas Comercio";
               break;
             case 'CI':
               $tpago = "Cuotas Comercio";
               break;
             case 'VD':
               $tpago = "Red Compra"; //Red Compra o Venta Debito
               break;  
         }  
     }
     return $tpago;	
 }
 
 ## ----------------------------- ##
 #Metodo que retorna el medio de pago
 private static function medioDePago($pago){
     $mpago="";     
     if(isset($pago) && $pago != ""){				
         switch($pago){
             case 'VN':
               $mpago = "Tarjeta de Credito";
               break;
             case 'SI':
               $mpago = "Tarjeta de Credito";
               break;
             case 'VC':
               $mpago = "Tarjeta de Credito";
               break;
             case 'CI':
               $mpago = "Tarjeta de Credito";
               break;
             case 'VD':
               $mpago = "Tarjeta de Debito"; //Red Compra o Venta Debito
               break;  
         }  
     }
     
     return $mpago;
 }
 
 ## ----------------------------- ##
 #Metodo Comprobacion de orden de compra
 #Definir tabla donde estara el valor del producto
 public static function getComprobacionOrden($orden_compra){
     $resultado=null;
     if(isset($orden_compra) && $orden_compra != ""){
         $num_rows 			= self::qry("SELECT count(*)as num FROM webpay WHERE TBK_ORDEN_COMPRA = '".$orden_compra."' ORDER BY TBK_ORDEN_COMPRA DESC;",0,1);
         $resultado	= ($num_rows["num"]>1) ? "RECHAZADO" : "ACEPTADO";
     }     
     return $resultado;
 }
 
 ## ----------------------------- ##
 #Metodo que valida una orden de compra existente
 public static function validarOrdenCompra($orden_compra){
     $resultado=null;
     if(isset($orden_compra) && $orden_compra != ""){				
         $validar 			= self::getComprobacionOrden($orden_compra); 
         $resultado	= ($validar == "ACEPTADO") ? true : false;
     }
     return $resultado;
 }
 
 ## ----------------------------- ##
 #Metodo Comprobacion del monto de la orden de compra
 final public function getComprobacionMonto($orden_compra, $trs_monto){
     $resultado=null;
     if(isset($orden_compra) && $orden_compra != "" && isset($trs_monto) && $trs_monto != ""){				
         $res 		= self::qry("SELECT * FROM webpay WHERE TBK_ORDEN_COMPRA ='".$orden_compra."' ORDER BY TBK_ORDEN_COMPRA DESC;",0,1);
         $resultado	= ($trs_monto!=$res['Tbk_Monto']) ? "RECHAZADO" : "ACEPTADO";
     }
     return $resultado;
 }
 
 ## ----------------------------- ##
 #Obtener verificacion de mac por metodo CURL.-
 public static function getDatosCurl($url){
     $resultado=null;
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
         $resultado 	= trim($resultado_curl);
     }
     return $resultado;
 }
 
 ## ----------------------------- ##
 #Obtener verificacion de mac por URL con metodo file_get_contents.-
 public static function getDatosUrl($url){
     $resultado=null;
     if(isset($url) && $url != ""){
         $resultado_url		= trim(file_get_contents($url));
         $resultado 	= $resultado_url;
     }
     
     return $resultado;
 }
 
 ## ----------------------------- ##
 #Metodo que entrega la respuesta que envia Transbank.-
 public static function getRespuestaTransbank($codigo){
     $resultado=null;
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
         $resultado 	= $respuesta;
     }
     return $resultado;
 }
 
 ## ----------------------------- ##
 #Metodo que construye el correo de comprobacion de pago.
 public static function comprobantePagoHTML($orden_compra, $nombre_cliente){
     $resultado=null;
     if(isset($orden_compra) && $orden_compra != "" && isset($nombre_cliente) && $nombre_cliente != ""){
         
         $datos            = self::qry("SELECT * FROM avisos WHERE idAviso = '".$orden_compra."' LIMIT 1;",0,1);
         $resultado="
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
                 <p>Si lo desea, puede modificar su aviso totalmente gratis, haciendo <a href=\"http://www.autoalaventa.cl/editar.html/ID/".$datos["idAviso"]."/CODE/".$datos["contrasena"]."\" style=\"text-decoration:none;\">click aqu&iacute;</a>.
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
     }
     return $resultado;
 }
 
 ## ----------------------------- ##
 #Metodo que retorna el encabezado del comprobante de pago.-
 public static function getComprobantePagoWebpay($orden_compra){
     $resultado=null;
     if(isset($orden_compra) && $orden_compra != ""){
         
         $res 	= self::qry("SELECT * FROM webpay WHERE Tbk_Orden_Compra = '".$orden_compra."' ORDER BY Tbk_Orden_Compra DESC LIMIT 1;",0,1);				
         
         $id_aviso				= $res['Tbk_idAviso'];
         $tbk_orden_compra	 	= $res['Tbk_Orden_Compra'];
         $tbk_id_transaccion    = $res['Tbk_Id_Transaccion'];
         $tbk_monto	 			= number_format($res['Tbk_Monto'], 0, ",", "."); 
         $tbk_final_tarjeta 	= $res['Tbk_Final_numero_Tarjeta']; 
         $tbk_numero_cuotas 	= $res['Tbk_Numero_Cuotas']; 
         $tbk_cod_autorizacion	= $res['Tbk_Codigo_Autorizacion']; 
         $tbk_tipo_pago			= $res['Tbk_Tipo_Pago'];
         $tbk_tipo_cuota		= self::tipoPagos($tbk_tipo_pago);
         $tbk_medio_pago		= self::medioDePago($tbk_tipo_pago);
         
         $res_2 	= self::qry("SELECT * FROM avisos WHERE idAviso = '".$id_aviso."' ORDER BY idAviso DESC LIMIT 1;",0,1);
         $RUT_cliente	 		= number_format($res_2['RUT'], 0, ",",".")."-".$res_2['RUTDV'];
         $NOMBRE_cliente		= $res_2['nombres']." ".$res_2['apellidos'];
         $EMAIL_cliente			= $res_2['correo'];					
         $resultado		= "<div id='comprobante_pago'>
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
         
     }
     
     return $resultado;
 }
 #Metodo que retorna el detalle del pago.-
 public static function getDetallePagoWebpay($orden_compra){
     $resultado=null;
     if(isset($orden_compra) && $orden_compra != ""){
         $res 	= self::qry("SELECT * FROM webpay WHERE Tbk_Orden_Compra = '".$orden_compra."' ORDER BY Tbk_Orden_Compra DESC LIMIT 1;",0,1);
         $tbk_id_aviso		 	= $res['Tbk_idAviso']; 
         $tbk_orden_compra	 	= $res['Tbk_Orden_Compra'];					
         $tbk_monto	 			= number_format($res['Tbk_Monto'], 0, ",", ".");
         $tbk_fecha_completa	= $res['FechaCompleta'];
         $tbk_respuesta			= self::getRespuestaTransbank($res['Tbk_Respuesta']);
         
         $res_2 = self::qry("SELECT * FROM avisos WHERE idAviso = '".$tbk_id_aviso."' ORDER BY idAviso DESC LIMIT 1;",0,1);
         $titulo_aviso = self::friendlyURL($res_2['oficio']);
         
         if($res['Tbk_Respuesta'] != 0){
             $class_alerta = "alerta";
         }else{
             $class_alerta = NULL;
         }
         $resultado		= "<div id='detalle_pago'>
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
         
     }
     
     return $resultado;
 }
 
}

?>