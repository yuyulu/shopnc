<?php
/**
 * UC接口
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */
define('IN_DISCUZ', true);
define('In33hao',true);
error_reporting(7);
define('UC_CLIENT_VERSION', '1.6.0');
define('UC_CLIENT_RELEASE', '20110501');

define('API_DELETEUSER', 1);		//note 用户删除 API 接口开关
define('API_RENAMEUSER', 1);		//note 用户改名 API 接口开关
define('API_GETTAG', 1);		//note 获取标签 API 接口开关
define('API_SYNLOGIN', 1);		//note 同步登录 API 接口开关
define('API_SYNLOGOUT', 1);		//note 同步登出 API 接口开关
define('API_UPDATEPW', 1);		//note 更改用户密码 开关
define('API_UPDATEBADWORDS', 1);	//note 更新关键字列表 开关
define('API_UPDATEHOSTS', 1);		//note 更新域名解析缓存 开关
define('API_UPDATEAPPS', 1);		//note 更新应用列表 开关
define('API_UPDATECLIENT', 1);		//note 更新客户端缓存 开关
define('API_UPDATECREDIT', 1);		//note 更新用户积分 开关
define('API_GETCREDITSETTINGS', 1);	//note 向 UCenter 提供积分设置 开关
define('API_GETCREDIT', 1);		//note 获取用户的某项积分 开关
define('API_UPDATECREDITSETTINGS', 1);	//note 更新应用积分设置 开关

define('API_RETURN_SUCCEED', '1');
define('API_RETURN_FAILED', '-1');
define('API_RETURN_FORBIDDEN', '-2');

