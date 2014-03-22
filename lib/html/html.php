<?php
namespace html;

use \db\db as database;
use \misc\misc as misc;
use \config\config as cfg;

class html {
 function __construct(){
  
 }
 
 ##------------------------------##
 public static function header(){
  $header="<header>
      <div id=\"subEmpresas\" class=\"sub\">
        <div>
          <span class=\"icono empresas\">
            <label class=\"btnEmpresas\">Empresas</label>
          </span>
        </div>
        <div>
          <span class=\"icono agregar\">
            <label class=\"btnAgregarEmpresa\">Agregar empresa</label>
          </span>
        </div>
      </div>
      <div id=\"subPropiedades\" class=\"sub\">
        <div>
          <span class=\"icono propsU\">
            <label class=\"btnPropsU\">Propiedades usuarios</label>
          </span>
        </div>
        <div>
          <span class=\"icono propsE\">
            <label class=\"btnPropsE\">Propiedades empresas</label>
          </span>
        </div>
        <div>
          <span class=\"icono agregar\">
            <label class=\"btnAgregarPropiedad\">Agregar propiedad</label>
          </span>
        </div>
      </div>
      <div id=\"subProyectos\" class=\"sub\">
        <div>
          <span class=\"icono proyectos\">
            <label class=\"btnProyectos\">Proyectos</label>
          </span>
        </div>
        <div>
          <span class=\"icono agregar\">
            <label class=\"btnAgregarProyecto\">Agregar proyecto</label>
          </span>
        </div>
      </div>
      <div id=\"subContactos\" class=\"sub\">
        <div>
          <span class=\"icono contactosProyectos\">
            <label class=\"btnContactosProyectos\">Contactos proyectos</label>
          </span>
        </div>
        <div>
          <span class=\"icono contactosEmpresa\">
            <label class=\"btnContactosEmpresa\">Contactos empresa</label>
          </span>
        </div>
        <div>
          <span class=\"icono contactosZoom\">
            <label class=\"btnContactosZoom\">Contactos zoom</label>
          </span>
        </div>
        <div>
          <span class=\"icono cotizaciones\">
            <label class=\"btnCotizaciones\">Cotizaciones</label>
          </span>
        </div>
      </div>
      <div id=\"subUsuarios\" class=\"sub\">
        <div>
          <span class=\"icono usuarios\">
            <label class=\"btnUsuarios\">Usuarios</label>
          </span>
        </div>
        <div>
          <span class=\"icono agregar\">
            <label class=\"btnAgregarUsuario\">Agregar usuario</label>
          </span>
        </div>
      </div>
      <nav>
        <ul class=\"menuElm\" title=\"inicio\"><li class=\"elmIz\"></li><li class=\"elmCtr\"><h4>inicio</h4></li><li class=\"elmDr\"></li></ul><!--
        --><ul class=\"menuElm\" title=\"subEmpresas\"><li class=\"elmIz\"></li><li class=\"elmCtr\"><h4>empresas</h4></li><li class=\"elmDr\"></li></ul><!--
        --><ul class=\"menuElm\" title=\"subPropiedades\"><li class=\"elmIz\"></li><li class=\"elmCtr\"><h4>propiedades</h4></li><li class=\"elmDr\"></li></ul><!--
        --><ul class=\"menuElm\" title=\"subProyectos\"><li class=\"elmIz\"></li><li class=\"elmCtr\"><h4>proyectos</h4></li><li class=\"elmDr\"></li></ul><!--
        --><ul class=\"menuElm\" title=\"subContactos\"><li class=\"elmIz\"></li><li class=\"elmCtr\"><h4>contactos</h4></li><li class=\"elmDr\"></li></ul><!--
        --><ul class=\"menuElm\" title=\"subUsuarios\"><li class=\"elmIz\"></li><li class=\"elmCtr\"><h4>usuarios</h4></li><li class=\"elmDr\"></li></ul>
      </nav>      
    </header>";
  
  return $header;
 }

