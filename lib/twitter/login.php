<?php
session_start();
require_once("twitter-oauth/twitterOAuth.php");
error_reporting(E_ALL);
ini_set("display_errors",1);
if(!$_SESSION["valido"]){
    $twApi=new TwitterOAuth('6WKVhBo0VuKE9sSikRWFfg','a5eHNzx9b6AKFBd2H8X4mK9fyvaKy1XQgcRAiW7FnE');
    $twToken=$twApi->getRequestToken('http://www.zoominmobiliario.com/lib/twitter/callback.php');
    $_SESSION["ZITW"]=$twToken["oauth_token"];
    $_SESSION["ZITWS"]=$twToken["oauth_token_secret"];
    $twUrlLogin=$twApi->getAuthorizeURL($twToken["oauth_token"]);
    echo "<a href='$twUrlLogin'>ir</a>";
}else{
    $twApi=new TwitterOAuth('6WKVhBo0VuKE9sSikRWFfg','a5eHNzx9b6AKFBd2H8X4mK9fyvaKy1XQgcRAiW7FnE',$_SESSION["token"],$_SESSION["secret"]);
    $cred=$twApi->get("account/verify_credentials");
    $limits=$twApi->get("users/show",array("user_id"=>$cred->id,"screen_name"=>$cred->screen_name));
    echo "<pre>";
    var_dump($limits);
    echo "</pre>";
}



?>