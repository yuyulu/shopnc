<?php
/* QQ登录
 * @version 2.0.0
 * @author connect@qq.com
 * @copyright © 2013, Tencent Corporation. All rights reserved.
 */
class OauthQq{
    const VERSION = "2.0";
    const GET_AUTH_CODE_URL = "https://graph.qq.com/oauth2.0/authorize";
    const GET_ACCESS_TOKEN_URL = "https://graph.qq.com/oauth2.0/token";
    const GET_OPENID_URL = "https://graph.qq.com/oauth2.0/me";
    const GET_USER_INFO = "https://graph.qq.com/user/get_user_info";
	public $client_id;
	public $client_secret;
    
    function __construct($client_id, $client_secret){
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
    }
    public function qq_login($url, $state = 'www', $display = ''){
        $keysArr = array(
            "response_type" => "code",
            "client_id" => $this->client_id,
            "redirect_uri" => $url,
            "state" => $state,
            "display" => $display,
            "scope" => "get_user_info"
        );
        $login_url =  $this->combine_url(self::GET_AUTH_CODE_URL, $keysArr);
        return $login_url;
    }
    public function qq_callback($url, $code){
        $keysArr = array(
            "grant_type" => "authorization_code",
            "client_id" => $this->client_id,
            "redirect_uri" => urlencode($url),
            "client_secret" => $this->client_secret,
            "code" => $code
        );
        $token_url = $this->combine_url(self::GET_ACCESS_TOKEN_URL, $keysArr);
        $response = $this->get_contents($token_url);
        if(strpos($response, "callback") !== false){
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response = substr($response, $lpos + 1, $rpos - $lpos -1);
        }
        $params = array();
        parse_str($response, $params);
        return $params["access_token"];
    }
    public function get_openid($token){
        $keysArr = array(
            "access_token" => $token
        );
        $graph_url = $this->combine_url(self::GET_OPENID_URL, $keysArr);
        $response = $this->get_contents($graph_url);
        if(strpos($response, "callback") !== false){
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response = substr($response, $lpos + 1, $rpos - $lpos -1);
        }
        $user = json_decode($response, true);
        return $user["openid"];
    }
    public function get_user_info($token, $openid){
        $keysArr = array(
            "openid" => $openid,
            "oauth_consumer_key" => $this->client_id,
            "access_token" => $token
        );
        $graph_url = $this->combine_url(self::GET_USER_INFO, $keysArr);
        $response = $this->get_contents($graph_url);
        $user_info = json_decode($response, true);
        return $user_info;
    }
    public function get_user_info_simple($token, $openid){
        $keysArr = array(
            "openid" => $openid,
            "oauth_consumer_key" => $this->client_id,
            "access_token" => $token
        );
        $graph_url = $this->combine_url('https://openmobile.qq.com/user/get_simple_userinfo', $keysArr);
        $response = $this->get_contents($graph_url);
        $user_info = json_decode($response, true);
        return $user_info;
    }
    public function combine_url($baseURL,$keysArr){
        $combined = $baseURL."?";
        $valueArr = array();
        foreach($keysArr as $key => $val){
            $valueArr[] = "$key=$val";
        }
        $keyStr = implode("&",$valueArr);
        $combined .= ($keyStr);
        return $combined;
    }
    public function get_contents($url){
        if (ini_get("allow_url_fopen") == "1") {
            $response = file_get_contents($url);
        }else{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $url);
            $response =  curl_exec($ch);
            curl_close($ch);
        }
        return $response;
    }
}