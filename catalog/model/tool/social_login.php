<?php
require_once(DIR_SYSTEM . 'connect/facebook/facebook.php');
require_once(DIR_SYSTEM . 'connect/twitter/twitteroauth.php');
require_once(DIR_SYSTEM . 'connect/google/Google_Client.php');
require_once(DIR_SYSTEM . 'connect/google/contrib/Google_Oauth2Service.php');
require_once(DIR_SYSTEM . 'connect/linkedin/linkedin.php');

class ModelToolSocialLogin extends Model {
  
  protected $google_client;
  protected $google_oauth2;
  	
  public function getFacebookLoginUrl(){
	  $facebook = new Facebook(array(
				'appId' => $this->config->get('social_facebook_key'),
				'secret' => $this->config->get('social_facebook_secret'),
				'cookie' => true
				));
	   $facebook_login_url=$facebook->getLoginUrl(array(
							    'redirect_uri' =>$this->config->get('social_facebook_redirect_uri'),//$this->url->link('account/login/facebook_login', '', 'SSL') ,
								'req_perms' => 'email,read_stream,publish_stream'
	   ));
	   return $facebook_login_url;
	   
  }
  public function getTwitterLoginUrl(){
	    $twitteroauth = new TwitterOAuth($this->config->get('social_twitter_key'), $this->config->get('social_twitter_secret'));
		$request_token = $twitteroauth->getRequestToken($this->config->get('social_twitter_redirect_uri'));
	
		$this->session->data['oauth_token'] = $request_token['oauth_token'];
		$this->session->data['oauth_token_secret'] = $request_token['oauth_token_secret'];
	
		if ($twitteroauth->http_code == 200) {
			$url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
		} else {
		 $url='';
		}

	   return $url;
	   
  }
  
  public function setGoogleCredential(){
	 $this->google_client = new Google_Client();
	 $this->google_client->setApplicationName($this->config->get('social_google_appname'));
	 $this->google_client->setClientId($this->config->get('social_google_client_id'));
	 $this->google_client->setClientSecret($this->config->get('social_google_secret_key'));
	 $this->google_client->setRedirectUri($this->config->get('social_google_redirect_uri'));
  	 $this->google_oauth2 = new Google_Oauth2Service($this->google_client);
  }
  
  public function getGoogleurl(){
    return $this->google_client->createAuthUrl();
	
  }
  
  public function getgoogleUserdata($code){
      if (isset($code)) {
         $this->google_client->authenticate($code);
		 $this->google_client->setAccessToken($this->google_client->getAccessToken());
	  }
		
	 if ($this->google_client->getAccessToken()) {
	   $userinfo = $this->google_oauth2->userinfo->get();
	 } else {
	   $userinfo=array();
	 }
	
	 return $userinfo;
  }
  
  public function checkfacebookLogin(){
     $facebook = new Facebook(array(
				'appId' => $this->config->get('social_facebook_key'),
				'secret' => $this->config->get('social_facebook_secret'),
				'cookie' => true
				));
	$user = $facebook->getUser();
	
	  try {
	   $user_profile = $facebook->api('/me');
	  } catch (FacebookApiException $e) {
	    echo "<pre>";
		print_r($e);
		exit;
		
		$user_profile=array();
	  }
	
	 return $user_profile;
	 
  }
  public function checktwitterLogin($oauth_verifier){
     $user_data=array();
	 if (!empty($oauth_verifier) && !empty($this->session->data['oauth_token']) && !empty($this->session->data['oauth_token_secret'])) {
		$twitteroauth = new TwitterOAuth($this->config->get('social_twitter_key'), $this->config->get('social_twitter_secret'), $this->session->data['oauth_token'], $this->session->data['oauth_token_secret']);
		$access_token = $twitteroauth->getAccessToken($oauth_verifier);
	    $this->session->data['access_token'] = $access_token;
		
		$user_profile = $twitteroauth->get('account/verify_credentials');
		
		if (isset($user_profile->error)) {
		  $user_data=array();
		} else{
		 $user_data=$user_profile;
		}
		
	}
	return $user_data;
	 
  }
  
   public function get_foursquare_login_url(){
    $login_url='https://foursquare.com/oauth2/authenticate?client_id='.$this->config->get('social_foursquare_client_id').'&response_type=code&redirect_uri='.$this->config->get('social_foursquare_redirect_uri');
    return $login_url;
  }
  
   public function getfoursquareUserdata($key){
  	 
	$ch = curl_init();
    $url="https://foursquare.com/oauth2/access_token?client_id=".$this->config->get('social_foursquare_client_id')."&client_secret=".$this->config->get('social_foursquare_secret_key')."&grant_type=authorization_code&redirect_uri=".urlencode($this->config->get('social_foursquare_redirect_uri'))."&code=".$key;       
	
  
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_URL,$url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 60); 
	$data = curl_exec($ch); 
	
	$obj = json_decode($data);
	
	
	if(isset($obj->error)){
	 return $obj->error;
	}else {
 	 $usertoken = $obj->access_token; 
    //If you want to show "Connected App" check-in replies for this user you will need to save this access token  
    //in a database with the user's foursquare id so you get access it later 
    $ch = curl_init();
	$url="https://api.foursquare.com/v2/users/self/checkins?oauth_token=".$obj->access_token.'&v='.date('Ymd');  
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_URL,$url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 60); 
	$uri = curl_exec($ch); 
     
	 $user_data = json_decode($uri);
	 return $user_data;
	}
  }
  
   public function getLinkedinurl(){
     $linkedin = new LinkedIn($this->config->get('social_linkedin_client_id'), $this->config->get('social_linkedin_secret_key'), $this->config->get('social_linkedin_callback_uri'));
	 $linkedin->getRequestToken();
	 $this->session->data['requestToken'] = serialize($linkedin->request_token);
   
     return $linkedin->generateAuthorizeUrl();
  }
  
  public function getlinkedinUserdata($code=NULL){
    $linkedin = new LinkedIn($this->config->get('social_linkedin_client_id'), $this->config->get('social_linkedin_secret_key'), $this->config->get('social_linkedin_callback_uri'));

	if (!empty($code)){
			$this->session->data['oauth_verifier'] = $code;
			 
			$linkedin->request_token    =   unserialize($this->session->data['requestToken']);
			$linkedin->oauth_verifier   =   $this->session->data['oauth_verifier'];
			$linkedin->getAccessToken($code);
	        
			$this->session->data['oauth_access_token']=serialize($linkedin->access_token);
		
			header("Location: " . $this->config->get('social_linkedin_callback_uri'));
			exit;
	}
	else{
			$linkedin->request_token    =   unserialize($this->session->data['requestToken']);
			$linkedin->oauth_verifier   =   $this->session->data['oauth_verifier'];
			$linkedin->access_token     =   unserialize($this->session->data['oauth_access_token']);
	}
	 
     $xml_response = $linkedin->getProfile("~:(id,first-name,last-name,headline,picture-url,email-address,location:(name),date-of-birth)");
     $userdata = $this->xml_to_array($xml_response); 
	
	 return $userdata;
  }
  
  function xml_to_array($xml,$main_heading = '') {
    $deXml = simplexml_load_string($xml);
    $deJson = json_encode($deXml);
    $xml_array = json_decode($deJson,TRUE);
    if (! empty($main_heading)) {
        $returned = $xml_array[$main_heading];
        return $returned;
    } else {
        return $xml_array;
    }
  }
  
  
}
?>