  ##------------------------------##
 public static function bo_inmo_header(){
  $usuario=misc::dame_datos_usuario($_SESSION["usr"]["id"]);
  
  $_header="<header>
            <nav>
              <ul>
                <a href=''><li>Inicio</li></a>
                <a href='".cfg::DOMADMIN.cfg::IN."proyectos.listado.php'><li>Proyectos</li></a>
                <a href='".cfg::DOMADMIN.cfg::IN."cotizaciones.listado.php'><li>Cotizaciones</li></a>
                <a href='".cfg::DOMADMIN.cfg::IN."contactos.listado.php'><li>Contactos</li></a>
                <li class='jMenu'>Mi Cuenta
                             <div class='items'>
                              <a href='".cfg::URLBASE.cfg::APP_USUARIO."datos.php'><div><img src='".cfg::DOMSTATIC."img/misDatos.png' />Mis datos</div></a>
                              <a href='".cfg::URLBASE.cfg::APP_LOGIN."logout.php'><div><img src='".cfg::DOMSTATIC."img/cerrarSesion.png'/>Cerrar sesión</div></a>
                             </div>
                </li>
              </ul>
            </nav>
            <div class='hcentro'>
              <section class='logo'><img src='".cfg::DOMSTATIC."img/logo.png' /></section>
              <section class='perfil'>
                <section class='info'>
                  <div class='nombre'>".$usuario["nombre"]."</div>
                  <div class='correo'>".$usuario["correo"]."</div>
                </section>
                <section class='avatar'>
                  <img src='".(($usuario["avatar"]=="")?cfg::DOMSTATIC."img/usuario2.png":$usuario["avatar"])."' />
                </section>
              </section>
              </div>
              <section class='indicadores'>".self::indicadores()."</section>
            </header>";
            
   return $_header;
 }
 
 ##------------------------------##
 public static function bo_corredor_header(){
  $usuario=misc::dame_datos_usuario($_SESSION["usr"]["id"]);
  
  $_header="<header>
            <nav>
              <ul>
                <a href=''><li>Inicio</li></a>
                <a href='".cfg::DOMADMIN.cfg::CO."propiedades.php'><li>Propiedades</li></a>
                <a href='".cfg::DOMADMIN.cfg::CO."publicar.php'><li>Agregar propiedad</li></a>
                <a href='".cfg::DOMADMIN.cfg::CO."contactos.listado.php'><li>Contactos</li></a>
                <li class='jMenu'>Mi Cuenta
                             <div class='items'>
                              <a href='".cfg::URLBASE.cfg::APP_USUARIO."datos.php'><div><img src='".cfg::DOMSTATIC."img/misDatos.png' />Mis datos</div></a>
                              <a href='".cfg::URLBASE.cfg::APP_LOGIN."logout.php'><div><img src='".cfg::DOMSTATIC."img/cerrarSesion.png'/>Cerrar sesión</div></a>
                             </div>
                </li>
              </ul>
            </nav>
            <div class='hcentro'>
              <section class='logo'><img src='".cfg::DOMSTATIC."img/logo.png' /></section>
              <section class='perfil'>
                <section class='info'>
                  <div class='nombre'>".$usuario["nombre"]."</div>
                  <div class='correo'>".$usuario["correo"]."</div>
                </section>
                <section class='avatar'>
                  <img src='".(($usuario["avatar"]=="")?cfg::DOMSTATIC."img/usuario2.png":$usuario["avatar"])."' />
                </section>
              </section>
              </div>
              <section class='indicadores'>".self::indicadores()."</section>
            </header>";
            
   return $_header;
 }
 
