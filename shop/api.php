<?php
/**
 * 入口文件
 *
 * 统一入口，进行初始化信息
 * 购买行为
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377

 */
define('BASE_PATH', str_replace('\\', '/', dirname(__FILE__)));
require __DIR__ . '/../33hao.php';

session_save_path(BASE_DATA_PATH.DS.'session');
require_once(BASE_DATA_PATH.DS.'config/config.ini.php');
$site_url = $config['shop_site_url'];
$version = $config['version'];
$setup_date = $config['setup_date'];
$gip = $config['gip'];
$dbtype = $config['dbdriver'];
$dbcharset = $config['db']['1']['dbcharset'];
$dbserver = $config['db']['1']['dbhost'];
$dbserver_port = $config['db']['1']['dbport'];
$dbname = $config['db']['1']['dbname'];
$db_pre = $config['tablepre'];
$dbuser = $config['db']['1']['dbuser'];
$dbpasswd = $config['db']['1']['dbpwd'];
$lang_type = $config['lang_type'];
$cookie_pre = $config['cookie_pre'];
unset($config);

if($_GET['act'] == 'adv'){
    define('ATTACH_ADV','shop/adv');
    //define('SHOP_SITE_URL',$site_url);
    $advshow_classfile = BASE_PATH.DS.'control/adv.php';
    if(is_file($advshow_classfile)){
        include_once ($advshow_classfile);
        $advshow = new advControl();
        $advshow->advshowOp();
    }else{
        echo "Adv System Inner Error!";
    }

}elseif ($_GET['act'] == 'toqq'){
    //define('SHOP_SITE_URL',$site_url);
    if ($_GET['op'] == 'g'){
        include 'api/qq/oauth/qq_callback.php';
    }else{
        include 'api/qq/oauth/qq_login.php';
    }
}elseif ($_GET['act'] == 'tosina'){
    //define('SHOP_SITE_URL',$site_url);
    if ($_GET['op'] == 'g'){
        include 'api/sina/callback.php';
    }else{
        include 'api/sina/index.php';
    }
}elseif ($_GET['act'] == 'get_session'){
    //session_start();
    $key = $_GET['key'];
    $val = '';
    if (!empty($_SESSION[$key])) $val = $_SESSION[$key];
    echo $val;
    exit;
}elseif ($_GET['act'] == 'sharebind'){
    //define('SHOP_SITE_URL',$site_url);
    if($_GET['type'] == 'qqzone'){
        include BASE_DATA_PATH.DS.'api/snsapi/qqzone/oauth/qq_login.php';
    }elseif ($_GET['type'] == 'sinaweibo'){
        include BASE_DATA_PATH.DS.'api/snsapi/sinaweibo/index.php';
    }elseif ($_GET['type'] == 'qqweibo'){
        include BASE_DATA_PATH.DS.'api/snsapi/qqweibo/index.php';
    }
}
