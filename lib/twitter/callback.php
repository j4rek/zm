<?php
session_start();
require_once("twitter-oauth/twitterOAuth.php");

$twApi=new TwitterOAuth('6WKVhBo0VuKE9sSikRWFfg','a5eHNzx9b6AKFBd2H8X4mK9fyvaKy1XQgcRAiW7FnE',$_SESSION["ZITW"],$_SESSION["ZITWS"]);

$twToken=$twApi->getAccessToken($_REQUEST["oauth_verifier"]);

if($twApi->http_code==200){
  $_SESSION["token"]=$twToken["oauth_token"];
  $_SESSION["secret"]=$twToken["oauth_token_secret"];
  $_SESSION["valido"]=true;
  header("location:login.php");
}else{
  
}
?>