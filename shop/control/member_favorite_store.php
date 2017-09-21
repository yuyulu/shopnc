<?php
/**
 * 会员中心--收藏功能
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class member_favorite_storeControl extends BaseMemberControl{
    public function __construct(){
        parent::__construct();
        Language::read('member_layout,member_member_favorites');
    }
    public function indexOp() {
        $this->fslistOp();
    }
    /**
     * 增加店铺收藏
     */
    public function favoritestoreOp(){
        $fav_id = intval($_GET['fid']);
        if ($fav_id <= 0){
            echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_collect_fail','UTF-8')));
            die;
        }
        $favorites_model = Model('favorites');
        //判断是否已经收藏
        $favorites_info = $favorites_model->getOneFavorites(array('fav_id'=>"$fav_id",'fav_type'=>'store','member_id'=>"{$_SESSION['member_id']}"));
        if(!empty($favorites_info)){
            echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_already_favorite_store','UTF-8')));
            die;
        }
        //判断店铺是否为当前会员所有
        if ($fav_id == $_SESSION['store_id']){
            echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_no_my_store','UTF-8')));
            die;
        }
        //添加收藏
        $insert_arr = array();
        $insert_arr['member_id'] = $_SESSION['member_id'];
        $insert_arr['member_name'] = $_SESSION['member_name'];
        $insert_arr['fav_id'] = $fav_id;
        $insert_arr['fav_type'] = 'store';
        $insert_arr['fav_time'] = time();
        $result = $favorites_model->addFavorites($insert_arr);
        if ($result){
            //增加收藏数量
            $store_model = Model('store');
            $store_model->editStore(array('store_collect'=>array('exp', 'store_collect+1')), array('store_id' => $fav_id));
            echo json_encode(array('done'=>true,'msg'=>Language::get('favorite_collect_success','UTF-8')));
            die;
        }else{
            echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_collect_fail','UTF-8')));
            die;
        }
    }
    /**
     * 店铺收藏列表
     *
     * @param
     * @return
     */
    public function fslistOp(){
        $favorites_model = Model('favorites');
        $favorites_list = $favorites_model->getStoreFavoritesList(array('member_id'=>$_SESSION['member_id']), '*', 10);
        if (!empty($favorites_list) && is_array($favorites_list)){
            $favorites_id = array();//收藏的店铺编号
            foreach ($favorites_list as $key=>$favorites){
                $fav_id = $favorites['fav_id'];
                $favorites_id[] = $favorites['fav_id'];
                $favorites_key[$fav_id] = $key;
            }
            $store_model = Model('store');
            $store_list = $store_model->getStoreList(array('store_id'=>array('in', $favorites_id)));
            if (!empty($store_list) && is_array($store_list)){
                foreach ($store_list as $key=>$val){
                    $store_id = $val['store_id'];
                    $key = $favorites_key[$store_id];
                    $favorites_list[$key]['store'] = $val;
                    $favorites_list[$key]['goods'] = $this->_getStoreGoods($store_id);
                }
            }
        }
        self::profile_menu('favorites','fav_store');
        Tpl::output('menu_key',"fav_store");
        Tpl::output('favorites_list',$favorites_list);
        Tpl::output('show_page',$favorites_model->showpage(2));
        Tpl::showpage("favorites_store_index");
    }
    /**
     * 取得店铺上新、优惠促销、热销商品
     * @param unknown $store_id
     */
    private function _getStoreGoods($store_id) {
        $model_goods = Model('goods');
        $fieldstr = "goods_id,goods_name,goods_promotion_price,goods_image";
        
        // 查询数量
        $num = 5;
        
        $sign = '';
        
        // 热销商品
        $condition = array();
        $condition['store_id'] = $store_id;
        $hot_goods_list = $model_goods->getGoodsListByColorDistinct($condition, $fieldstr, 'goods_salenum desc', 0, $num);
        if (!empty($hot_goods_list)) {
            $sign = 'hot';
        }
        
        // 优惠促销
        $condition = array();
        $condition['store_id'] = $store_id;
        $condition['goods_promotion_type'] = array('neq', 0);
        $promotion_goods_list = $model_goods->getGoodsListByColorDistinct($condition, $fieldstr, 'goods_id desc', 0, $num);
        if (!empty($promotion_goods_list)) {
            $sign = 'promotion';
        }
        
        // 本周上新
        $weak_start = mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"));
        $weak_end   = mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"));
        $condition = array();
        $condition['store_id'] = $store_id;
        $condition['goods_addtime'] = array('between', array($weak_start, $weak_end));
        $new_goods_list = $model_goods->getGoodsListByColorDistinct($condition, $fieldstr, 'goods_id desc', 0, $num);
        if (!empty($new_goods_list)) {
            $sign = 'new';
        }
        
        return array('new'=>$new_goods_list, 'promotion'=>$promotion_goods_list, 'hot'=>$hot_goods_list, 'sign' => $sign);
    }
    /**
     * 店铺收藏列表查看 
     */
    public function moreOp() {
        $store_id = $_GET['store_id'];

        if (empty($_GET['curpage'])) {
            $store_info = array();
            $favorites_model = Model('favorites');
            $favorites_list = $favorites_model->getStoreFavoritesList(array('member_id'=>$_SESSION['member_id']), '*');
            if (!empty($favorites_list) && is_array($favorites_list)){
                $favorites_id = array();//收藏的店铺编号
                foreach ($favorites_list as $key=>$favorites){
                    $fav_id = $favorites['fav_id'];
                    $favorites_id[] = $favorites['fav_id'];
                    $favorites_key[$fav_id] = $key;
                }
                $store_model = Model('store');
                $store_list = $store_model->getStoreList(array('store_id'=>array('in', $favorites_id)));
                if (!empty($store_list) && is_array($store_list)){
                    foreach ($store_list as $key=>$val){
                        if ($val['store_id'] == $store_id) {
                            $store_info = $val;
                        } else {
                            $key = $favorites_key[$val['store_id']];
                            $favorites_list[$key]['store'] = $val;
                        }
                    }
                }
            }
        
            if (empty($store_info)) {
                showMessage('参数错误', '', 'html', 'error');
            }
            Tpl::output('store_info', $store_info);
            Tpl::output('favorites_list', $favorites_list);
        }
        
        $model_goods = Model('goods');
        $fieldstr = "goods_id,goods_name,goods_promotion_price,goods_image,goods_addtime";

        $condition = array();
        $condition['store_id'] = $store_id;
        $order = 'goods_id desc';
        switch ($_GET['sign']) {
            case 'new': // 新品
                $order = 'goods_id desc';
                break;
            case 'promotion':   // 促销
                $order = 'goods_id desc';
                $condition['goods_promotion_type'] = array('neq', 0);
                break;
            case 'hot': // 热销
                $order = 'goods_salenum desc';
                break;
        }
        
        $goods_list = $model_goods->getGoodsListByColorDistinct($condition, $fieldstr, $order, 60);
        Tpl::output('goods_list', $goods_list);
        
        // 计算新品、促销、热销、商品数量
        $model_goods->cls();
        $new_count = $salenum_count = $model_goods->getGoodsOnlineCount(array('store_id' => $store_id));
        $promotion_count = $model_goods->getGoodsOnlineCount(array('store_id' => $store_id, 'goods_promotion_type' => array('neq', 0)));
        Tpl::output('count', array('new' => $new_count, 'promotion' => $promotion_count, 'hot' => $salenum_count));
        

        $total_page = pagecmd('gettotalpage');
        if (intval($_GET['curpage'] > $total_page)) {
            exit();
        }
        Tpl::output('total_page', $total_page);
        if (!empty($_GET['curpage'])) {
            Tpl::showpage('favorites_store_index.more_item','null_layout');
        } else {

            self::profile_menu('favorites','more');
            Tpl::showpage('favorites_store_index.more');
        }
    }
    /**
     * 删除收藏
     *
     * @param
     * @return
     */
    public function delfavoritesOp(){
        if (!$_GET['fav_id'] || !$_GET['type']){
            showDialog(Language::get('member_favorite_del_fail'),'','error');
        }
        if (!preg_match_all('/^[0-9,]+$/',$_GET['fav_id'], $matches)) {
            showDialog(Language::get('wrong_argument'),'','error');
        }
        $fav_id = trim($_GET['fav_id'],',');
        if (!in_array($_GET['type'], array('goods', 'store'))) {
          showDialog(Language::get('wrong_argument'),'','error');
        }
        $type = $_GET['type'];
        $favorites_model = Model('favorites');
        $fav_arr = explode(',',$fav_id);
        if (!empty($fav_arr) && is_array($fav_arr)){
            $favorites_list = $favorites_model->getFavoritesList(array('fav_id'=>array('in', $fav_arr),'fav_type'=>"$type",'member_id'=>$_SESSION['member_id']));
            if (!empty($favorites_list) && is_array($favorites_list)){
                $fav_arr = array();
                foreach ($favorites_list as $k=>$v){
                    $fav_arr[] = $v['fav_id'];
                }
                $result = $favorites_model->delFavorites(array('fav_id'=>array('in', $fav_arr),'fav_type'=>"$type",'member_id'=>"{$_SESSION['member_id']}"));
                if (!empty($fav_arr) && $result){
                    //更新收藏数量
                    $store_model = Model('store');
                    $store_model->editStore(array('store_collect'=>array('exp', 'store_collect - 1')),array('store_id'=>array('in', $fav_arr)));
                    showDialog(Language::get('favorite_del_success'),'index.php?act=member_favorite_store&op=fslist','succ');
                }
            }else {
                showDialog(Language::get('favorite_del_fail'),'','error');
            }
        }else {
            showDialog(Language::get('member_favorite_del_fail'),'','error');
        }
    }
    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key='') {
        $menu_array = array(
            array('menu_key'=>'fav_store','menu_name'=>Language::get('nc_member_path_collect_store'), 'menu_url'=>'index.php?act=member_favorite_store&op=fslist')
        );
        if ($menu_key == 'more') {
            $menu_array[] = array('menu_key' => 'more', 'menu_name' => '收藏店铺详细');
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