define('hao_ROOT', substr(dirname(__FILE__), 0, -3));
$data = require(hao_ROOT.'/data/cache/setting.php');
require_once hao_ROOT.'/data/config/config.ini.php';
if(!empty($config) && is_array($config)){
	$site_url = $config['shop_site_url'];
	$version = $config['version'];
	$setup_date = $config['setup_date'];
	$gip = $config['gip'];
	$dbtype = $config['dbdriver'];
	$dbcharset = $config['db'][1]['dbcharset'];
	$dbserver = $config['db'][1]['dbhost'];
	$dbserver_port = $config['db'][1]['dbport'];
	$dbname = $config['db'][1]['dbname'];
	$db_pre = $config['tablepre'];
	$dbuser = $config['db'][1]['dbuser'];
	$dbpasswd = $config['db'][1]['dbpwd'];
	$lang_type = $config['lang_type'];
	$cookie_pre = $config['cookie_pre'];
	$tpl_name = $config['tpl_name'];
}
@ini_set('session.cookie_domain', $config['subdomain_suffix']);
define('UC_CONNECT', ($data['ucenter_connect_type']=='0' ? 'mysql' : 'NULL'));				// 连接 UCenter 的方式: mysql/NULL, 默认为空时为 fscoketopen()
//数据库相关 (mysql 连接时, 并且没有设置 UC_DBLINK 时, 需要配置以下变量)
define('UC_DBHOST', $data['ucenter_mysql_server']);			// UCenter 数据库主机
define('UC_DBUSER', $data['ucenter_mysql_username']);		// UCenter 数据库用户名
define('UC_DBPW',	$data['ucenter_mysql_passwd']);			// UCenter 数据库密码
define('UC_DBNAME', $data['ucenter_mysql_name']);			// UCenter 数据库名称
define('UC_DBCHARSET', strtoupper($dbcharset)=='UTF-8' ? 'utf8' : 'gbk');				// UCenter 数据库字符集
define('UC_DBTABLEPRE', '`'.$data['ucenter_mysql_name'].'`.'.$data['ucenter_mysql_pre']);		// UCenter 数据库表前缀
//通信相关
define('UC_KEY', $data['ucenter_app_key']);					// 与 UCenter 的通信密钥, 要与 UCenter 保持一致
define('UC_API', $data['ucenter_url']);						// UCenter 的 URL 地址, 在调用头像时依赖此常量
define('UC_CHARSET', $dbcharset);								// UCenter 的字符集
define('UC_IP', $data['ucenter_ip']);						// UCenter 的 IP, 当 UC_CONNECT 为非 mysql 方式时, 并且当前应用服务器解析域名有问题时, 请设置此值
define('UC_APPID', $data['ucenter_app_id']);				// 当前应用的 ID
define('BASE_ROOT_PATH',dirname(dirname(__FILE__)));
session_save_path(BASE_ROOT_PATH.'/data/session');
session_start();
$dbcharset = strtoupper($dbcharset)=='UTF-8' ? 'utf8' : 'gbk';
//note 普通的 http 通知方式
if(!defined('IN_UC')) {
	error_reporting(0);
	@set_magic_quotes_runtime(0);

	defined('MAGIC_QUOTES_GPC') || define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());

	$_DCACHE = $get = $post = array();

	$code = @$_GET['code'];
	parse_str(_authcode($code, 'DECODE', UC_KEY), $get);
	if(MAGIC_QUOTES_GPC) {
		$get = _stripslashes($get);
	}
	$timestamp = time();
	if($timestamp - $get['time'] > 3600) {
		exit('Authracation has expiried');
	}
	if(empty($get)) {
		exit('Invalid Request');
	}
	$action = $get['action'];
	require_once hao_ROOT.'/uc_client/lib/xml.class.php';
	$post = xml_unserialize(file_get_contents('php://input'));
	if(in_array($get['action'], array('test', 'deleteuser', 'renameuser', 'gettag', 'synlogin', 'synlogout', 'updatepw', 'updatebadwords', 'updatehosts', 'updateapps', 'updateclient', 'updatecredit', 'getcreditsettings', 'updatecreditsettings'))) {
		require_once 'db_mysql.class.php';
		$GLOBALS['db'] = new dbstuff;
		$GLOBALS['db']->connect($dbserver, $dbuser, $dbpasswd, $dbname,$db_pre, $dbcharset);
		$GLOBALS['tablepre'] = '`'.$dbname.'`.'.$db_pre;
		unset($dbserver, $dbuser, $dbpw, $dbpasswd, $dbname, $db_pre);
		$uc_note = new uc_note();
		exit($uc_note->$get['action']($get, $post));
	} else {
		exit(API_RETURN_FAILED);
	}
} else {
	require_once 'db_mysql.class.php';
	$GLOBALS['db'] = new dbstuff;
	$GLOBALS['db']->connect($dbserver, $dbuser, $dbpasswd, $dbname,$db_pre, $dbcharset);
	$GLOBALS['tablepre'] = '`'.$dbname.'`.'.$db_pre;
	unset($dbserver, $dbuser, $dbpw, $dbpasswd, $dbname, $db_pre);
}

class uc_note {

	public $db = '';
	public $tablepre = '';
	public $appdir = '';

	function _serialize($arr, $htmlon = 0) {
		if(!function_exists('xml_serialize')) {
			include_once hao_ROOT.'/uc_client/lib/xml.class.php';
		}
		return xml_serialize($arr, $htmlon);
	}

	function uc_note() {
		$this->appdir = hao_ROOT;
		$this->db = $GLOBALS['db'];
		$this->tablepre = $GLOBALS['tablepre'];
	}

	function test($get, $post) {
		return API_RETURN_SUCCEED;
	}

	function deleteuser($get, $post) {
		$uids = $get['ids'];
		!API_DELETEUSER && exit(API_RETURN_FORBIDDEN);

		$this->db->query("DELETE FROM ".$this->tablepre."member WHERE member_id IN ($uids)");
		$this->db->query("DELETE FROM ".$this->tablepre."store WHERE member_id IN ($uids)");
		/*删除未完，还有其他数据需要删除*/
		return API_RETURN_SUCCEED;
	}

