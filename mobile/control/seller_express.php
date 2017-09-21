<?php
/**
 * 商家注销
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class   Control extends mobileSellerControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 物流列表
     */
    public function get_listOp() {
        $express_list  = rkcache('express',true);
        $express_array = array();
        //快递公司
        $my_express_list = Model()->table('store_extend')->getfby_store_id($this->store_info['store_id'],'express');
        if (!empty($my_express_list)){
            $my_express_list = explode(',',$my_express_list);
            foreach ($my_express_list as $val) {
                $express_array[$val] = $express_list[$val];
            }
        }

        output_data(array('express_array' =>$express_array));
    }
    
    /**
     * 自提物流列表
     */
    public function get_zt_listOp() {
        $express_list  = rkcache('express',true);
        foreach ($express_list as $k => $v) {
            if ($v['e_zt_state'] == '0') unset($express_list[$k]);
        }
        output_data(array('express_array' =>$express_list));
    }
}
