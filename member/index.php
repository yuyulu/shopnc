<?php
/**
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */
define('APP_ID','member');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));

require __DIR__ . '/../33hao.php';

define('APP_SITE_URL', MEMBER_SITE_URL);
define('TPL_NAME',TPL_MEMBER_NAME);
define('MEMBER_TEMPLATES_URL', MEMBER_SITE_URL.'/templates/'.TPL_MEMBER_NAME);
define('BASE_MEMBER_TEMPLATES_URL', dirname(__FILE__).'/templates/'.TPL_MEMBER_NAME);
define('MEMBER_RESOURCE_SITE_URL',MEMBER_SITE_URL.'/resource');
define('LOGIN_RESOURCE_SITE_URL',LOGIN_SITE_URL.'/resource');
define('LOGIN_TEMPLATES_URL', LOGIN_SITE_URL.'/templates/'.TPL_MEMBER_NAME);

if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');
Base::run();
