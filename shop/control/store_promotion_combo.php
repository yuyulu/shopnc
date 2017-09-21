<?php
/**
 * 预定商品管理
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');
class store_promotion_comboControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
        //检查是否开启
        if (intval(C('promotion_allow')) !== 1) {
            showMessage("商品促销功能尚未开启", urlShop('seller_center', 'index'), '', 'error');
        }
    }

    public function indexOp() {
        $this->combo_listOp();
    }

    /**
     * 推荐组合商品列表
     */
    public function combo_listOp() {
        $model_combo = Model('p_combo_quota');
        $hasList = false;
        if (checkPlatformStore()) {
            Tpl::output('isOwnShop', true);
            $hasList = true;
        } else {
            // 检查是否已购买套餐
            $where = array();
            $where['store_id'] = $_SESSION['store_id'];
            $combo_quota = $model_combo->getComboQuotaInfo($where);
            Tpl::output('combo_quota', $combo_quota);
            if (!empty($combo_quota)) {
                $hasList = true;
            }
        }
        
        if ($hasList) {
            $model_combo_goods = Model('p_combo_goods');
            $combo_list = $model_combo_goods->getComboGoodsList(array('store_id' => $_SESSION['store_id']), 'distinct goods_id', 10, ' goods_id desc');
            if (!empty($combo_list)) {
                $goodsid_array = array();
                foreach ($combo_list as $val) {
                    $goodsid_array[] = $val['goods_id'];
                }
                $goods_list = Model('goods')->getGoodsList(array('goods_id' => array('in', $goodsid_array)));
                Tpl::output('goods_list', $goods_list);
            }
            Tpl::output('show_page', $model_combo_goods->showpage());
        }
        
        $this->profile_menu('combo_goods_list', 'combo_goods_list');
        Tpl::showpage('store_promotion_combo.goods_list');
    }
    
    /**
     * 保存推荐组合
     */
    public function save_comboOp() {
        if (! chksubmit()) {
            showDialog(L('wrong_argument'));
        }
        $goods_id = intval($_POST['goods_id']);
        if ($goods_id <= 0) {
            showDialog(L('wrong_argument'));
        }
        
        $model_goods = Model('goods');
        $model_combo_goods = Model('p_combo_goods');
        
        // 验证
        $goods_info = $model_goods->getGoodsOnlineInfoByID($goods_id, 'goods_id,goods_commonid,store_id');
        // 商品是否存在，及是否为本店铺商品
        if (empty($goods_info) || $goods_info['store_id'] != $_SESSION['store_id']) {
            showDialog(L('wrong_argument'));
        }
        // 验证商品是否为普通商品
        if (!$model_goods->checkIsGeneral($goods_info)) {
            showDialog('只有普通商品才能添加推荐组合');
        }
        
        // 删除该商品原有推荐组合
        $model_combo_goods->delComboGoodsByGoodsId($goods_id);
        
        $insert = array();
        $data = $_POST['combo'];
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                $val = array_unique($val);
                foreach ($val as $v) {
                    $combo_gid = intval($v); // 被推荐的商品id
                    // 验证推荐组合商品是否为本店铺商品，如果不是本店商品继续下一个循环
                    $combo_info = $model_goods->getGoodsInfoByID($combo_gid, 'store_id,is_virtual,is_fcode,is_presell');
                    $is_general = $model_goods->checkIsGeneral($combo_info); // 验证是否为普通商品
                    if ($combo_info['store_id'] != $_SESSION['store_id'] || $is_general == false || $goods_id == $combo_gid) {
                        continue;
                    }
                    $array = array();
                    $array['cg_class'] = $_POST['class'][$key];
                    $array['goods_id'] = $goods_id;
                    $array['goods_commonid'] = $goods_info['goods_commonid'];
                    $array['store_id'] = $_SESSION['store_id'];
                    $array['combo_goodsid'] = $combo_gid;
                    $insert[] = $array;
                }
            }
            // 插入数据
            $model_combo_goods->addComboGoodsAll($insert);
        }
        showDialog(L('nc_common_save_succ'), 'reload', 'succ');
    }

    /**
     * 购买套餐
     */
    public function combo_quota_addOp() {
        if (chksubmit()) {
            $quantity = intval($_POST['combo_quota_quantity']); // 购买数量（月）
            $price_quantity = $quantity * intval(C('promotion_combo_price')); // 扣款数
            if ($quantity <= 0 || $quantity > 12) {
                showDialog('参数错误，购买失败。', urlShop('store_promotion_combo', 'combo_quota_add'), '', 'error' );
            }
            // 实例化模型
            $model_combo = Model('p_combo_quota');

            $data = array();
            $data['store_id']        = $_SESSION['store_id'];
            $data['store_name']      = $_SESSION['store_name'];
            $data['cq_starttime']   = TIMESTAMP;
            $data['cq_endtime']     = TIMESTAMP + 60 * 60 * 24 * 30 * $quantity;

            $return = $model_combo->addComboQuota($data);
            if ($return) {
                // 添加店铺费用记录
                $this->recordStoreCost($price_quantity, '购买推荐组合活动');

                $this->recordSellerLog('购买'.$quantity.'套推荐组合活动，单价'.intval(C('promotion_combo_price')).'元');
                showDialog('购买成功', urlShop('store_promotion_combo', 'combo_goods_list'), 'succ');
            } else {
                showDialog('购买失败', urlShop('store_promotion_combo', 'combo_quota_add'));
            }
        }
        // 输出导航
        $this->profile_menu('combo_quota_add', 'combo_quota_add');
        Tpl::showpage('store_promotion_combo.quota_add');
    }

    /**
     * 套餐续费
     */
    public function combo_renewOp() {
        if (chksubmit()) {
            $model_combo = Model('p_combo_quota');
            $quantity = intval($_POST['combo_quota_quantity']); // 购买数量（月）
            $price_quantity = $quantity * intval(C('promotion_combo_price')); // 扣款数
            if ($quantity <= 0 || $quantity > 12) {
                showDialog('参数错误，购买失败。', urlShop('store_promotion_combo', 'combo_quota_add'), '', 'error');
            }
            $where = array();
            $where['store_id'] = $_SESSION ['store_id'];
            $combo_quota = $model_combo->getComboQuotaInfo($where);
            if ($combo_quota['cq_endtime'] > TIMESTAMP) {
                // 套餐未超时(结束时间+购买时间)
                $update['cq_endtime']   = intval($combo_quota['cq_endtime']) + 60 * 60 * 24 * 30 * $quantity;
            } else {
                // 套餐已超时(当前时间+购买时间)
                $update['cq_endtime']   = TIMESTAMP + 60 * 60 * 24 * 30 * $quantity;
            }
            $return = $model_combo->editComboQuota($update, $where);

            if ($return) {
                // 添加店铺费用记录
                $this->recordStoreCost($price_quantity, '购买推荐组合活动');

                $this->recordSellerLog('续费'.$quantity.'套推荐组合活动，单价'.intval(C('promotion_bundling_price')).'元');
                showDialog('购买成功', urlShop('store_promotion_combo', 'combo_list'), 'succ');
            } else {
                showDialog('购买失败', urlShop('store_promotion_combo', 'combo_quota_add'));
            }
        }

        $this->profile_menu('combo_renew', 'combo_renew');
        Tpl::showpage('store_promotion_combo.quota_add');
    }
    
    /**
     * 选择商品
     */
    public function combo_select_goodsOp() {
        $model_goods = Model('goods');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        if ($_GET['goods_name'] != '') {
            $condition['goods_name'] = array('like', '%'.$_GET['goods_name'].'%');
        }
        $goods_list = $model_goods->getGeneralGoodsList($condition, '*', 10);
    
        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        Tpl::showpage('store_promotion_combo.select_goods', 'null_layout');
    }

    /**
     * 选择商品
     */
    public function choosed_goodsOp() {
        $model_combo = Model('p_combo_quota');
        if (!checkPlatformStore()) {
            // 验证套餐时候过期
            $combo_info = $model_combo->getComboQuotaInfo(array('store_id' => $_SESSION['store_id'], 'cq_endtime' => array('gt', TIMESTAMP)));
            if (empty($combo_info)) {
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
                showDialog('参数错误', '', 'succ', 'CUR_DIALOG.close();');
            }
            Tpl::output('error', '参数错误，该商品不能参加推荐展位');
        }
        $goods_info['is_general'] = $model_goods->checkIsGeneral($goods_info);
        Tpl::output('goods_info', $goods_info);
        
        $model_combo_goods = Model('p_combo_goods');
        $combo_list = $model_combo_goods->getComboGoodsCacheByGoodsId($gid);
        $combo_list = unserialize($combo_list['gcombo_list']);
        Tpl::output('combo_list', $combo_list);

        $this->profile_menu('combo_goods_add', 'combo_goods_add');
        Tpl::showpage('store_promotion_combo.choosed_goods');
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
        $result = Model('p_combo_goods')->delComboGoodsByGoodsId($gid);
        if ($result) {
            $this->recordSellerLog('删除推荐组合商品，商品id：'.$gid);
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
     *
     */
    private function profile_menu($menu_type, $menu_key = '')
    {
        $menu_array = array();
        switch ($menu_type) {
            case 'combo_goods_list':
                $menu_array = array(
                    1=>array('menu_key'=>'combo_goods_list', 'menu_name'=>'商品列表', 'menu_url'=>urlShop('store_promotion_combo', 'combo_goods_list'))
                );
                break;
            case 'combo_goods_add':
                $menu_array = array(
                    1=>array('menu_key'=>'combo_goods_list', 'menu_name'=>'商品列表', 'menu_url'=>urlShop('store_promotion_combo', 'combo_goods_list')),
                    2=>array('menu_key'=>'combo_goods_add', 'menu_name'=>'设置组合', 'menu_url'=>'javascript:;')
                );
                break;
            case 'combo_quota_add':
                $menu_array = array(
                    1=>array('menu_key'=>'combo_goods_list', 'menu_name'=>'商品列表', 'menu_url'=>urlShop('store_promotion_combo', 'combo_goods_list')),
                    2=>array('menu_key'=>'combo_quota_add', 'menu_name'=>'购买套餐', 'menu_url'=>urlShop('store_promotion_combo', 'combo_quota_add'))
                );
                break;
            case 'combo_renew':
                $menu_array = array(
                    1=>array('menu_key'=>'combo_goods_list', 'menu_name'=>'商品列表', 'menu_url'=>urlShop('store_promotion_combo', 'combo_goods_list')),
                    2=>array('menu_key'=>'combo_renew', 'menu_name'=>'套餐续费', 'menu_url'=>urlShop('store_promotion_combo', 'combo_renew'))
                );
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
