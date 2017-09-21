<?php
/**
 * 会员中心--收藏功能
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class member_favorite_goodsControl extends BaseMemberControl{
    public function __construct(){
        parent::__construct();
        Language::read('member_layout,member_member_favorites');
    }
    public function indexOp() {
        $this->fglistOp();
    }
    /**
     * 增加商品收藏
     */
    public function favoritegoodsOp(){
        $fav_id = intval($_GET['fid']);
        if ($fav_id <= 0){
            echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_collect_fail','UTF-8')));
            die;
        }
        $favorites_model = Model('favorites');
        //判断是否已经收藏
        $favorites_info = $favorites_model->getOneFavorites(array('fav_id'=>"$fav_id",'fav_type'=>'goods','member_id'=>"{$_SESSION['member_id']}"));
        if(!empty($favorites_info)){
            echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_already_favorite_goods','UTF-8')));
            die;
        }
        //判断商品是否为当前会员所有
        $goods_model = Model('goods');
        $goods_info = $goods_model->getGoodsInfoByID($fav_id, 'store_id');
        if ($goods_info['store_id'] == $_SESSION['store_id']){
            echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_no_my_product','UTF-8')));
            die;
        }
        //添加收藏
        $insert_arr = array();
        $insert_arr['member_id'] = $_SESSION['member_id'];
        $insert_arr['member_name'] = $_SESSION['member_name'];
        $insert_arr['fav_id'] = $fav_id;
        $insert_arr['fav_type'] = 'goods';
        $insert_arr['fav_time'] = time();
        $result = $favorites_model->addFavorites($insert_arr);
        if ($result){
            //增加收藏数量
            $goods_model->editGoodsById(array('goods_collect' => array('exp', 'goods_collect + 1')), $fav_id);
            echo json_encode(array('done'=>true,'msg'=>Language::get('favorite_collect_success','UTF-8')));
            die;
        }else{
            echo json_encode(array('done'=>false,'msg'=>Language::get('favorite_collect_fail','UTF-8')));
            die;
        }
    }

    /**
     * 商品收藏列表
     *
     * @param
     * @return
     */
    public function fglistOp(){
        $favorites_model = Model('favorites');
        $show = $_GET['show'];
        $men_key = 'fav_goods';
        $show_array = array(
            'pic'=>array(
                'favorites_goods_picshowlist',
                'fav_goods'
            ),
            'store'=>array(
                'favorites_goods_shoplist',
                'fav_goodsstore'
            )
        );
        $show = (array_key_exists($show,$show_array)) ? $show : 'pic';
        $show_type = $show_array[$show][0];
        $men_key = $show_array[$show][1];

        $condition = array();
        $condition['member_id'] = $_SESSION['member_id'];
        $keyword_type = array('store_name','log_msg','goods_name');
        if (trim($_GET['key']) != '' && in_array($_GET['type'],$keyword_type)){
            $type = $_GET['type'];
            $condition[$type] = array('like','%'.$_GET['key'].'%');
        }
        $favorites_list = $favorites_model->getGoodsFavoritesList($condition, '*', 60);
        Tpl::output('show_page',$favorites_model->showpage(2));
        if (!empty($favorites_list) && is_array($favorites_list)){
            $favorites_id = array();//收藏的商品编号
            foreach ($favorites_list as $key=>$favorites){
                $fav_id = $favorites['fav_id'];
                $favorites_id[] = $favorites['fav_id'];
                $favorites_key[$fav_id] = $key;
            }
            $goods_model = Model('goods');
            $fields = 'goods.goods_id,goods.goods_name,goods.store_id,goods.goods_image,goods.goods_promotion_price,goods.goods_state,goods.goods_verify,goods.evaluation_count,goods.goods_salenum,goods.goods_collect,'
                        .'goods.is_virtual,goods.is_fcode,goods.is_presell,goods.is_book,'
                        .'store.store_name,store.member_id,store.member_name,store.store_qq,store.store_ww,store.store_domain,store.store_avatar';
            $goods_list = $goods_model->getGoodsStoreList(array('goods_id' => array('in', $favorites_id)), $fields);
            $store_array = array();//店铺编号
            if (!empty($goods_list) && is_array($goods_list)){
                $store_goods_list = array();//店铺为分组的商品
                foreach ($goods_list as $key=>$fav){
                    $fav['state'] = $goods_model->checkOnline($fav);
                    $fav_id = $fav['goods_id'];
                    $fav['goods_member_id'] = $fav['member_id'];
                    $key = $favorites_key[$fav_id];
                    $favorites_list[$key]['goods'] = $fav;
                    $store_id = $fav['store_id'];
                    if (!in_array($store_id,$store_array)) $store_array[] = $store_id;
                    $store_goods_list[$store_id][] = $favorites_list[$key];
                }
            }
            $store_favorites = array();//店铺收藏信息
            $voucher_template = array();
            if (!empty($store_array) && is_array($store_array)){
                $store_list = $favorites_model->getStoreFavoritesList(array('member_id'=>$_SESSION['member_id'], 'fav_id'=> array('in', $store_array)));
                if (!empty($store_list) && is_array($store_list)){
                    foreach ($store_list as $key=>$val){
                        $store_id = $val['fav_id'];
                        $store_favorites[] = $store_id;
                    }
                }
                $condition = array();
                $condition['voucher_t_gettype'] = 3;
                $condition['voucher_t_state'] = 1;
                $condition['voucher_t_end_date'] = array('gt', time());
                $condition['voucher_t_mgradelimit'] = array('elt', $this->member_info['level']);
                $condition['voucher_t_store_id'] = array('in', $store_array);
                $voucher_template = Model('voucher')->getVoucherTemplateList($condition);
                $voucher_template = array_under_reset($voucher_template, 'voucher_t_store_id', 2);
            }
        }
        $total_page = pagecmd('gettotalpage');
        if (intval($_GET['curpage'] > $total_page)) {
            exit();
        }
        Tpl::output('total_page', $total_page);
        self::profile_menu('favorites','favorites');
        Tpl::output('menu_key',$men_key);
        Tpl::output('favorites_list',$favorites_list);
        Tpl::output('store_favorites',$store_favorites);
        Tpl::output('voucher_template', $voucher_template);
        Tpl::output('store_goods_list',$store_goods_list);
        if ($show == 'pic' && !empty($_GET['curpage'])) {
            Tpl::showpage('favorites_goods_picshowlist.item','null_layout');
        } else {
            Tpl::showpage($show_type);
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
                    $goods_model = Model('goods');
                    $goods_model->editGoodsById(array('goods_collect'=>array('exp', 'goods_collect - 1')), $fav_arr);
                    showDialog(Language::get('favorite_del_success'),'index.php?act=member_favorite_goods&op=fglist&show='.$_GET['show'],'succ');
                }
            }else {
                showDialog(Language::get('favorite_del_fail'),'','error');
            }
        }else {
            showDialog(Language::get('member_favorite_del_fail'),'','error');
        }
    }
    /**
     * 收藏备注
     *
     */
    public function log_msgOp(){
        $model_favorites = Model('favorites');
        $condition = array();
        $condition['member_id'] = $_SESSION['member_id'];
        $condition['log_id'] = intval($_GET['log_id']);
        $favorites = $model_favorites->getOneFavorites($condition);
        Tpl::output('favorites',$favorites);
        if (chksubmit()) {
            if (empty($favorites)) {//检查状态,防止页面刷新不及时造成数据错误
                showDialog(Language::get('wrong_argument'),'reload','error','CUR_DIALOG.close();');
            }
            $fav_array = array();
            $fav_array['log_msg'] = $_POST['fav_msg'];
            if (empty($fav_array['log_msg'])) {
                $fav_array['log_msg'] = $favorites['log_price'];
            }
            $state = $model_favorites->editFavorites($condition, $fav_array);
            if ($state) {
                showDialog(Language::get('nc_common_save_succ'),'reload','succ','CUR_DIALOG.close();');
            } else {
                showDialog(Language::get('nc_common_save_fail'),'reload','error','CUR_DIALOG.close();');
            }
        }
        Tpl::showpage('favorites_goods_msg','null_layout');
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
            array('menu_key'=>'fav_goods','menu_name'=>Language::get('nc_member_path_collect_list'), 'menu_url'=>'index.php?act=member_favorite_goods&op=fglist'),
            array('menu_key'=>'fav_goodsstore','menu_name'=>'同店商品', 'menu_url'=>'index.php?act=member_favorite_goods&op=fglist&show=store'),
        );
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
