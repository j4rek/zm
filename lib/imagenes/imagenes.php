<?php
namespace imagenes;

use config\config as cfg;
use misc\misc;

class imagenes {
 function __construct(){
  
 }
 
 ## --- resize --- ##
 public static function resizeImage($origen,$nomFoto,$rutaFinal,$thumb=true,$ancho=cfg::anchoMax,$alto=cfg::altoMax){
  
   #misc::generaLog("r","RESIZE:".$nomFoto);
   // tipo de imagen jpg|png
   $tp=explode(".",$nomFoto);
   $ext=strtolower($tp[1]);
   
   // Creamos una imagen para el redimensionado
   if($ext=="jpg" || $ext=="jpeg"){
     $src = imagecreatefromjpeg($origen);
     #misc::generaLog("c","jpg");
   }elseif($ext=="png"){
     $src = imagecreatefrompng($origen);
     #misc::generaLog("c","png");
   }elseif($ext=="gif"){
     $src = imagecreatefromgif($origen);
     #misc::generaLog("c","gif");
   }

   // Tamaño original de la imagen
   list($width,$height)=getimagesize($origen);
   #misc::generaLog("c","size: $width,$height");
    
   //Mantenemos el ratio de la imagen
   // Si alto es mayor
   if($width<$height){
       if($height>$alto){
           $newheight=$alto;
           $newwidth = ($newheight * $width) / $height;
       }else{
           $newheight=$height;
           $newwidth=$width;
       }
   }
   // Si el ancho es mayor
   elseif( $height < $width ){
       if($width>$ancho){
           $newwidth=$ancho;
           $newheight=($newwidth * $height) / $width;
       }else{
           $newheight=$height;
           $newwidth=$width;
       }
   }
   // Si el ancho = alto
   elseif($height==$width){
     $newheight=$ancho;
     $newwidth=$ancho;
   }
   $tmp=imagecreatetruecolor($newwidth,$newheight);
   #misc::generaLog("c","temp: $tmp");
   //Esta línea es la que se encarga de copiar la imagen con las medidas nuevas
   imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
   
   $path=cfg::repoUbic.cfg::repo.$rutaFinal;
   #$mk=mkdir($path,0777,true);
   #misc::generaLog("c","ruta:".cfg::repoUbic.cfg::repo.$rutaFinal." t:".$mk);
   //Ahora guardamos la imagen en la ruta especificada
   $filename = cfg::repoUbic.cfg::repo.$rutaFinal.$nomFoto;
   #misc::generaLog("c","archivo: $filename");
   
   $exif=exif_read_data($origen);
   $ort=$exif['Orientation'];
   switch($ort){
      case 3: // 180 rotate left
           $tmp=imagerotate($tmp, 180, -1);
           break;
      case 6: // 90 rotate right
           $tmp=imagerotate($tmp, -90, -1);
           break;
      case 8:    // 90 rotate left
           $tmp=imagerotate($tmp, 90, -1);
           break;
   }
    
   if($ext=="jpg" || $ext=="jpeg"){
    $make=imagejpeg($tmp,$filename,cfg::calidad);
    #misc::generaLog("e","genera jpg: $make");
   }elseif($ext=="png"){
    $make=imagepng($tmp,$filename,(cfg::calidad/10));
    #misc::generaLog("e","genera png: $make");
   }elseif($ext=="gif"){
    $make=imagegif($tmp,$filename);
    #misc::generaLog("e","genera gif: $make");
   }
   $_url=cfg::URLBASE.cfg::repo.$rutaFinal.$nomFoto;
   $exst=misc::urlExist($_url);
   #misc::generaLog("c","final $_url: $exst");
   #misc::generaLog("c","------------");
   
   chmod($filename,0755); // esto permite sobreescribir la imagen
    
   imagedestroy($tmp);
   imagedestroy($src);
   
   if($thumb){
    self::cropThumbnail($filename,"th-".$nomFoto,$path);
   }
   return $exst;
 }
 
