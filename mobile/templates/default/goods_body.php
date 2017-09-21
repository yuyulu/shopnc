<?php defined('In33hao') or exit('Access Invalid!');
if (!empty($output['goods_info']['mobile_body'])) {
    echo $output['goods_info']['mobile_body'];
} else {
    echo $output['goods_info']['goods_body'];
}?>