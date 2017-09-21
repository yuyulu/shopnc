<?php
/**
 * 商城板块初始化文件
 *
 * 商城板块初始化文件，引用框架初始化文件
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377 
 */
define('APP_ID','microshop');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));

require __DIR__ . '/../33hao.php';

define('APP_SITE_URL', MICROSHOP_SITE_URL);
define('MICROSHOP_IMG_URL',UPLOAD_SITE_URL.DS.ATTACH_MICROSHOP);
define('TPL_NAME',TPL_MICROSHOP_NAME);
define('MICROSHOP_RESOURCE_SITE_URL',MICROSHOP_SITE_URL.'/resource');
define('MICROSHOP_TEMPLATES_URL',MICROSHOP_SITE_URL.'/templates/'.TPL_NAME);
define('MICROSHOP_BASE_TPL_PATH',dirname(__FILE__).'/templates/'.TPL_NAME);

//define('MICROSHOP_SEO_KEYWORD',$config['seo_keywords']);
//define('MICROSHOP_SEO_DESCRIPTION',$config['seo_description']);

define('MICROSHOP_SEO_KEYWORD',C('seo_keywords'));
define('MICROSHOP_SEO_DESCRIPTION',C('seo_description'));

//微商城框架扩展
require(BASE_PATH.'/framework/function/function.php');

if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');
Base::run();