 ## --- Funcion para cortar thumbnails --- ##
 public static function cropThumbnail($SrcImage,$nomFoto,$DestFolder,$Quality=cfg::calidad,$nAncho=cfg::thAncho,$nAlto=cfg::thAlto){
  #misc::generaLog("c","crop");
  // tipo de imagen 
   $tp=explode(".",$nomFoto);
   $ext=strtolower($tp[1]);
   
  $ImageType=image_type_to_mime_type(exif_imagetype($SrcImage));
  #misc::generaLog("c","tipo ".$ImageType);
  
  // Tamaño original de la imagen
  list($CurWidth,$CurHeight)=getimagesize($SrcImage);

   //revisa que peso no sea 0
   if($CurWidth <= 0 || $CurHeight <= 0){
       return false;
   }
   
   // Creamos una imagen para el redimensionado
   if($ext=="jpg" || $ext=="jpeg"){
     $src = imagecreatefromjpeg($SrcImage);
     #misc::generaLog("c","jpg");
   }elseif($ext=="png"){
     $src = imagecreatefrompng($SrcImage);
     #misc::generaLog("c","png");
   }elseif($ext=="gif"){
     $src = imagecreatefromgif($SrcImage);
     #misc::generaLog("c","gif");
   }

   if($CurWidth>$CurHeight){
    $_nAlto=($nAncho * $CurHeight) / $CurWidth;
    if($_nAlto<$nAlto){
     $_nAlto=$nAlto;
     $_nAncho=($nAlto * $CurWidth) / $CurHeight;
    }else{
     $_nAncho=($_nAlto * $CurWidth) / $CurHeight;
    }
    
    $y_offset = 0;
    $x_offset = ceil(($_nAncho/2)-($nAncho/2));
   }else{
    $_nAncho=($nAlto * $CurWidth) / $CurHeight;
    if($_nAncho<$nAncho){
     $_nAncho=$nAncho;
     $_nAlto=($nAncho * $CurHeight) / $CurWidth;
    }else{
     $_nAlto=($nAncho * $CurHeight) / $CurWidth;
    }
    
    $x_offset = 0;
    $y_offset = ceil(($_nAlto/2)-($nAlto/2));
   }
   #echo $_nAlto. " " . $_nAncho;
   
   $_NewCanves     = imagecreatetruecolor($_nAncho, $_nAlto);
   imagecopyresampled($_NewCanves, $src,0, 0, 0,0, ($_nAncho), ($_nAlto), $CurWidth, $CurHeight);
   
   $NewCanves=imagecreatetruecolor($nAncho,$nAlto);
   if(imagecopyresampled($NewCanves, $_NewCanves,0, 0, $x_offset,$y_offset,$nAncho,$nAlto,$nAncho,$nAlto)){
    #misc::generaLog("c","proceso");
       switch(strtolower($ImageType)){
         case 'image/png':
             $_png=imagepng($NewCanves,$DestFolder.$nomFoto,$Quality);
             #misc::generaLog("c","genera png :".$_png);
             break;
         case 'image/gif':
             $_gif=imagegif($NewCanves,$DestFolder.$nomFoto);
             #misc::generaLog("c","genera gif :".$_gif);
             break;
         case 'image/jpeg':
         case 'image/pjpeg':
             $_jpg=imagejpeg($NewCanves,$DestFolder.$nomFoto,$Quality=90);
             #misc::generaLog("c","genera jpg $DestFolder$nomFoto :".$_jpg);
             break;
         default:
             return false;
       }
   //libera memoria
   if(is_resource($NewCanves)) {imagedestroy($NewCanves);imagedestroy($_NewCanves);}
   return true;
   }
   return false;
 }

 ## --- resize de img --- ##
 public static function resizeImage_($CurWidth,$CurHeight,$MaxSize,$DestFolder,$SrcImage,$Quality=cfg::calidad,$ImageType){
    //revisa que peso no sea 0
    if($CurWidth <= 0 || $CurHeight <= 0)
    {
        return false;
    }
 
    //saca tamaño proporcional
    $ImageScale          = min($MaxSize/$CurWidth, $MaxSize/$CurHeight);
    $NewWidth              = ceil($ImageScale*$CurWidth);
    $NewHeight             = ceil($ImageScale*$CurHeight);
    $NewCanves             = imagecreatetruecolor($NewWidth, $NewHeight);
 
    // achica img
    if(imagecopyresampled($NewCanves, $SrcImage,0, 0, 0, 0, $NewWidth, $NewHeight, $CurWidth, $CurHeight))
    {
        switch(strtolower($ImageType))
        {
            case 'image/png':
                imagepng($NewCanves,$DestFolder);
                break;
            case 'image/gif':
                imagegif($NewCanves,$DestFolder);
                break;
            case 'image/jpeg':
            case 'image/pjpeg':
                imagejpeg($NewCanves,$DestFolder,$Quality);
                break;
            default:
                return false;
        }
    //libera memoria
    if(is_resource($NewCanves)) {imagedestroy($NewCanves);}
    return true;
    }
 
 }
 