	function renameuser($get, $post) {
		$uid = $get['uid'];
		$usernameold = $get['oldusername'];
		$usernamenew = $get['newusername'];
		if(!API_RENAMEUSER) {
			return API_RETURN_FORBIDDEN;
		}
		if($usernamenew != '' and $usernameold != '') {
			$this->db->query("UPDATE ".$this->tablepre."member SET member_name='$usernamenew' WHERE member_name='$usernameold'");
		}
		return API_RETURN_SUCCEED;
	}

	function gettag($get, $post) {
		$name = $get['id'];
		if(!API_GETTAG) {
			return API_RETURN_FORBIDDEN;
		}

		$return = array();
		return $this->_serialize($return, 1);
	}

	function synlogin($get, $post) {
		$uid = $get['uid'];
		$username = $get['username'];
		if(!API_SYNLOGIN) {
			return API_RETURN_FORBIDDEN;
		}
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		$member_info = $this->db->fetch_first("SELECT member.*,store.store_name,store.grade_id FROM ".$this->tablepre."member as member left join ".$this->tablepre."store as store on member.store_id = store.store_id  WHERE member.member_name='".$username."' and member.member_id=".$uid);
		if(empty($member_info)) {
			return false;
		}
		if($member_info['member_state'] == '1'){//账号可以使用（1为启用 0 为禁用）
			$this->db->query("UPDATE ".$this->tablepre."member set member_login_ip='".$this->getIp()."',member_old_login_ip='".$member_info['member_login_ip']."',member_login_num=".($member_info['member_login_num']+1).",member_login_time='".time()."',member_old_login_time='".$member_info['member_login_time']."' WHERE member_name='".$username."' and member_id=".$uid);
			/**
			* 写入session
			*/
			$_SESSION['is_login']	= '1';
			$_SESSION['is_seller']	= intval($member_info['store_id']) == 0 ? '' : 1;
			$_SESSION['member_id']	= $member_info['member_id'];
			$_SESSION['member_name']= $member_info['member_name'];
			$_SESSION['member_email']= $member_info['member_email'];		
			if (trim($member_info['member_qqopenid'])){
				$_SESSION['openid']		= $member_info['member_qqopenid'];
			}
			if (trim($member_info['member_sinaopenid'])){
				$_SESSION['slast_key']['uid'] = $member_info['member_sinaopenid'];
			}
			if (intval($member_info['store_id']) > 0){
				$_SESSION['store_id']	= intval($member_info['store_id']);
				$_SESSION['store_name']	= $member_info['store_name'];
				$_SESSION['grade_id']	= $member_info['grade_id'];
			}
		}
	}

	function synlogout($get, $post) {
		if(!API_SYNLOGOUT) {
			return API_RETURN_FORBIDDEN;
		}

		//note 同步登出 API 接口
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		session_unset();
		session_destroy();
	}

	function updatepw($get, $post) {
		if(!API_UPDATEPW) {
			return API_RETURN_FORBIDDEN;
		}
		$username = $get['username'];
		$password = md5($get['password']);

		$this->db->query("UPDATE ".$this->tablepre."member SET member_passwd='$password' WHERE member_name='$username'");
		return API_RETURN_SUCCEED;
	}

	function updatebadwords($get, $post) {
		if(!API_UPDATEBADWORDS) {
			return API_RETURN_FORBIDDEN;
		}
		$cachefile = $this->appdir.'./uc_client/data/cache/badwords.php';
		$fp = fopen($cachefile, 'w');
		$data = array();
		if(is_array($post)) {
			foreach($post as $k => $v) {
				$data['findpattern'][$k] = $v['findpattern'];
				$data['replace'][$k] = $v['replacement'];
			}
		}
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'badwords\'] = '.var_export($data, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}

	function updatehosts($get, $post) {
		if(!API_UPDATEHOSTS) {
			return API_RETURN_FORBIDDEN;
		}
		$cachefile = $this->appdir.'./uc_client/data/cache/hosts.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'hosts\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}

	function updateapps($get, $post) {
		if(!API_UPDATEAPPS) {
			return API_RETURN_FORBIDDEN;
		}
		$UC_API = $post['UC_API'];

		//note 写 app 缓存文件
		$cachefile = $this->appdir.'./uc_client/data/cache/apps.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'apps\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);

		//note 写配置文件
		if(is_writeable($this->appdir.'./config.inc.php')) {
			$configfile = trim(file_get_contents($this->appdir.'./config.inc.php'));
			$configfile = substr($configfile, -2) == '?>' ? substr($configfile, 0, -2) : $configfile;
			$configfile = preg_replace("/define\('UC_API',\s*'.*?'\);/i", "define('UC_API', '$UC_API');", $configfile);
			if($fp = @fopen($this->appdir.'./config.inc.php', 'w')) {
				@fwrite($fp, trim($configfile));
				@fclose($fp);
			}
		}

		return API_RETURN_SUCCEED;
	}