 ##------------------------------##
 public static function apps_header(){
  $usuario=misc::dame_datos_usuario($_SESSION["usr"]["id"]);
  
  $_header="<header>
            <nav>
              <ul>
                <li>FB | TW</li>
                <a href=''><li>Inicio</li></a>
                <a href=''><li>Inmobiliarias</li></a>
                <a href=''><li>Corredoras</li></a>
                <a href=''><li>Noticias</li></a>
                <a href='".cfg::URLBASE.cfg::APP_PROPIEDADES."publicar.php'><li>Publica tu Propiedad</li></a>
                <li class='jMenu'>Mi Cuenta
                             <div class='items'>
                              <a href='".cfg::URLBASE.cfg::APP_PROPIEDADES."publicar.php'><div><img src='".cfg::DOMSTATIC."img/publicarPropiedad.png' />Publica tu propiedad</div></a>
                              <a href='".cfg::URLBASE.cfg::APP_PROPIEDADES."propiedades.php'><div><img src='".cfg::DOMSTATIC."img/misPropiedades.png' />Mis propiedades</div></a>
                              <a href='".cfg::URLBASE.cfg::APP_USUARIO."datos.php'><div><img src='".cfg::DOMSTATIC."img/misDatos.png' />Mis datos</div></a>
                              <a href='".cfg::URLBASE.cfg::APP_LOGIN."logout.php'><div><img src='".cfg::DOMSTATIC."img/cerrarSesion.png'/>Cerrar sesión</div></a>
                             </div>
                </li>
              </ul>
            </nav>
            <div class='hcentro'>
              <section class='logo'><img src='".cfg::DOMSTATIC."img/logo.png' /></section>
              <section class='perfil'>
                <section class='info'>
                  <div class='nombre'>".$usuario["nombre"]."</div>
                  <div class='correo'>".$usuario["correo"]."</div>
                </section>
                <section class='avatar'>
                  <img src='".(($usuario["avatar"]=="")?cfg::DOMSTATIC."img/usuario2.png":$usuario["avatar"])."' />
                </section>
              </section>
              </div>
              <section class='indicadores'>".self::indicadores()."</section>
            </header>";
            
   return $_header;
 }
 
 ##------------------------------##
 public static function apps_footer(){
  $_footer="<footer>
    <div class='links'>
      <ul>
        <li><a href=''>Inicio</a></li>
        <li><a href=''>Inmobiliarias</a></li>
        <li><a href=''>Corredoras</a></li>
        <li><a href=''>Noticias</a></li>
        <li><a href=''>Publica tu Propiedad</a></li>
        <li><a href=''>Mi Cuenta</a></li>
      </ul>
    </div>
    <div class='listas'>
      <div class='fcentro'>
        <section>
          Búsqueda de Propiedades
          <ul>
            <li><a href=''>- Nuevas</a></li>
            <li><a href=''>- Usadas</a></li>
            <li><a href=''>- Casas</a></li>
            <li><a href=''>- Departamentos</a></li>
            <li><a href=''>- Oficinas</a></li>
          </ul>
        </section>
        <section>
          Búsqueda Rápida
          <ul>
            <li><a href=''>- Venta de Departamentos en Santiago</a></li>
            <li><a href=''>- Arriendo de Departamentos en Santiago</a></li>
            <li><a href=''>- Venta de Departamentos en Las Condes</a></li>
            <li><a href=''>- Venta de Departamentos en Providencia</a></li>
            <li><a href=''>- Arriendo de Departamentos en Providencia</a></li>
          </ul>
        </section>
        <section>
          Otros
          <ul>
            <li><a href=''>- Acerca de ZoomInmobiliario.com</a></li>
            <li><a href=''>- Aviso legal</a></li>
            <li><a href=''>- Preguntas frecuentes</a></li>
            <li><a href=''>- Mapa del sitio</a></li>
          </ul>
        </section>
        <section class='logo'>
          <img src='".cfg::DOMSTATIC."img/logo_GC.png' class='logo' />
        </section>
      </div>
    </div>
    <div class='copyright'>
      <div class='fcentro'>
        <p><strong>Zoominmobiliario.com</strong> Consorcio Periodístico de Chile S.A. Todos los Derechos reservados</p>
        <p>Se prohíbe expresamente la reproducción o copia de los contenidos de este sitio sin el expreso consentimiento del Consorcio Periodístico de Chile S.A.</p>
      </div>
    </div>
  </footer>";
  
  return $_footer;
 }
 
