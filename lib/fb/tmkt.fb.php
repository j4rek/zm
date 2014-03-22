<?php
require_once("src/facebook.php");

class tmktLoginFb{

  ## -- Parametros -- ##
  private $parametros=array(
          "appId"=>'121338748010443',
          "secret"=>'37c5c6a4e07816694f9e7a3f9b8e45ce'
          );
  
  private $user=null;
  private $user_profile=null;
  
  function __construct(){
    //instancia clase
    $this->facebook = new Facebook($this->parametros);  
  }
  
  ## -- funcion para login -- ##
  
  public function fb_login(){
    
    $this->fb_user();
    
    // URL login/logout 
    if ($this->user) {
      $logoutUrl = $this->facebook->getLogoutUrl();
    } else {
      $loginUrl = $this->facebook->getLoginUrl(array("scope"=>"email","redirect_uri"=>"http://www.zoominmobiliario.com/registro_.php"));
    }
    ## -- fin proceso --##  
    return array("LOGIN"=>$loginUrl,"LOGOUT"=>$logoutUrl);
  }
  
  ## -- funcion para obtener los datos del USER --##
  public function fb_user(){
    // obtiene el ID de usuario
    $this->user = $this->facebook->getUser();
    
    if ($this->user) {
      try {
        $this->user_profile = $this->facebook->api('/me');
      } catch (FacebookApiException $e) {
        error_log($e);
        $this->user = null;
      }
    }
        
    return $this->user_profile;
  }
  
}
?>