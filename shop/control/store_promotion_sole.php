<?php
/**
 * 手机专享管理
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');
class store_promotion_soleControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
        //检查是否开启
        if (intval(C('promotion_allow')) !== 1) {
            showMessage("商品促销功能尚未开启", urlShop('seller_center', 'index'),'','error');
        }
    }

    public function indexOp() {
        $this->sole_goods_listOp();
    }

    /**
     * 套餐商品列表
     */
    public function sole_goods_listOp() {
        $model_sole = Model('p_sole');
        // 更新套餐状态
        $where = array();
        $where['store_id'] = $_SESSION['store_id'];
        $where['sole_quota_endtime'] = array('lt', TIMESTAMP);
        $model_sole->editSoleClose($where);

        $hasList = false;
        if (checkPlatformStore()) {
            Tpl::output('isOwnShop', true);
            $hasList = true;
        } else {
            // 检查是否已购买套餐
            $where = array();
            $where['store_id'] = $_SESSION['store_id'];
            $sole_quota = $model_sole->getSoleQuotaInfo($where);
            Tpl::output('sole_quota', $sole_quota);
            if (!empty($sole_quota)) {
                $hasList = true;
            }
        }

        if ($hasList) {
            // 查询已选择商品
            $solegoods_list = $model_sole->getSoleGoodsList(array('store_id' => $_SESSION['store_id']));
            if (!empty($solegoods_list)) {
                $goodsid_array = array();
                $solegoods_array = array();
                foreach ($solegoods_list as $val) {
                    $goodsid_array[] = $val['goods_id'];
                    $solegoods_array[$val['goods_id']] = $val;
                }
                $goods_list = Model('goods')->getGoodsList(array('goods_id' => array('in', $goodsid_array)), 'goods_id,goods_name,goods_image,goods_price,store_id,gc_id');
                if (!empty($goods_list)) {
                    $gcid_array = array();  // 商品分类id
                    foreach ($goods_list as $key => $val) {
                        $gcid_array[] = $val['gc_id'];
                        $goods_list[$key]['goods_image'] = thumb($val);
                        $goods_list[$key]['url'] = urlShop('goods', 'index', array('goods_id' => $val['goods_id']));
                        $goods_list[$key]['sole_price'] = $solegoods_array[$val['goods_id']]['sole_price'];
                    }
                    $goodsclass_list = Model('goods_class')->getGoodsClassListByIds($gcid_array);
                    $goodsclass_list = array_under_reset($goodsclass_list, 'gc_id');
                    Tpl::output('goods_list', $goods_list);
                    Tpl::output('goodsclass_list', $goodsclass_list);
                }
            }
        }

        $this->profile_menu('sole_goods_list', 'sole_goods_list');
        Tpl::showpage('store_promotion_sole.goods_list');
    }

    /**
     * 选择商品
     */
    public function sole_select_goodsOp() {
        $model_goods = Model('goods');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        if ($_GET['goods_name'] != '') {
            $condition['goods_name'] = array('like', '%'.$_GET['goods_name'].'%');
        }
        $goods_list = $model_goods->getGeneralGoodsList($condition, '*', 10);

        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        Tpl::showpage('store_promotion_sole.select_goods', 'null_layout');
    }

    /**
     * 购买套餐
     */
    public function sole_quota_addOp() {
        if (chksubmit()) {
            $quantity = intval($_POST['sole_quota_quantity']); // 购买数量（月）
            $price_quantity = $quantity * intval(C('promotion_sole_price')); // 扣款数
            if ($quantity <= 0 || $quantity > 12) {
                showDialog('参数错误，购买失败。', urlShop('store_promotion_sole', 'sole_quota_add'), '', 'error' );
            }
            // 实例化模型
            $model_sole = Model('p_sole');

            $data = array();
            $data['store_id']               = $_SESSION['store_id'];
            $data['store_name']             = $_SESSION['store_name'];
            $data['sole_quota_starttime']   = TIMESTAMP;
            $data['sole_quota_endtime']     = TIMESTAMP + 60 * 60 * 24 * 30 * $quantity;
            $data['sole_state']             = 1;

            $return = $model_sole->addSoleQuota($data);
            if ($return) {
                // 添加店铺费用记录
                $this->recordStoreCost($price_quantity, '购买手机专享');

                // 添加任务队列
                $end_time = TIMESTAMP + 60 * 60 * 24 * 30 * $quantity;
                Model('cron')->addCron(array('exetime' => $end_time, 'exeid' => $_SESSION['store_id'], 'type' => 10), true);
                $this->recordSellerLog('购买'.$quantity.'套手机专享，单价'.intval(C('promotion_sole_price')).'元');
                showDialog('购买成功', urlShop('store_promotion_sole', 'sole_goods_list'), 'succ');
            } else {
                showDialog('购买失败', urlShop('store_promotion_sole', 'sole_quota_add'));
            }
        }
        // 输出导航
        self::profile_menu('sole_quota_add', 'sole_quota_add');
        Tpl::showpage('store_promotion_sole.quota_add');
    }

    /**
     * 套餐续费
     */
    public function sole_renewOp() {
        if (chksubmit()) {
            $model_sole = Model('p_sole');
            $quantity = intval($_POST['sole_quota_quantity']); // 购买数量（月）
            $price_quantity = $quantity * intval(C('promotion_sole_price')); // 扣款数
            if ($quantity <= 0 || $quantity > 12) {
                showDialog('参数错误，购买失败。', urlShop('store_promotion_sole', 'sole_quota_add'), '', 'error' );
            }
            $where = array();
            $where['store_id'] = $_SESSION ['store_id'];
            $sole_quota = $model_sole->getSoleQuotaInfo($where);
            if ($sole_quota['sole_quota_endtime'] > TIMESTAMP) {
                // 套餐未超时(结束时间+购买时间)
                $update['sole_quota_endtime']   = intval($sole_quota['sole_quota_endtime']) + 60 * 60 * 24 * 30 * $quantity;
            } else {
                // 套餐已超时(当前时间+购买时间)
                $update['sole_quota_endtime']   = TIMESTAMP + 60 * 60 * 24 * 30 * $quantity;
            }
            $return = $model_sole->editSoleQuotaOpen($update, $where);

            if ($return) {
                // 添加店铺费用记录
                $this->recordStoreCost($price_quantity, '购买手机专享');

                // 添加任务队列
                $end_time = TIMESTAMP + 60 * 60 * 24 * 30 * $quantity;
                Model('cron')->addCron(array('exetime' => $end_time, 'exeid' => $_SESSION['store_id'], 'type' => 10), true);
                $this->recordSellerLog('续费'.$quantity.'套手机专享，单价'.intval(C('promotion_sole_price')).'元');
                showDialog('购买成功', urlShop('store_promotion_sole', 'sole_list'), 'succ');
            } else {
                showDialog('购买失败', urlShop('store_promotion_sole', 'sole_quota_add'));
            }
        }

        self::profile_menu('sole_renew', 'sole_renew');
        Tpl::showpage('store_promotion_sole.quota_add');
    }

    /**
     * 选择商品
     */
    public function choosed_goodsOp() {
        $gid = $_GET['gid'];
        if ($gid <= 0) {
            Tpl::output('error', '参数错误');
        }
        $goods_info = Model('goods')->getGoodsInfoByID($gid);
        if (empty($goods_info) || $goods_info['store_id'] != $_SESSION['store_id']) {
            Tpl::output('error', '参数错误');
        }
        Tpl::output('goods_info', $goods_info);
        
        $solegoods_info = Model('p_sole')->getSoleGoodsInfo(array('store_id' => $_SESSION['store_id'], 'goods_id' => $gid));
        Tpl::output('solegoods_info', $solegoods_info);
        
        Tpl::showpage('store_promotion_sole.choosed_goods', 'null_layout');
    }
    
    /**
     * 保存选择商品
     */
    public function choosed_goods_saveOp() {
        $model_sole = Model('p_sole');
        if (!checkPlatformStore()) {
            // 验证套餐时候过期
            $sole_info = $model_sole->getSoleQuotaInfo(array('store_id' => $_SESSION['store_id'], 'sole_quota_endtime' => array('gt', TIMESTAMP)), 'sole_quota_id');
            if (empty($sole_info)) {
                showDialog('套餐过期，请重新购买套餐');
            }
        }
        
        $gid = intval($_POST['gid']);
        $sole_price = ncPriceFormat(floatval($_POST['sole_price']));
        if ($gid <= 0) {
            showDialog('参数错误', 'reload');
        }

        // 验证商品是否存在
        $goods_info = Model('goods')->getGoodsInfoByID($gid);
        if (empty($goods_info) || $goods_info['store_id'] != $_SESSION['store_id']) {
            showDialog('参数错误', 'reload');
        }
        // 验证手机价格是否为最低价格
        if ($sole_price > $goods_info['goods_promotion_price']) {
            showDialog('专享价格不能超过手机实际售价。');
        }

        $param = array();
        $param['store_id']     = $_SESSION['store_id'];
        $param['goods_id']     = $goods_info['goods_id'];
        $param['sole_price']   = $sole_price;
        $param['gc_id']        = $goods_info['gc_id'];
        
        $solegoods_info = $model_sole->getSoleGoodsInfo(array('store_id' => $_SESSION['store_id'], 'goods_id' => $goods_info['goods_id']));
        if (empty($solegoods_info)) {
            // 保存到推荐展位商品表
            $model_sole->addSoleGoods($param);
            $this->recordSellerLog('添加手机专享商品，商品id：'.$goods_info['goods_id']);
        } else {
            $model_sole->editSoleGoods($param, array('sole_goods_id' => $solegoods_info['sole_goods_id']));
            $this->recordSellerLog('编辑手机专享商品，商品id：'.$goods_info['goods_id']);
        }


        $goods_class = Model('goods_class')->getGoodsClassInfoById($goods_info['gc_id']);
        // 输出商品信息
        $goods_info['goods_image']  = thumb($goods_info);
        $goods_info['url']          = urlShop('goods', 'index', array('goods_id' => $goods_info['goods_id']));
        $goods_info['gc_name']      = $goods_class['gc_name'];
        $goods_info['sole_price']   = $sole_price;
        $goods_info['result']       = 'true';
        if (empty($solegoods_info)) {
            showDialog('添加手机专享促销成功', '', 'succ', 'CUR_DIALOG.close();insert_data(' . json_encode($goods_info) . ');');
        } else {
            showDialog('编辑手机专享促销成功', '', 'succ', 'CUR_DIALOG.close();update_data(' . json_encode($goods_info) . ');');
        }
    }

    /**
     * 删除选择商品
     */
    public function del_choosed_goodsOp() {
        $gid = $_GET['gid'];
        if ($gid <= 0) {
            $data = array('result' => 'false', 'msg' => '参数错误');
            $this->_echoJson($data);
        }

        $result = Model('p_sole')->delSoleGoods(array('goods_id' => $gid, 'store_id' => $_SESSION['store_id']));
        if ($result) {
            $this->recordSellerLog('删除手机专享商品，商品id：'.$gid);
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
            case 'sole_goods_list':
                $menu_array = array(
                    1=>array('menu_key'=>'sole_goods_list', 'menu_name'=>'商品列表', 'menu_url'=>urlShop('store_promotion_sole', 'sole_goods_list'))
                );
                break;
            case 'sole_quota_add':
                $menu_array = array(
                    1=>array('menu_key'=>'sole_goods_list', 'menu_name'=>'商品列表', 'menu_url'=>urlShop('store_promotion_sole', 'sole_goods_list')),
                    2=>array('menu_key'=>'sole_quota_add', 'menu_name'=>'购买套餐', 'menu_url'=>urlShop('store_promotion_sole', 'sole_quota_add'))
                );
                break;
            case 'sole_renew':
                $menu_array = array(
                    1=>array('menu_key'=>'sole_goods_list', 'menu_name'=>'商品列表', 'menu_url'=>urlShop('store_promotion_sole', 'sole_goods_list')),
                    2=>array('menu_key'=>'sole_renew', 'menu_name'=>'套餐续费', 'menu_url'=>urlShop('store_promotion_sole', 'sole_renew'))
                );
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