 ##------------------------------##
 public static function paginador($hoja, $url,$lenPaginador,$numRegistrosTotales,$regXpagina){
     $paginas="";
     #$url=$this->attr_page($url,"Q");
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

             ### retroceder ####
             if($hoja>0 && $i==$min)
                 $paginas.="<li class=\"LiPrimero\"><a href=\"$url\" class=\"primero\"></a></li><li class=\"LiAnterior\"><a href=\"$anterior\" class=\"anterior\" ></a></li>";

             ### paginas ####
             $class=(($hoja+1)==$i)?"actual":"pagina";
             $paginas.="<li><a href=\"$destino".($i-1)."\" class=\"$class\" >$i</a></li>";

             ### avanzar ####
             if($hoja<($hojas-1) && $i==$max)
                 $paginas.="<li class=\"LiSiguiente\"><a href=\"$siguiente\" class=\"siguiente\"></a></li><li class=\"LiUltima\"><a href=\"$ultimo\" class=\"ultima\"></a></li>";
     
         }
     }
     return $paginas;
 }
 
 ##------------------------------##
 public static function regiones($props=array('name'=>"regiones"),$selected=""){
   $propiedades="";
   
   if(is_array($props)){
    foreach($props as $key => $value){
     $propiedades.=$key."='".$value."' ";
    }
   }else{
    $propiedades=$props;
   }
   
   $lst="<select $propiedades>";
   $lst.="<option value='0'>Seleccione</option>";
   $rRegiones=database::myQuery("select * from regiones;");
   while($row=mysql_fetch_array($rRegiones)){
    if($selected==$row["idRegion"]){
     $_sel="selected";
    }else{
     $_sel="";
    }
    
    $lst.="<option value='".$row["idRegion"]."' $_sel>".$row["region"]."</option>";
   }
   $lst.="</select>";
   
   return $lst;
 }
 
 ##------------------------------##
 public static function comunas($props=array('name'=>"comunas"),$region=13,$selected=""){
   $propiedades="";
   
   if(is_array($props)){
    foreach($props as $key => $value){
     $propiedades.=$key."='".$value."' ";
    }
   }else{
    $propiedades=$props;
   }
   
   $lst="<select $propiedades>";
   $lst.="<option value='0'>Seleccione</option>";
   if($region!=0){
    $_where="where idRegion='$region'";
   }
   $rComunas=database::myQuery("select * from comunas $_where order by comuna;");
   while($row=mysql_fetch_array($rComunas)){
    if($selected==$row["idComuna"]){
     $_sel="selected";
    }else{
     $_sel="";
    }
    
    $lst.="<option value='".$row["idComuna"]."' $_sel>".$row["comuna"]."</option>";
   }
   $lst.="</select>";
   
   return $lst;
 }
 
 ##------------------------------##
 public static function ciudades(){
  
 }
 
 ##------------------------------##
 public static function tipoProps($props=array('name'=>"tipoProps"),$selected=""){
   $propiedades="";
   
   if(is_array($props)){
    foreach($props as $key => $value){
     $propiedades.=$key."='".$value."' ";
    }
   }else{
    $propiedades=$props;
   }
   
   $lst="<select $propiedades>";
   $lst.="<option value='0'>Seleccione</option>";
   $rTipos=database::myQuery("select * from propiedadesTipos where idTipo<>14;");
   while($row=mysql_fetch_array($rTipos)){
    if($selected==$row["idTipo"]){
     $_sel="selected";
    }else{
     $_sel="";
    }
    
    $lst.="<option value='".$row["idTipo"]."' $_sel>".$row["tipo"]."</option>";
   }
   $lst.="</select>";
   
   return $lst;
 }
 
 ##------------------------------##
 public static function operaciones($props=array('name'=>"operacion"),$selected=""){
   $propiedades="";
   
   if(is_array($props)){
    foreach($props as $key => $value){
     $propiedades.=$key."='".$value."' ";
    }
   }else{
    $propiedades=$props;
   }
   
   $lst="<select $propiedades>";
   $lst.="<option value='0'>Seleccione</option>";
   $rOperacion=database::myQuery("select * from operaciones where idOperacion>0;");
   while($row=mysql_fetch_array($rOperacion)){
    if($selected==$row["idOperacion"]){
     $_sel="selected";
    }else{
     $_sel="";
    }
    
    $lst.="<option value='".$row["idOperacion"]."' $_sel>".$row["Operacion"]."</option>";
   }
   $lst.="</select>";
   
   return $lst;
 }
 
 ##------------------------------##
 public static function años($props=array('name'=>"anios"),$selected=""){
   $propiedades="";
   
   if(is_array($props)){
    foreach($props as $key => $value){
     $propiedades.=$key."='".$value."' ";
    }
   }else{
    $propiedades=$props;
   }
   
   $lst="<select $propiedades>\n";
   $lst.="<option value='0'>seleccione</option>";
   for($i=date("Y");$i>=(date("Y")-100);$i--){
    if($selected==$i){
     $_sel="selected";
    }else{
     $_sel="";
    }
    
    $lst.="<option value='$i' $_sel>$i</option>";
   }
   $lst.="</select>";
   
   return $lst;
 }
 
 ##------------------------------##
 public static function meses($props=array('name'=>"meses"),$selected=""){
   $propiedades="";
   
   if(is_array($props)){
    foreach($props as $key => $value){
     $propiedades.=$key."='".$value."' ";
    }
   }else{
    $propiedades=$props;
   }
    
   $lst="<select $propiedades>\n";
   $lst.="<option value='0'>Seleccione</option>";
   for($i=1;$i<=12;$i++){
    if($selected==$i){
     $_sel="selected";
    }else{
     $_sel="";
    }
   
    $lst.="<option value='$i' $_sel></option>";
   }
   $lst.="</select>";
   
   return $lst;
 }
 
 ##------------------------------##
 public static function dias($props=array('name'=>"dias"),$mes=1,$selected=""){
   $total=cal_days_in_month(CAL_GREGORIAN, $mes, date("Y"));
   $propiedades="";
   
   if(is_array($props)){
    foreach($props as $key => $value){
     $propiedades.=$key."='".$value."' ";
    }
   }else{
    $propiedades=$props;
   }
   
   $lst="<select $propiedades>\n";
   $lst.="<option value='0'>seleccione</option>";
   for($i=1;$i<=$total;$i++){
    if($selected==$i){
     $_sel="selected";
    }else{
     $_sel="";
    }
    
    $lst.="<option value='$i' $_sel>$i</option>";
   }
   $lst.="</select>";
   
   return $lst;
 }
 
 ##------------------------------##
 public static function empresas($props=array('name'=>"nEmpresas"),$selected=""){
  $propiedades="";
   
   if(is_array($props)){
    foreach($props as $key => $value){
     $propiedades.=$key."='".$value."' ";
    }
   }else{
    $propiedades=$props;
   }
   
   $lst="<select $propiedades>";
   $lst.="<option value='0'>Seleccione</option>";
   $rEmpresas=database::myQuery("select * from empresas where idEstado='7' and idTipo in (1,2) order by nombre;");
   while($row=mysql_fetch_array($rEmpresas)){
    if($selected==$row["idEmpresa"]){
     $_sel="selected";
    }else{
     $_sel="";
    }
    
    $lst.="<option value='".$row["idEmpresa"]."' $_sel>".($row["nombre"])."</option>";
   }
   $lst.="</select>";
   
   return $lst;
 }
 
 ##------------------------------##
 public static function medidas($props=array('name'=>"medidas"),$selected=""){
  $propiedades="";
  
   if(is_array($props)){
    foreach($props as $key => $value){
     $propiedades.=$key."='".$value."' ";
    }
   }else{
    $propiedades=$props;
   }
   
   $lst="<select $propiedades>";
   $lst.="<option value='0'>Seleccione</option>";
   $rMedidas=database::myQuery("select * from medidas;");
   while($row=mysql_fetch_array($rMedidas)){
    if($selected==$row["idMedida"]){
     $_sel="selected";
    }else{
     $_sel="";
    }
    
    $lst.="<option value='".$row["idMedida"]."' $_sel>".($row["descripcion"])."</option>";
   }
   $lst.="</select>";
   
   return $lst;
 }
 
 ##------------------------------##
  public static function divisas($props=array('name'=>"divisas"),$selected=""){
  $propiedades="";
  
   if(is_array($props)){
    foreach($props as $key => $value){
     $propiedades.=$key."='".$value."' ";
    }
   }else{
    $propiedades=$props;
   }
   
   $lst="<select $propiedades>";
   $lst.="<option value='0'>Seleccione</option>";
   $rDivisas=database::myQuery("select * from divisas where idDivisa<>'3';");
   while($row=mysql_fetch_array($rDivisas)){
    if($selected==$row["idDivisa"]){
     $_sel="selected";
    }else{
     $_sel="";
    }
    
    $lst.="<option value='".$row["idDivisa"]."' $_sel>".($row["descripcion"])."</option>";
   }
   $lst.="</select>";
   
   return $lst;
 }
 
 ##------------------------------##
 public static function lstNumerico($props=array('name'=>''),$selected=""){
  $propiedades="";
  
  if(is_array($props)){
   foreach($props as $key => $value){
    $propiedades.=$key."='".$value."' ";
   }
  }else{
   $propiedades=$props;
  }
  
  $lst="<select $propiedades>";
  $lst.="<option value='0'>Seleccione</option>";
  for($i=1;$i<=10;$i++){
   if($selected==$i){
     $_sel="selected";
   }else{
    $_sel="";
   }
   
   $lst.="<option value='$i' $_sel>$i</option>" ;
  }
  $lst.="</select>";
  
  return $lst;
 }
 
 ##------------------------------##
 public static function tipoPisos($props=array('name'=>"tipoPisos"),$selected=""){
  $propiedades="";
  
  if(is_array($props)){
   foreach($props as $key=>$value){
    $propiedades.="$key='$value' ";
   }
  }else{
   $propiedades=$props;
  }
  
  $lst="<select $propiedades>";
  $lst.="<option value='0'>Seleccione</option>";
  $rPisos=database::myQuery("select * from tiposPisos;");
  while($opPisos=mysql_fetch_array($rPisos)){
   if($selected==$opPisos["idTipoPiso"]){
    $_sel="selected";
   }else{
    $_sel="";
   }
   $lst.="<option value='".$opPisos["idTipoPiso"]."' $_sel>".($opPisos["descripcion"])."</option>";
  }
  $lst.="</select>";
  
  return $lst;
 }
 
 ##------------------------------##
 public static function tipoCalefaccion($props=array('name'=>"tipoCalefaccion"),$selected=""){
  $propiedades="";
  
  if(is_array($props)){
   foreach($props as $key =>$value){
    $propiedades.="$key='$value' ";
   }
  }else{
   $propiedades=$props;
  }
  
  $lst="<select $propiedades>";
  $lst.="<option value='0'>Seleccione</option>";
  $rCalefaccion=database::myQuery("select * from tiposCalefaccion");
  while($opCalefaccion=mysql_fetch_array($rCalefaccion)){
   if($selected==$opCalefaccion["idTipoCalefaccion"]){
    $_sel="selected";
   }else{
    $_sel="";
   }
   $lst.="<option value='".$opCalefaccion["idTipoCalefaccion"]."' $_sel>".($opCalefaccion["descripcion"])."</option>";
  }
  $lst.="</select>";
  
  return $lst;
 }
 
 ##------------------------------##
 public static function roles($props=array('name'=>"roles"),$selected=""){
 $propiedades="";
  
  if(is_array($props)){
   foreach($props as $key => $value){
    $propiedades.=$key."='".$value."' ";
   }
  }else{
   $propiedades=$props;
  }
  
  $lst="<select $propiedades>";
  $lst.="<option value='0'>Seleccione</option>";
  $rRoles=database::myQuery("select * from usuariosRoles;");
  while($row=mysql_fetch_array($rRoles)){
   if($selected==$row["idRol"]){
    $_sel="selected";
   }else{
    $_sel="";
   }
   
   $lst.="<option value='".$row["idRol"]."' $_sel>".($row["rol"])."</option>";
  }
  $lst.="</select>";
  
  return $lst;
 }

 ##------------------------------##
 public static function lstSexo($props=array('name'=>"sexo"),$selected=""){
  $propiedades="";
  
  if(is_array($props)){
   foreach($props as $key => $value){
    $propiedades.=$key."='".$value."' ";
   }
  }else{
   $propiedades=$props;
  }
  
  $lst="<select $propiedades>";
  $lst.="<option value=''>Seleccione</option>";
  $arr=array("Femenino","Masculino");
  $i=0;
  while($arr[$i]!=""){
   if($selected==substr($arr[$i],0,1)){
    $_sel="selected";
   }else{
    $_sel="";
   }
   
   $lst.="<option value='".substr($arr[$i],0,1)."' $_sel>".($arr[$i])."</option>";
   $i++;
  }
  $lst.="</select>";
  
  return $lst;
 }
 
 ##------------------------------##
 public static function lstEstadosConstruccion($props=array('name'=>"estado"),$selected=""){
  $propiedades="";
  
  if(is_array($props)){
   foreach($props as $key => $value){
    $propiedades.=$key."='".$value."' ";
   }
  }else{
   $propiedades=$props;
  }
  
  $lst="<select $propiedades>";
  $lst.="<option value=''>Seleccione</option>";
  $arr=array("En Verde","En Construcción", "Terminado");
  $arr_id=array("1","2", "3");
  $i=0;
  while($arr[$i]!=""){
	if($selected==$arr_id[$i]){
		$_sel="selected";
	}else{
		$_sel="";
	}
	$lst.="<option value='".($i+1)."' $_sel>".($arr[$i])."</option>";
	$i++;
  }
  $lst.="</select>";
  
  return $lst;
 }
 
 ##------------------------------##
 public static function lstOrientacion($props=array('name'=>"orientacion"),$selected=""){
  $propiedades="";
  
  if(is_array($props)){
   foreach($props as $key => $value){
    $propiedades.=$key."='".$value."' ";
   }
  }else{
   $propiedades=$props;
  }
  
  $lst="<select $propiedades>";
  $lst.="<option value=''>Seleccione</option>";
  $arr=array("N","S","E","O","NO","NE","SO","SE");
  $i=0;
  while($arr[$i]!=""){
   if($selected==$arr[$i]){
    $_sel="selected";
   }else{
    $_sel="";
   }
   
   $lst.="<option value='".$arr[$i]."' $_sel>".($arr[$i])."</option>";
   $i++;
  }
  $lst.="</select>";
  
  return $lst;
 }
 
 ##------------------------------##
 public static function check_caracteristicas($tipo=1,$propiedad=null){
  $_html="<div class=\"cuboChecks\">
           <input type=\"checkbox\" name=\"caracteristicas[]\" id=\"char_{char}\" title=\"{nchar}\" value=\"{char}\" {sel}/>           
         </div>\n";
  
  $_checks="";
  $_selected=array();
  
  if($propiedad!=null){
   $rp=database::myQuery("select * from propiedades_caracteristicas where idPropiedad='$propiedad';");
   while($fp=mysql_fetch_array($rp)){
    array_push($_selected,$fp["idCaracteristica"]);
   }
  }
  $r=database::myQuery("select a.idCaracteristica,a.nombre from caracteristicas a, caracteristicas_xtipo b where b.idTipoPropiedad='$tipo' and a.idCaracteristica=b.idCaracteristica and a.activa='1';");
  while($rc=mysql_fetch_array($r)){
   $_checks.=str_replace("{char}",$rc["idCaracteristica"],str_replace("{nchar}",($rc["nombre"]),str_replace("{sel}",((in_array($rc["idCaracteristica"],$_selected))?"checked":""),$_html)));
  }
  
  return $_checks;
 }
 
 ##------------------------------##
 public static function check_caracteristicas_proyectos($tipo=1,$proyecto=null){
  $_html="<div class=\"cuboChecks\">
           <input type=\"checkbox\" name=\"caracteristicas[]\" id=\"char_{char}\" value=\"{char}\" title=\"{nchar}\" {sel} />
         </div>\n";
  
  $_checks="";
  $_selected=array();
  
  if($proyecto!=null){
   $rp=database::myQuery("select * from proyectos_caracteristicas where idProyecto='$proyecto';");
   while($fp=mysql_fetch_array($rp)){
    array_push($_selected,$fp["idCaracteristica"]);
   }
  }
  $r=database::myQuery("select a.idCaracteristica,a.nombre from caracteristicas a, caracteristicas_xtipo b 
                        where b.idTipoPropiedad='$tipo' and a.idCaracteristica=b.idCaracteristica and a.activa='1'
						order by idCaracteristica asc;");
  while($rc=mysql_fetch_array($r)){
   //echo misc::strSiNo(in_array($rc["idCaracteristica"],$_selected));
   $_checks.=str_replace("{char}",$rc["idCaracteristica"],str_replace("{nchar}",($rc["nombre"]), str_replace("{sel}",((in_array($rc["idCaracteristica"],$_selected))?"checked":"pco"),$_html)));
  }
  
  return $_checks;
 }
 
 ##------------------------------##
 public static function lstPlantas($planta=array('name'=>"plantas"),$proyecto=0,$selected=""){
   $plantas="";
   
   if(is_array($planta)){
    foreach($planta as $key => $value){
     $plantas.=$key."='".$value."' ";
    }
   }else{
    $plantas=$planta;
   }
   
   /*onchange='this.form.submit()'*/
   $lst="<select $plantas>";
   $lst.="<option value='0'>Seleccione</option>";
   if($proyecto!=0){
    $_where="where idProyecto='$proyecto'";
   }
   $rComunas=database::myQuery("select * from proyectosPlantas $_where order by nombrePlanta asc;");
   while($row=mysql_fetch_array($rComunas)){
    if($selected==$row["idPlanta"]){
     $_sel="selected";
    }else{
     $_sel="";
    }
    
    $lst.="<option value='".$row["idPlanta"]."' $_sel>".$row["nombrePlanta"]."</option>";
   }
   $lst.="</select>";
   
   return $lst;
 }
 
 ##-------------------------------##
 public static function indicadores(){
  $_indicadores="<ul>";
  
  $resInd=database::myQuery("select * from indicadores where id_indicador in (2,3,4,5,8,9,10) order by field (id_indicador,2,3,4,5,8,10,9);");
  while($r=mysql_fetch_array($resInd)){
   $_indicadores.="<li>".$r["descripcion_corta"].": ".$r["valor"]."</li>";
  }
  
  $_indicadores.="</ul>";
  
  return $_indicadores;
 }
 
}
?>