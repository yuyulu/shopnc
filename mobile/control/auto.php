<?php
/**
*微信相关接口功能
 * @好商城V4 (c) 2005-2016 33hao Inc.
 * @license    http://www.33hao.com
 * @link       交流群号：216611541
 * @since      好商城提供技术支持 授权请购买shopnc授权
**/

class autoControl extends mobileMemberControl{
	public function __construct() {
        parent::__construct();
        $agent = $_SERVER['HTTP_USER_AGENT']; 
		if (strpos($agent, "MicroMessenger") && $_GET["act"]=='auto') {	
			$this->appId = C('app_weixin_appid');
			$this->appSecret = C('app_weixin_secret');			
        }   
    }
	public function loginOp(){
		$redirect_uri = MOBILE_SITE_URL."/index.php?act=auto&op=checkAuth&ref=".$_GET['ref'];
	    $code_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$this->appId&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_base&state=123#wechat_redirect"; // 获取code
		if(!empty($_COOKIE['key']) && !empty($_COOKIE['new_cookie'])){ //已经登陆
			$ref=WAP_SITE_URL;
			$model_mb_user_token = Model('mb_user_token');
			$model_member = Model('member');
			$mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($_COOKIE['key']);
			$member_info = $model_member->getMemberInfoByID($mb_user_token_info['member_id']);
			if(empty($member_info)){
				setcookie('username',$member_info["member_name"],time()-3600*24,'/');
				setcookie('key',$token,time()-3600*24,'/');
				setcookie('unionid',$token,time()-3600*24,'/');
				setcookie('new_cookie','100',time()-3600*24,'/');
				header('Location:'.$code_url);exit;
			}
			header('Location:'.$ref);exit;	
		}else{    	
	    	header("location:".$code_url);
		}
		
	}
	

	public function checkAuthOp(){
		$ref = $_GET['ref'];
		if(empty($ref)){
			$ref=WAP_SITE_URL;
		}
		if (isset($_GET['code'])){				
			$this->code = $_GET['code'];
    		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$this->appId&secret=$this->appSecret&code=$this->code&grant_type=authorization_code";  		
    		$res =json_decode($this->httpGet($url), true);
    		$this->openid = $res['openid'];     		
    		
			$_SESSION['openid']=$res['openid'];    		
    		$accessToken5 = $this->getAccessToken(); 	    		
    		$url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=$accessToken5&openid=".$res['openid']."&lang=zh_CN";	//获取用户信息		    		
    		$res5=json_decode($this->httpGet($url), true);
    		

    		/*
    		if($res5['openid']==''){
    			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret"; //获取access_token
				$res = json_decode($this->httpGet($url));
				$access_token = $res->access_token;
				//echo $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=".$res['openid']."&lang=zh_CN";	//获取用户信息		
				//$res5=json_decode($this->httpGet($url), true);			

    		}*/
    		//$res5['unionid'] = 'me_'.$res5['openid'];
    		//$_SESSION['unionid']=$res5['unionid'];
			if(empty($res5['unionid'])){
				$res5['unionid'] = $res5['openid'];
			}
			//echo '<pre>';
    		
    		//$this->autoLogin($res5,$ref);
			$model_member = Model('member');
            $member_info = $model_member->getMemberInfo(array('weixin_unionid'=> $res5['unionid']));
			
			if(!empty($member_info)){
				$token = $this->_get_token($member_info['member_id'], $member_info['member_name'], 'wap');
				setcookie('username',$member_info["member_name"],time()+3600*24,'/');
				setcookie('key',$token,time()+3600*24,'/');
				//setcookie('unionid',$token,time()+3600*24,'/');
				setcookie('new_cookie','100',time()+3600*24,'/');
				header('Location:'.$ref);	
				//print_R($_COOKIE);exit;
			}else{				
				if($this->register($res5)){
					header('Location:'.$ref);
				}
			}
			
		}else{
			header('Location:'.$ref);
		}
	}
	private function register($user_info){
        $unionid = $user_info['unionid'];
        $nickname = $user_info['nickname'];
        if(!empty($unionid)) {
            $rand = rand(100, 899);
			if(empty($nickname))$nickname = 'name_'.$rand;
            if(strlen($nickname) < 3) $nickname = $nickname.$rand;
            $member_name = $nickname;
            $model_member = Model('member');
            $member_info = $model_member->getMemberInfo(array('member_name'=> $member_name));
            $password = rand(100000, 999999);
            $member = array();
            $member['member_passwd'] = $password;
            $member['member_email'] = '';
            $member['weixin_unionid'] = $unionid;
			//$member['nickname'] = $nickname;
			//$member['openid'] = $user_info['openid'];
            $member['weixin_info'] = $user_info['weixin_info'];
			
            if(empty($member_info)) {
                $member['member_name'] = $member_name;
                $result = $model_member->addMember($member);
            } else {
                for ($i = 1;$i < 999;$i++) {
                    $rand += $i;
                    $member_name = $nickname.$rand;
                    $member_info = $model_member->getMemberInfo(array('member_name'=> $member_name));
                    if(empty($member_info)) {//查询为空表示当前会员名可用
                        $member['member_name'] = $member_name;
                        $result = $model_member->addMember($member);
                        break;
                    }
                }
            }
            $headimgurl = $user_info['headimgurl'];//用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像）
            $headimgurl = substr($headimgurl, 0, -1).'132';
            $avatar = @copy($headimgurl,BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR."/avatar_$result.jpg");
            if($avatar) {
                $model_member->editMember(array('member_id'=> $result),array('member_avatar'=> "avatar_$result.jpg"));
            }
            $member = $model_member->getMemberInfo(array('member_name'=> $member_name));
            if(!empty($member)) {
				if(!empty($member_info)) {
					//$unionid = $member_info['unionid'];
					$token = $this->_get_token($result, $member_name, 'wap');
					setcookie('username',$member_name);
					setcookie('key',$token);
					return true;
				} else {
					return false;
				}
            }
        }
    }

	
	
	/**
     * 登录生成token
     */
    private function _get_token($member_id, $member_name, $client) {
        $model_mb_user_token = Model('mb_user_token');
        //生成新的token
        $mb_user_token_info = array();
        $token = md5($member_name . strval(TIMESTAMP) . strval(rand(0,999999)));
        $mb_user_token_info['member_id'] = $member_id;
        $mb_user_token_info['member_name'] = $member_name;
        $mb_user_token_info['token'] = $token;
        $mb_user_token_info['login_time'] = TIMESTAMP;
        $mb_user_token_info['client_type'] = $client;

        $result = $model_mb_user_token->addMbUserToken($mb_user_token_info);
        if($result) {
            return $token;
        } else {
            return null;
        }

    }
	


	//校验AccessToken 是否可用及返回新的
	private function getAccessToken() {
		$data = json_decode(file_get_contents("../access_token.json"));
		$check_token_url="https://api.weixin.qq.com/sns/auth?access_token=$data->access_token&openid=$this->appId";
		$check_res = json_decode($this->httpGet($check_token_url));		
		if ($data->expire_time < time() || $cike_url->errcode>0) {
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
			$res = json_decode($this->httpGet($url));
			$access_token = $res->access_token;
			if ($access_token) {
				$data->expire_time = time() + 6500;
				$data->access_token = $access_token;
				$fp = fopen("../access_token.json", "w");
				fwrite($fp, json_encode($data));
				fclose($fp);
			}
		} else {
			$access_token = $data->access_token;
		}
		return $access_token;
	}

	public function httpGet($url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, $url);	
		$res = curl_exec($curl);
		curl_close($curl);	
		return $res;
	}
}