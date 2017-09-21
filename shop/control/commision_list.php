<?php
/**
 * 卖家佣金详情管理
 *
 *
 *
 ***/


defined('In33hao') or exit('Access Invalid!');
class commision_listControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
        Language::read('member_store_index');
    }

	/**
	 * 佣金列表
	 *
	 */
	public function indexOp() {
       
	   $store_info = $this->store_info;
	   $model_mingxi = Model('mingxi');
	   $model_goods = Model('goods');
	   $condition = array(); 
	   if(trim($_POST['commision_level']) != ''){
			$condition['commision_level']	= $_POST['commision_level'];
			Tpl::output('commision_level',$_POST['commision_level']);
		}
		if(trim($_POST['order_sn']) != ''){
			$condition['order_sn']	= array('like', '%'.trim($_POST['order_sn']).'%');
			Tpl::output('order_sn',$_POST['order_sn']);
		} 
	   $commision_lists = $model_mingxi->getMingxiByMemberId($condition);
	   $CLists = array();
	   foreach($commision_lists as $key=>$val){
	   		//echo $this->getStoreId($val['goods_id'];
			if($this->getStoreId($val['goods_id'])==$store_info['store_id']){
				$CLists[$key] = $val;
			}
	   }
	   foreach($CLists as $key=>$val){
	   		$goods_info = $model_goods->getGoodsInfo(array('goods_id'=>$val['goods_id']));
	   		$CLists[$key]['goods_name'] = $goods_info['goods_name'];
	   }
	   //header("Content-type:text/html;charset=utf-8");
	   Tpl::output('CLists',$CLists);
	   Tpl::output('show_page',$model_mingxi->showpage());
	   Tpl::showpage('commision_list');
	}

	 
	public function getStoreId($goods_id){
		
		$model_goods = Model('goods');
		$goods_info = $model_goods->getGoodsInfo(array('goods_id'=>$goods_id));
		return $goods_info['store_id'];
	} 
}
