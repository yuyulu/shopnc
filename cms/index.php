<?php
/**
 * 商城板块初始化文件
 *
 * 商城板块初始化文件，引用框架初始化文件
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
define('APP_ID','cms');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));
if (!@include(dirname(dirname(__FILE__)).'/33hao.php')) exit('33hao.php isn\'t exists!');

if (!@include(BASE_PATH.'/config/config.ini.php')){
	@header("Location: install/index.php");die;
}

define('APP_SITE_URL', CMS_SITE_URL);
define('TPL_NAME',TPL_CMS_NAME);
define('BASE_TPL_PATH',BASE_PATH.'/templates/'.TPL_NAME);

define('CMS_RESOURCE_SITE_URL',CMS_SITE_URL.'/resource');
define('CMS_TEMPLATES_URL',CMS_SITE_URL.'/templates/'.TPL_NAME);
define('CMS_BASE_TPL_PATH',dirname(__FILE__).'/templates/'.TPL_NAME);
define('CMS_SEO_KEYWORD',$config['seo_keywords']);
define('CMS_SEO_DESCRIPTION',$config['seo_description']);
//cms框架扩展
require(BASE_PATH.'/framework/function/function.php');
if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');

Base::run();