 ## --- Funcion para cortar img a cuadrado --- ##
 public static function cropImage($CurWidth,$CurHeight,$iSize,$DestFolder,$SrcImage,$Quality=cfg::calidad,$ImageType)
 {
     //revisa que peso no sea 0
     if($CurWidth <= 0 || $CurHeight <= 0){
         return false;
     }
 
 
     // corta en forma cuadrada
     if($CurWidth>$CurHeight){
         $y_offset = 0;
         $x_offset = ($CurWidth - $CurHeight) / 2;
         $square_size     = $CurWidth - ($x_offset * 2);
     }else{
         $x_offset = 0;
         $y_offset = ($CurHeight - $CurWidth) / 2;
         $square_size = $CurHeight - ($y_offset * 2);
     }
 
     $NewCanves     = imagecreatetruecolor(440, 340);
     //if(imagecopyresampled($NewCanves, $SrcImage,0, 0, $x_offset, $y_offset, $iSize, $iSize, $square_size, $square_size))
     if(imagecopyresampled($NewCanves, $SrcImage,0, 0, $x_offset, $y_offset, 440, 340, $square_size, 340)){
         switch(strtolower($ImageType)){
             case 'image/png':
                 imagepng($NewCanves,$DestFolder);
                 break;
             case 'image/gif':
                 imagegif($NewCanves,$DestFolder);
                 break;
             case 'image/jpeg':
             case 'image/pjpeg':
                 imagejpeg($NewCanves,$DestFolder,cfg::calidad);
                 break;
             default:
                 return false;
         }
     //libera memoria
     if(is_resource($NewCanves)) {imagedestroy($NewCanves);}
     return true;
     }
 }

 ## --- Funcion para cortar img a medidaCustom --- ##
 public static function cropImageCustom($SrcImage,$nomFoto,$DestFolder,$Quality=cfg::calidad,$nAncho=cfg::thAncho,$nAlto=cfg::thAlto){
  
  #misc::generaLog("c","crop");
  // tipo de imagen 
   $tp=explode(".",$nomFoto);
   $ext=strtolower($tp[1]);
   
  $ImageType=image_type_to_mime_type(exif_imagetype($SrcImage));
  #misc::generaLog("c","tipo ".$ImageType);
  
  // Tamaño original de la imagen
  list($CurWidth,$CurHeight)=getimagesize($SrcImage);

   //revisa que peso no sea 0
   if($CurWidth <= 0 || $CurHeight <= 0){
       return false;
   }

   // corta en forma cuadrada
   if($CurWidth>$CurHeight){
       $y_offset = 0;
       $x_offset = 0;
       $square_size_height     = $CurHeight;
   }else{
       $x_offset = 0;
       $y_offset = ($CurHeight/2);
       $square_size_height = ($CurWidth*0.33);
   }
   #misc::generaLog("c","cuadro :".$square_size);
   
     // Creamos una imagen para el redimensionado
   if($ext=="jpg" || $ext=="jpeg"){
     $src = imagecreatefromjpeg($SrcImage);
     #misc::generaLog("c","jpg");
   }elseif($ext=="png"){
     $src = imagecreatefrompng($SrcImage);
     #misc::generaLog("c","png");
   }elseif($ext=="gif"){
     $src = imagecreatefromgif($SrcImage);
     #misc::generaLog("c","gif");
   }

   $NewCanves     = imagecreatetruecolor($nAncho, $nAlto);
   #misc::generaLog("c","truecolor : ".$NewCanves);
   if(imagecopyresampled($NewCanves, $src,0, 0, $x_offset,$y_offset, ($nAncho), ($nAlto), $CurWidth, $square_size_height)){
    #misc::generaLog("c","proceso");
       switch(strtolower($ImageType))
       {
           case 'image/png':
               $_png=imagepng($NewCanves,$DestFolder.$nomFoto);
               #misc::generaLog("c","genera png :".$_png);
               break;
           case 'image/gif':
               $_gif=imagegif($NewCanves,$DestFolder.$nomFoto);
               #misc::generaLog("c","genera gif :".$_gif);
               break;
           case 'image/jpeg':
           case 'image/pjpeg':
               $_jpg=imagejpeg($NewCanves,$DestFolder.$nomFoto,$Quality);
               #misc::generaLog("c","genera jpg $DestFolder$nomFoto :".$_jpg);
               break;
           default:
               return false;
       }
   //libera memoria
   if(is_resource($NewCanves)) {imagedestroy($NewCanves);}
   return true;
   }
   return false;
 }

}
?>