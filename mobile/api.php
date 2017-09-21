<?php
/**
 * 入口文件
 *
 * 统一入口，进行初始化信息
 */

define('BASE_PATH', str_replace('\\', '/', dirname(__FILE__)));
require __DIR__ . '/../33hao.php';

session_save_path(BASE_DATA_PATH.DS.'session');
require_once(BASE_DATA_PATH.DS.'config/config.ini.php');

define('MOBILE_RESOURCE_SITE_URL',MOBILE_SITE_URL.DS.'resource');
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

if ($_GET['act'] == 'toqq'){
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
