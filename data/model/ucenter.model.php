<?php
/**
 * Ucenter处理
 *
 * 好商城 33hao.com
 *  
 * @好5商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 * 
 */
defined('In33hao') or exit('Access Invalid!');
class ucenterModel{
	/**
	 * 抛出错误信息
	 *
	 * @var obj
	 */
	public $uc_error = '';

	public function __construct() {
		define('API_SYNLOGIN',	1);        		//同步登录 API 接口开关
		define('API_SYNLOGOUT',	1);        		//同步登出 API 接口开关
        if($GLOBALS['setting_config']['ucenter_type'] == 'phpwind'){
		    define('P_W', TRUE);
        } else{
            define('IN_UC',	TRUE);
        }

		define('UC_API',		$GLOBALS['setting_config']['ucenter_url']);
		define('UC_CONNECT', 	($GLOBALS['setting_config']['ucenter_connect_type']=='0' ? 'mysql' : 'NULL'));
		define('UC_DBHOST',		$GLOBALS['setting_config']['ucenter_mysql_server']);
		define('UC_DBUSER',		$GLOBALS['setting_config']['ucenter_mysql_username']);
		define('UC_DBPW',		$GLOBALS['setting_config']['ucenter_mysql_passwd']);
		define('UC_DBNAME',		$GLOBALS['setting_config']['ucenter_mysql_name']);
		define('UC_DBCHARSET',	strtoupper(CHARSET)=='UTF-8' ? 'utf8' : 'gbk');
		define('UC_DBTABLEPRE', '`'.$GLOBALS['setting_config']['ucenter_mysql_name'].'`.'.$GLOBALS['setting_config']['ucenter_mysql_pre']);
		define('UC_KEY',		$GLOBALS['setting_config']['ucenter_app_key']);
		define('UC_CHARSET',	CHARSET);
		define('UC_IP',			$GLOBALS['setting_config']['ucenter_ip']);
		define('UC_APPID',		$GLOBALS['setting_config']['ucenter_app_id']);

        if($GLOBALS['setting_config']['ucenter_type'] == 'phpwind'){
		    @require_once(BASE_DATA_PATH.'/pw_client/uc_client.php');
        } else{
            @require_once(BASE_DATA_PATH.'/uc_client/client.php');
        }
	}
	/**
	 * 检测会员是否存在
	 * @author Vimhui Develop Team
	 * @param  $username 会员名
	 * @return bool
	 */
	public function checkUserExit($username,$isreturn=false) {
		$ucresult = uc_user_checkname($username);
		if($isreturn === true) return $ucresult;
		if ($ucresult == 1){
			return true;
		}
		if($ucresult == -1) {
			$this->uc_error = 'profile_username_illegal';
			return false;
		} elseif($ucresult == -2) {
			$this->uc_error = 'profile_username_protect';
			return false;
		} elseif($ucresult == -3) {
			$this->uc_error = 'register_check_found';
			return false;
		}
	}
	/**
	 * 电子邮件检查
	 * @author Vimhui Develop Team
	 * @param
	 * @return bool
	 */
	public function checkEmailExit($email) {
		return uc_user_checkemail($email);
	}
	/**
	 * 会员注册
	 * @author Vimhui Develop Team
	 * @param  $username 会员名, $password 密码, $email 邮件
	 * @return bool
	 */
	public function addUser($username, $password, $email) {
		$uid = uc_user_register($username, $password, $email);
		if($uid <= 0) {
			if($uid == -1) {
				$this->uc_error = 'profile_username_illegal';
				return false;
			} elseif($uid == -2) {
				$this->uc_error = 'profile_username_protect';
				return false;
			} elseif($uid == -3) {
				$this->uc_error = 'profile_username_duplicate';
				return false;
			} elseif($uid == -4) {
				$this->uc_error = 'profile_email_illegal';
				return false;
			} elseif($uid == -5) {
				$this->uc_error = 'profile_email_domain_illegal';
				return false;
			} elseif($uid == -6) {
				$this->uc_error = 'profile_email_duplicate';
				return false;
			} else {
				$this->uc_error = 'undefined_action';
				return false;
			}
		}else {
			$this->adduid = $uid;
			return $uid;
		}
	}
	/**
	 * 会员登录
	 * @author Vimhui Develop Team
	 * @param  $uid 会员ID
	 * @return int/bool/object/array/string
	 */
	public function userLogin($username, $password) {
		if($GLOBALS['setting_config']['ucenter_type'] == 'phpwind') {
			$user_login = uc_user_login($username,md5($password),0);
			if($user_login['status'] < 1) return false;
			$uid = $user_login['uid'];
			$uname = $user_login['username'];
			$pwd = $password;
			$email = $user_login['email'];
		} else {
			list($uid, $uname, $pwd, $email) = uc_user_login($username, $password);
		}
		if($uid > 0) {
			$user_exit = Db::select(array('table'=>'member','filed'=>'*','where'=>"where member_name='".$uname."' and member_id=".$uid));
			$user_exit = $user_exit[0];
			if(empty($user_exit)) {
				$reg_time	= time();
				$array		= array();
				$user_array['member_id']			= $uid;
				$user_array['member_name']			= $uname;
				$user_array['member_passwd']		= md5($pwd);
				$user_array['member_email']			= $email;
				$user_array['member_time']			= $reg_time;
				$user_array['member_login_time'] 	= $reg_time;
				$user_array['member_old_login_time']= $reg_time;
				$user_array['member_login_ip']		= getIp();
				$user_array['member_old_login_ip']	= $user_array['member_login_ip'];
				Db::insert('member',$user_array);
				return $uid;
			} else {
				if(md5($pwd) != $user_exit['member_passwd']) {
					Db::update('member',array('member_passwd'=>md5($pwd)),"where member_id='$uid' and member_name='$uname'");
				}
				if($email != $user_exit['member_email']) {
					Db::update('member',array('member_email'=>$email),"where member_id='$uid' and member_name='$uname'");
				}
				return $uid;
			}
		} else {
			return false;
		}
	}
	/**
	 * 输出通知部分
	 * @author Vimhui Develop Team
	 * @param
	 * @return bool
	 */
	public function outputLogin($uid, $password){
		if($GLOBALS['setting_config']['ucenter_type'] == 'phpwind') {
		    $user_login = uc_user_login($uid,md5($password),1);
            return $user_login['synlogin'];
        } else{
            return uc_user_synlogin($uid);
        }
	}
	/**
	 * 会员退出
	 * @author Vimhui Develop Team
	 * @param
	 * @return bool
	 */
	public function userLogout(){
		return uc_user_synlogout();
	}
	/**
	 * 会员修改
	 * @author Vimhui Develop Team
	 * @param
	 * @return bool
	 */
	public function userEdit($cfg, $ignoreoldpw = 1){
		if($GLOBALS['setting_config']['ucenter_type'] == 'phpwind') {
		    $user_login = uc_user_login($cfg['login_name'],md5($cfg ['old_password']),0);
            return uc_user_edit($user_login['uid'], $cfg['login_name'], $cfg['login_name'], $cfg['password'] ? $cfg['password'] : $cfg ['old_password'], $cfg['email']);
        } else{
            return uc_user_edit($cfg['login_name'], $cfg ['old_password'], $cfg['password'], $cfg['email'], $ignoreoldpw);
        }
	}
	/**
	 * 会员删除
	 * @author Vimhui Develop Team
	 * @param
	 * @return bool
	 */
	public function userDelete($uids) {
		return uc_user_delete($uids);
	}
    /**
	 * 会员积分兑换
	 * @author Vimhui Develop Team
	 * @param
	 * @return bool
	 */
	public function userCreditExchange($uid, $creditsrc, $to_credit, $to_appid, $net_amount) {
        if($GLOBALS['setting_config']['ucenter_type'] == 'phpwind') {
            $credit=array("$uid"=> array('credit'=>$net_amount));
            return uc_credit_add($credit);
        } else {
            return uc_credit_exchange_request($uid, $creditsrc, $to_credit, $to_appid, $net_amount);
        }
	}
	/**
	 * 获取uc用户信息
	 */
	public function getUserInfo($username,$isuid = 0){
		return uc_get_user($username, $isuid);
	}
}