	function updateclient($get, $post) {
		if(!API_UPDATECLIENT) {
			return API_RETURN_FORBIDDEN;
		}
		$cachefile = $this->appdir.'./uc_client/data/cache/settings.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'settings\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}

	function updatecredit($get, $post) {
		if(!API_UPDATECREDIT) {
			return API_RETURN_FORBIDDEN;
		}
		$credit = $get['credit'];
		$amount = $get['amount'];
		$uid = $get['uid'];

		require_once hao_ROOT.'/uc_client/data/cache/creditsettings.php';
		$this->db->query("UPDATE ".$this->tablepre."member SET member_points=member_points+{$amount} WHERE member_id='$uid'");
		$member_name = $this->db->result_first("SELECT member_name FROM ".$this->tablepre."member WHERE member_id='$uid'");
		$this->db->query("INSERT INTO ".$this->tablepre."points_log (pl_memberid, pl_membername, pl_points, pl_addtime, pl_desc, pl_stage)
				VALUES ('$uid', '$member_name', '$amount', '".time()."', '".getGBK('来自其它应用的积分兑入')."', 'app')");
		return API_RETURN_SUCCEED;
	}

	function getcredit($get, $post) {
		if(!API_GETCREDIT) {
			return API_RETURN_FORBIDDEN;
		}
	}

	function getcreditsettings($get, $post) {
		if(!API_GETCREDITSETTINGS) {
			return API_RETURN_FORBIDDEN;
		}
		$credits = array (
					  1 => array (
					    0 => getGBK('积分'),
					    1 => '',
					  ),
				  );
		return $this->_serialize($credits);
	}

	function updatecreditsettings($get, $post) {
		global $_DCACHE;
		if(!API_UPDATECREDITSETTINGS) {
			return API_RETURN_FORBIDDEN;
		}
		$outextcredits = array();

		foreach($get['credit'] as $appid => $credititems) {
			if($appid == UC_APPID) {
				foreach($credititems as $value) {
					$outextcredits[$value['appiddesc'].'|'.$value['creditdesc']] = array(
						'creditsrc' => $value['creditsrc'],
						'title' => $value['title'],
						'unit' => $value['unit'],
						'ratio' => $value['ratio']
					);
				}
			}
		}

		$cachefile = hao_ROOT.'/uc_client/data/cache/creditsettings.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'creditsettings\'] = '.var_export($outextcredits, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);

		return API_RETURN_SUCCEED;
	}

	function getIp(){
		if (@$_SERVER['HTTP_CLIENT_IP'] && $_SERVER['HTTP_CLIENT_IP']!='unknown') {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (@$_SERVER['HTTP_X_FORWARDED_FOR'] && $_SERVER['HTTP_X_FORWARDED_FOR']!='unknown') {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}


function _authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;

	$key = md5($key ? $key : UC_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}

function _stripslashes($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = _stripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}

function getGBK($key){
	/**
	 * 转码
	 */
	if (strtoupper(UC_CHARSET) == 'GBK' && !empty($key)){
		if (is_array($key)){
			$result = var_export($key, true);//变为字符串
			$result = iconv('UTF-8','GBK',$result);
			eval("\$result = $result;");//转换回数组
		} else {
			$result = iconv('UTF-8','GBK',$key);
		}
		return $result;
	} else {
		return $key;
	}
}