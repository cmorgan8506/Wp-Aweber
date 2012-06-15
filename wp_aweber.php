<?php
/*
Plugin Name: WP Aweber
Plugin URI: http://www.colinjmorgan.com
Description: Integrates the use of the Aweber API in WordPress.
Version: 1.0
Author: Colin Morgan  
Author URI: http://www.colinjmorgan.com
License: GPL2
*/

require_once("aweber-admin.php");
require_once('aweber_api/aweber_api.php');

# Initialize Admin
$aweber_admin = new Wp_Aweber_Admin();

# Aweber API

if( !class_exists( Wp_Aweber ) ){
  class Wp_Aweber{
    private  $consumer_key;
    private  $consumer_secret;
    private  $access_key;
    private  $access_secret;
    private  $account;
    private  $options;
    
    function __construct(){
      # Grab Plugin Options
      $this->options = get_option('wp_aweber_options');

      # Check for oAuth tokens
      if( $this->get_tokens() ){
        $this->connect();
      } 
    }
    
    private function connect(){
      $consumerKey = trim($this->options['consumer_key']);
      $consumerSecret = trim($this->options['consumer_secret']);
      $aweber = new AWeberAPI($consumerKey, $consumerSecret);
      $this->account = $aweber->getAccount($this->access_key, $this->access_secret);
    }
    
    private function get_tokens(){
      # Check for Consumer Key
      if($this->options['consumer_key']){
        $this->consumer_key = trim( $this->options['consumer_key'] );
      }else{
        return false;
      }
      # Check for Consumer Secret
      if($this->options['consumer_secret']){
        $this->consumer_secret = trim( $this->options['consumer_secret'] );
      }else{
        return false;
      }
      # Check for Access Key
      if($this->options['access_key']){
        $this->access_key = trim( $this->options['access_key'] );
      }else{
        return false;
      }
      # Check for Access Secret
      if($this->options['access_secret']){
        $this->access_secret = trim( $this->options['access_secret'] );
      }else{
        return false;
      }
      # All keys exist
      return true;
    }
    
    public function authorize(){
      # copy your consumer key and secret from your app on labs.aweber.com
      # ensure that this application requests access to subscriber data!
      if( $this->options['consumer_key'] && $this->options['consumer_secret'] ){
        $consumerKey = trim($this->options['consumer_key']);
        $consumerSecret = trim($this->options['consumer_secret']);

        $aweber = new AWeberAPI($consumerKey, $consumerSecret);

        # do the authentication process
        if (empty($_COOKIE['token'])) {
          if (empty($_GET['oauth_token'])) {

              # step 1: get a request token
              $callback = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
              list($token, $secret) = $aweber->getRequestToken($callback);
              setcookie('secret', $secret);

              # step 2: prompt user to connect app
              header("Location: {$aweber->getAuthorizeUrl()}");
              exit();
          }

          # step 3: exchange request token for access token
          $aweber->user->tokenSecret = $_COOKIE['secret'];
          $aweber->user->requestToken = $_GET['oauth_token'];
          $aweber->user->verifier = $_GET['oauth_verifier'];
          list($token, $secret) = $aweber->getAccessToken();
          setcookie('token', $token);
          setcookie('secret', $secret);
          
          $this->options['access_key'] = $token;
          $this->options['access_secret'] = $secret;
          update_option('wp_aweber_options', $this->options);

          # redirect to self, so we can make api calls
          $app_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
          header('Location: '.$app_url);
          exit();
        }
      }
    }
    
    public function add_suscriber( $list_id, $info = array() ){
      $account_id = $this->account->id;
      $listURL = "/accounts/{$account_id}/lists/{$list_id}";
      $list = $account->loadFromUrl($listURL); 
    }
  }
}

?>
