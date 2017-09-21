<?php
/**
 * 微商城公共方法
 *
 * 公共方法
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');

function getMicroshopImageSize($image_url, $max_width = 238) {
    $local_file_path = str_replace(UPLOAD_SITE_URL, BASE_ROOT_PATH.DS.DIR_UPLOAD, $image_url);
    if(file_exists($local_file_path)) {
        list($width, $height) = getimagesize($local_file_path);
    } else {
        list($width, $height) = getimagesize($image_url);
    }
    if($width > $max_width) {
        $height = $height * $max_width/ $width;
        $width=$max_width;
    }
    return array(
        'width' => $width,
        'height' => $height
    );
}

function getRefUrl() {
    return urlencode('http://'.$_SERVER['HTTP_HOST'].request_uri());
}
