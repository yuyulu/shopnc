<?php
/**
 * 预定商品管理
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');
class store_promotion_bookControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
        //检查是否开启
        if (intval(C('promotion_allow')) !== 1) {
            showMessage("商品促销功能尚未开启", urlShop('seller_center', 'index'),'','error');
        }
    }

    public function indexOp() {
        $this->book_listOp();
    }
    
    /**
     * 预售商品礼包
     */
    public function book_listOp() {
        $model_book = Model('p_book');
        $hasList = false;
        if (checkPlatformStore()) {
            Tpl::output('isOwnShop', true);
            $hasList = true;
        } else {
            // 检查是否已购买套餐
            $where = array();
            $where['store_id'] = $_SESSION['store_id'];
            $book_quota = $model_book->getBookQuotaInfo($where);
            Tpl::output('book_quota', $book_quota);
            if (!empty($book_quota)) {
                $hasList = true;
            }
        }
        
        if ($hasList) {
            $goods_list = $model_book->getAllGoodsList(array('store_id' => $_SESSION['store_id']), '*', null);
            if (!empty($goods_list)) {
                $gcid_array = array();  // 商品分类id
                foreach ($goods_list as $key => $val) {
                    $gcid_array[] = $val['gc_id'];
                    $goods_list[$key]['goods_image'] = thumb($val);
                    $goods_list[$key]['url'] = urlShop('goods', 'index', array('goods_id' => $val['goods_id']));
                }
                $goodsclass_list = Model('goods_class')->getGoodsClassListByIds($gcid_array);
                $goodsclass_list = array_under_reset($goodsclass_list, 'gc_id');

                Tpl::output('goods_list', $goods_list);
                Tpl::output('goodsclass_list', $goodsclass_list);
            }
        }
        
        $this->profile_menu('book_goods_list', 'book_goods_list');
        Tpl::showpage('store_promotion_book.goods_list');
    }

    /**
     * 购买套餐
     */
    public function book_quota_addOp() {
        if (chksubmit()) {
            $quantity = intval($_POST['book_quota_quantity']); // 购买数量（月）
            $price_quantity = $quantity * intval(C('promotion_book_price')); // 扣款数
            if ($quantity <= 0 || $quantity > 12) {
                showDialog('参数错误，购买失败。', urlShop('store_promotion_book', 'book_quota_add'), '', 'error' );
            }
            // 实例化模型
            $model_book = Model('p_book');

            $data = array();
            $data['store_id']        = $_SESSION['store_id'];
            $data['store_name']      = $_SESSION['store_name'];
            $data['bkq_starttime']   = TIMESTAMP;
            $data['bkq_endtime']     = TIMESTAMP + 60 * 60 * 24 * 30 * $quantity;

            $return = $model_book->addBookQuota($data);
            if ($return) {
                // 添加店铺费用记录
                $this->recordStoreCost($price_quantity, '购买预定商品活动');

                $this->recordSellerLog('购买'.$quantity.'套预定商品活动，单价'.intval(C('promotion_book_price')).'元');
                showDialog('购买成功', urlShop('store_promotion_book', 'book_goods_list'), 'succ');
            } else {
                showDialog('购买失败', urlShop('store_promotion_book', 'book_quota_add'));
            }
        }
        // 输出导航
        $this->profile_menu('book_quota_add', 'book_quota_add');
        Tpl::showpage('store_promotion_book.quota_add');
    }

    /**
     * 套餐续费
     */
    public function book_renewOp() {
        if (chksubmit()) {
            $model_book = Model('p_book');
            $quantity = intval($_POST['book_quota_quantity']); // 购买数量（月）
            $price_quantity = $quantity * intval(C('promotion_book_price')); // 扣款数
            if ($quantity <= 0 || $quantity > 12) {
                showDialog('参数错误，购买失败。', urlShop('store_promotion_book', 'book_quota_add'), '', 'error' );
            }
            $where = array();
            $where['store_id'] = $_SESSION ['store_id'];
            $book_quota = $model_book->getBookQuotaInfo($where);
            if ($book_quota['bkq_endtime'] > TIMESTAMP) {
                // 套餐未超时(结束时间+购买时间)
                $update['bkq_endtime']   = intval($book_quota['bkq_endtime']) + 60 * 60 * 24 * 30 * $quantity;
            } else {
                // 套餐已超时(当前时间+购买时间)
                $update['bkq_endtime']   = TIMESTAMP + 60 * 60 * 24 * 30 * $quantity;
            }
            $return = $model_book->editBookQuota($update, $where);

            if ($return) {
                // 添加店铺费用记录
                $this->recordStoreCost($price_quantity, '购买预定商品活动');

                $this->recordSellerLog('续费'.$quantity.'套预定商品活动，单价'.intval(C('promotion_book_price')).'元');
                showDialog('购买成功', urlShop('store_promotion_book', 'book_list'), 'succ');
            } else {
                showDialog('购买失败', urlShop('store_promotion_book', 'book_quota_add'));
            }
        }

        $this->profile_menu('book_renew', 'book_renew');
        Tpl::showpage('store_promotion_book.quota_add');
    }
    
    /**
     * 选择商品
     */
    public function book_select_goodsOp() {
        $model_goods = Model('goods');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        if ($_GET['goods_name'] != '') {
            $condition['goods_name'] = array('like', '%'.$_GET['goods_name'].'%');
        }
        $goods_list = $model_goods->getGeneralGoodsList($condition, '*', 10);
    
        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        Tpl::showpage('store_promotion_book.select_goods', 'null_layout');
    }

    /**
     * 选择商品
     */
    public function choosed_goodsOp() {
        $model_book = Model('p_book');
        if (!checkPlatformStore()) {
            // 验证套餐时候过期
            $book_info = $model_book->getBookQuotaInfo(array('store_id' => $_SESSION['store_id'], 'bkq_endtime' => array('gt', TIMESTAMP)));
            if (empty($book_info)) {
                if ($_GET['inajax']) {
                    showDialog('套餐过期请重新购买套餐', '', 'succ', 'CUR_DIALOG.close();');
                }
                Tpl::output('error', '套餐过期请重新购买套餐');
            }
        }
        $gid = intval($_REQUEST['gid']);
        if ($gid <= 0) {
            if ($_GET['inajax']) {
                showDialog('参数错误', '', 'succ', 'CUR_DIALOG.close();');
            }
            Tpl::output('error', '参数错误');
        }
        
        $model_goods = Model('goods');
        // 验证商品是否存在
        $goods_info = $model_goods->getGoodsInfoByID($gid);
        if (empty($goods_info) || $goods_info['store_id'] != $_SESSION['store_id'] || !$model_goods->checkIsGeneral($goods_info)) {
            if ($_GET['inajax']) {
                showDialog('参数错误，或该商品已经添加过活动', '', 'succ', 'CUR_DIALOG.close();');
            }
            Tpl::output('error', '参数错误，或该商品已经添加过活动');
        }
        if (chksubmit()) {
            $update = array();
            if ($_POST['type'] == 'book') {
                $update['is_book'] = 1;
                $update['book_down_payment'] = floatval($_POST['down_payment']);
                $update['book_final_payment'] = floatval($_POST['final_payment']);
                $update['book_down_time'] = strtotime($_POST['down_time']) + 86400 - 1;
                $rs = Model('p_book')->addBookGoodsByGoodsId($update, $gid);
            } else if ($_POST['type'] == 'presell') {
                $update['is_presell'] = 1;
                $update['presell_deliverdate'] = strtotime($_POST['presell_deliverdate']) + 86400 - 1;
                $rs = Model('p_book')->addPresellGoodsByGoodsId($update, $gid);
            }
            if ($rs) {
                $goodsclass_info = Model('goods_class')->getGoodsClassInfoById($goods_info['gc_id']);
                $goods_info['gc_name'] = $goodsclass_info['gc_name'];
                $goods_info['goods_image'] = thumb($goods_info, '60');
                $goods_info['url'] = urlShop('goods', 'index', array('goods_id' => $goods_info['goods_id']));
                if ($_POST['type'] == 'book') {
                    $goods_info['book_down_payment'] = floatval($_POST['down_payment']);
                    $goods_info['book_final_payment'] = floatval($_POST['final_payment']);
                    $goods_info['book_down_time'] = date('Y-m-d', $update['book_down_time']);
                } else if ($_POST['type'] == 'presell') {
                    $goods_info['presell_deliverdate'] = date('Y-m-d', $update['presell_deliverdate']);
                }
                $this->recordSellerLog('添加预定商品，商品id：'.$gid);
                showDialog('操作成功', '', 'succ', 'CUR_DIALOG.close();choose_goods('.json_encode($goods_info).', "'. $_POST['type'] .'")');
            } else {
                showDialog('操作失败', '', 'succ', 'CUR_DIALOG.close();');
            }
        }                
        Tpl::output('book_info', $book_info);

        $goodscommon_info = $model_goods->getGoodsCommonInfoByID($goods_info['goods_commonid'], 'spec_name,store_id');
        $spec_name = array_values((array)unserialize($goodscommon_info['spec_name']));
        $goods_spec = array_values((array)unserialize($goods_info['goods_spec']));
        Tpl::output('goods_spec', $goods_spec);
        Tpl::output('spec_name', $spec_name);
        Tpl::output('goods_info', $goods_info);
        Tpl::output('type', $_GET['type']);
        Tpl::showpage('store_promotion_book.choosed_goods', 'null_layout');
    }

    /**
     * 删除选择商品
     */
    public function del_choosed_goodsOp() {
        $gid = intval($_GET['gid']);
        if ($gid <= 0) {
            $data = array('result' => 'false', 'msg' => '参数错误');
            $this->_echoJson($data);
        }

        // 验证商品是否存在
        $goods_info = Model('goods')->getGoodsInfoByID($gid);
        if (empty($goods_info) || $goods_info['store_id'] != $_SESSION['store_id']) {
            $data = array('result' => 'false', 'msg' => '参数错误');
            $this->_echoJson($data);
        }
        if ($_GET['type'] == 'book') {
            $result = Model('p_book')->delBookGoodsByGoodsId($gid);
        } else if ($_GET['type'] == 'presell') {
            $result = Model('p_book')->delPresellGoodsByGoodsId($gid);
        }
        if ($result) {
            $this->recordSellerLog('删除预定商品，商品id：'.$gid);
            $data = array('result' => 'true');
        } else {
            $data = array('result' => 'false', 'msg' => '删除失败');
        }
        $this->_echoJson($data);
    }

    /**
     * 输出JSON
     * @param array $data
     */
    private function _echoJson($data) {
        if (strtoupper(CHARSET) == 'GBK'){
            $data = Language::getUTF8($data);//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
        }
        echo json_encode($data);exit();
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key='') {
        $menu_array = array();
        switch ($menu_type) {
            case 'book_goods_list':
                $menu_array = array(
                    1=>array('menu_key'=>'book_goods_list', 'menu_name'=>'商品列表', 'menu_url'=>urlShop('store_promotion_book', 'book_goods_list'))
                );
                break;
            case 'book_quota_add':
                $menu_array = array(
                    1=>array('menu_key'=>'book_goods_list', 'menu_name'=>'商品列表', 'menu_url'=>urlShop('store_promotion_book', 'book_goods_list')),
                    2=>array('menu_key'=>'book_quota_add', 'menu_name'=>'购买套餐', 'menu_url'=>urlShop('store_promotion_book', 'book_quota_add'))
                );
                break;
            case 'book_renew':
                $menu_array = array(
                    1=>array('menu_key'=>'book_goods_list', 'menu_name'=>'商品列表', 'menu_url'=>urlShop('store_promotion_book', 'book_goods_list')),
                    2=>array('menu_key'=>'book_renew', 'menu_name'=>'套餐续费', 'menu_url'=>urlShop('store_promotion_book', 'book_renew'))
                );
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
