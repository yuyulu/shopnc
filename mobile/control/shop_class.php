<?php
/**
 * 所有店铺分类
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */


defined('In33hao') or exit('Access Invalid!');

class shop_classControl extends mobileHomeControl {

    public function __construct(){
        parent::__construct();
    }

    /*
     * 首页显示
     */
    public function indexOp(){
        $this->_get_shop_class_List();

    }

    private  function  _get_shop_class_List(){
        //获取自营店列表
        $model_store_class = Model("store_class");
	//如果只想显示自营店铺，把下面的//去掉即可
        //$condition = array(
         //   'is_own_shop' => 1,
        //);

        $lst = $model_store_class->getStoreClassList($condition);
        $new_lst = array();
        foreach ($lst as $key => $value) {

            $new_lst[$key]['sc_id'] = $lst[$key]['sc_id'];
            $new_lst[$key]['sc_name'] = $lst[$key]['sc_name'];
            $new_lst[$key]['sc_bail'] = $lst[$key]['sc_bail'];
            $new_lst[$key]['sc_sort'] = $lst[$key]['sc_sort'];
        }
        output_data(array('class_list' => $new_lst));
    }
}