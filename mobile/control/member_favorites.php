<?php
/**
 * 我的收藏
 *
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class member_favoritesControl extends mobileMemberControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 收藏列表
     */
    public function favorites_listOp() {
        $model_favorites = Model('favorites');

        $favorites_list = $model_favorites->getGoodsFavoritesList(array('member_id'=>$this->member_info['member_id']), '*', $this->page);
        $page_count = $model_favorites->gettotalpage();
        $favorites_id = '';
        foreach ($favorites_list as $value){
            $favorites_id .= $value['fav_id'] . ',';
        }
        $favorites_id = rtrim($favorites_id, ',');

        $model_goods = Model('goods');
        $field = 'goods_id,goods_name,goods_promotion_price,goods_image,store_id';
        $goods_list = $model_goods->getGoodsList(array(
            'goods_id' => array('in', $favorites_id),
            // 默认不显示预订商品
            'is_book' => 0,
        ), $field);
        foreach ($goods_list as $key=>$value) {
            $goods_list[$key]['fav_id'] = $value['goods_id'];
            $goods_list[$key]['goods_image_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
            $goods_list[$key]['goods_price'] = $value['goods_promotion_price'];
            unset($goods_list[$key]['goods_promotion_price']);
        }

        output_data(array('favorites_list' => $goods_list), mobile_page($page_count));
    }

    /**
     * 添加收藏
     */
    public function favorites_addOp() {
        $goods_id = intval($_POST['goods_id']);
        if ($goods_id <= 0){
            output_error('参数错误');
        }

        $favorites_model = Model('favorites');

        //判断是否已经收藏
        $favorites_info = $favorites_model->getOneFavorites(array('fav_id'=>$goods_id,'fav_type'=>'goods','member_id'=>$this->member_info['member_id']));
        if(!empty($favorites_info)) {
            output_error('您已经收藏了该商品');
        }

        //判断商品是否为当前会员所有
        $goods_model = Model('goods');
        $goods_info = $goods_model->getGoodsInfoByID($goods_id);
        $seller_info = Model('seller')->getSellerInfo(array('member_id'=>$this->member_info['member_id']));
        if ($goods_info['store_id'] == $seller_info['store_id']) {
            output_error('您不能收藏自己发布的商品');
        }

        //添加收藏
        $insert_arr = array();
        $insert_arr['member_id'] = $this->member_info['member_id'];
        $insert_arr['member_name'] = $this->member_info['member_name'];
        $insert_arr['fav_id'] = $goods_id;
        $insert_arr['fav_type'] = 'goods';
        $insert_arr['fav_time'] = TIMESTAMP;
        $result = $favorites_model->addFavorites($insert_arr);

        if ($result){
            //增加收藏数量
            $goods_model->editGoodsById(array('goods_collect' => array('exp', 'goods_collect + 1')), $goods_id);
            output_data('1');
        }else{
            output_error('收藏失败');
        }
    }

    /**
     * 删除收藏
     */
    public function favorites_delOp() {
        $fav_id = intval($_POST['fav_id']);
        if ($fav_id <= 0){
            output_error('参数错误');
        }

        $model_favorites = Model('favorites');
        $model_goods = Model('goods');

        $condition = array();
        $condition['fav_type'] = 'goods';
        $condition['fav_id'] = $fav_id;
        $condition['member_id'] = $this->member_info['member_id'];

        //判断收藏是否存在
        $favorites_info = $model_favorites->getOneFavorites($condition);
        if(empty($favorites_info)) {
            output_error('收藏删除失败');
        }

        $model_favorites->delFavorites($condition);

        $model_goods->editGoodsById(array('goods_collect' => array('exp', 'goods_collect - 1')), $fav_id);

        output_data('1');
    }
	

	/**
     * 收藏详情
     */
    public function favorites_infoOp() {
        $param = $_POST;
        $fav_id = $param['fav_id']?intval($param['fav_id']):0;
        if (!$fav_id) {
            output_data(array());
        }
        $model_favorites = Model('favorites');
        $where = array();
        $where['member_id'] = $this->member_info['member_id'];
        $where['fav_id'] = $fav_id;
        $where['fav_type'] = 'goods';
        $favorites_info = $model_favorites->getOneFavorites($where);
        if (!$favorites_info) {
            output_data(array());
        }
        $field = 'goods_id,goods_name,goods_price,goods_image,store_id';
        $goods_info = Model('goods')->getGoodsInfo(array(
            'goods_id' => $favorites_info['fav_id'],
            'is_book' => 0,// 默认不显示预订商品
        ), $field);
        $favorites_info = array_merge($favorites_info,$goods_info);
        $favorites_info['goods_image_url'] = cthumb($favorites_info['goods_image'], 240, $favorites_info['store_id']);
        output_data(array('favorites_info' => $favorites_info));
    }
}
