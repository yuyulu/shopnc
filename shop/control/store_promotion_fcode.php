<?php
/**
 * F码商品管理
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');
class store_promotion_fcodeControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
        //检查是否开启
        if (intval(C('promotion_allow')) !== 1) {
            showMessage("商品促销功能尚未开启", urlShop('seller_center', 'index'),'','error');
        }
    }

    public function indexOp() {
        $this->fcode_listOp();
    }
    
    public function fcode_listOp() {
        $model_fcode = Model('p_fcode');
        $hasList = false;
        if (checkPlatformStore()) {
            Tpl::output('isOwnShop', true);
            $hasList = true;
        } else {
            // 检查是否已购买套餐
            $where = array();
            $where['store_id'] = $_SESSION['store_id'];
            $fcode_quota = $model_fcode->getFCodeQuotaInfo($where);
            Tpl::output('fcode_quota', $fcode_quota);
            if (!empty($fcode_quota)) {
                $hasList = true;
            }
        }
        
        if ($hasList) {
            $goods_list = $model_fcode->getFCodeGoodsList(array('store_id' => $_SESSION['store_id']), '*', null);
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
        
        $this->profile_menu('fcode_goods_list', 'fcode_goods_list');
        Tpl::showpage('store_promotion_fcode.goods_list');
    }

    /**
     * 购买套餐
     */
    public function fcode_quota_addOp() {
        if (chksubmit()) {
            $quantity = intval($_POST['fcode_quota_quantity']); // 购买数量（月）
            $price_quantity = $quantity * intval(C('promotion_fcode_price')); // 扣款数
            if ($quantity <= 0 || $quantity > 12) {
                showDialog('参数错误，购买失败。', urlShop('store_promotion_fcode', 'fcode_quota_add'), '', 'error' );
            }
            // 实例化模型
            $model_fcode = Model('p_fcode');

            $data = array();
            $data['store_id']        = $_SESSION['store_id'];
            $data['store_name']      = $_SESSION['store_name'];
            $data['fcq_starttime']   = TIMESTAMP;
            $data['fcq_endtime']     = TIMESTAMP + 60 * 60 * 24 * 30 * $quantity;

            $return = $model_fcode->addFCodeQuota($data);
            if ($return) {
                // 添加店铺费用记录
                $this->recordStoreCost($price_quantity, '购买F码商品活动');

                $this->recordSellerLog('购买'.$quantity.'套F码商品活动，单价'.intval(C('promotion_fcode_price')).'元');
                showDialog('购买成功', urlShop('store_promotion_fcode', 'fcode_goods_list'), 'succ');
            } else {
                showDialog('购买失败', urlShop('store_promotion_fcode', 'fcode_quota_add'));
            }
        }
        // 输出导航
        $this->profile_menu('fcode_quota_add', 'fcode_quota_add');
        Tpl::showpage('store_promotion_fcode.quota_add');
    }

    /**
     * 套餐续费
     */
    public function fcode_renewOp() {
        if (chksubmit()) {
            $model_fcode = Model('p_fcode');
            $quantity = intval($_POST['fcode_quota_quantity']); // 购买数量（月）
            $price_quantity = $quantity * intval(C('promotion_fcode_price')); // 扣款数
            if ($quantity <= 0 || $quantity > 12) {
                showDialog('参数错误，购买失败。', urlShop('store_promotion_fcode', 'fcode_quota_add'), '', 'error' );
            }
            $where = array();
            $where['store_id'] = $_SESSION ['store_id'];
            $fcode_quota = $model_fcode->getFCodeQuotaInfo($where);
            if ($fcode_quota['fcq_endtime'] > TIMESTAMP) {
                // 套餐未超时(结束时间+购买时间)
                $update['fcq_endtime']   = intval($fcode_quota['fcq_endtime']) + 60 * 60 * 24 * 30 * $quantity;
            } else {
                // 套餐已超时(当前时间+购买时间)
                $update['fcq_endtime']   = TIMESTAMP + 60 * 60 * 24 * 30 * $quantity;
            }
            $return = $model_fcode->editFCodeQuota($update, $where);

            if ($return) {
                // 添加店铺费用记录
                $this->recordStoreCost($price_quantity, '购买F码商品活动');

                $this->recordSellerLog('续费'.$quantity.'套F码商品活动，单价'.intval(C('promotion_bundling_price')).'元');
                showDialog('购买成功', urlShop('store_promotion_fcode', 'fcode_list'), 'succ');
            } else {
                showDialog('购买失败', urlShop('store_promotion_fcode', 'fcode_quota_add'));
            }
        }

        $this->profile_menu('fcode_renew', 'fcode_renew');
        Tpl::showpage('store_promotion_fcode.quota_add');
    }
    
    /**
     * 选择商品
     */
    public function fcode_select_goodsOp() {
        $model_goods = Model('goods');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        if ($_GET['goods_name'] != '') {
            $condition['goods_name'] = array('like', '%'.$_GET['goods_name'].'%');
        }
        $goods_list = $model_goods->getGeneralGoodsList($condition, '*', 10);
    
        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        Tpl::showpage('store_promotion_fcode.select_goods', 'null_layout');
    }

    /**
     * 选择商品
     */
    public function choosed_goodsOp() {
        $model_fcode = Model('p_fcode');
        if (!checkPlatformStore()) {
            // 验证套餐时候过期
            $fcode_info = $model_fcode->getFCodeQuotaInfo(array('store_id' => $_SESSION['store_id'], 'fcq_endtime' => array('gt', TIMESTAMP)));
            if (empty($fcode_info)) {
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
            $rs = Model('p_fcode')->addFCodeGoodsByGoodsId($gid);
            if ($rs) {
                // 生成F码
                QueueClient::push('createGoodsFCode', array('goods_id' => $gid, 'fc_count' => intval($_POST['g_fccount']), 'fc_prefix' => $_POST['g_fcprefix']));
                
                $goodsclass_info = Model('goods_class')->getGoodsClassInfoById($goods_info['gc_id']);
                $goods_info['gc_name'] = $goodsclass_info['gc_name'];
                $goods_info['goods_image'] = thumb($goods_info, '60');
                $goods_info['url'] = urlShop('goods', 'index', array('goods_id' => $goods_info['goods_id']));
                $this->recordSellerLog('添加F码商品，商品id：'.$gid);
                showDialog('操作成功', '', 'succ', 'CUR_DIALOG.close();choose_goods('.json_encode($goods_info).')');
            } else {
                showDialog('操作失败', '', 'succ', 'CUR_DIALOG.close();');
            }
        }
        Tpl::output('fcode_info', $fcode_info);
        

        $goodscommon_info = $model_goods->getGoodsCommonInfoByID($goods_info['goods_commonid'], 'spec_name,store_id');
        $spec_name = array_values((array)unserialize($goodscommon_info['spec_name']));
        $goods_spec = array_values((array)unserialize($goods_info['goods_spec']));
        Tpl::output('goods_spec', $goods_spec);
        Tpl::output('spec_name', $spec_name);
        Tpl::output('goods_info', $goods_info);
        Tpl::showpage('store_promotion_fcode.choosed_goods', 'null_layout');
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

        $result = Model('p_fcode')->delFCodeGoodsByGoodsId($gid);
        if ($result) {
            $this->recordSellerLog('删除F码商品，商品id：'.$gid);
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
     * 下载F码
     */
    public function download_f_code_excelOp() {
        $goods_id = $_GET['gid'];
        if ($goods_id <= 0) {
            showMessage(L('wrong_argument'), '', '', 'error');
        }
        $goods_info = Model('goods')->getGoodsInfoByID($goods_id);
        if (empty($goods_info) || $goods_info['store_id'] != $_SESSION['store_id']) {
            showMessage(L('wrong_argument'), '', '', 'error');
        }
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'号码');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'使用状态');
        $data = Model('goods_fcode')->getGoodsFCodeList(array('goods_id' => $goods_id));
        foreach ($data as $k=>$v){
            $tmp = array();
            $tmp[] = array('data'=>$v['fc_code']);
            $tmp[] = array('data'=>$v['fc_state'] ? '已使用' : '未使用');
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset($goods_info['goods_name'],CHARSET));
        $excel_obj->generateXML($excel_obj->charset($goods_info['goods_name'],CHARSET).'-'.date('Y-m-d-H',time()));
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
            case 'fcode_goods_list':
                $menu_array = array(
                    1=>array('menu_key'=>'fcode_goods_list', 'menu_name'=>'商品列表', 'menu_url'=>urlShop('store_promotion_fcode', 'fcode_goods_list'))
                );
                break;
            case 'fcode_quota_add':
                $menu_array = array(
                    1=>array('menu_key'=>'fcode_goods_list', 'menu_name'=>'商品列表', 'menu_url'=>urlShop('store_promotion_fcode', 'fcode_goods_list')),
                    2=>array('menu_key'=>'fcode_quota_add', 'menu_name'=>'购买套餐', 'menu_url'=>urlShop('store_promotion_fcode', 'fcode_quota_add'))
                );
                break;
            case 'fcode_renew':
                $menu_array = array(
                    1=>array('menu_key'=>'fcode_goods_list', 'menu_name'=>'商品列表', 'menu_url'=>urlShop('store_promotion_fcode', 'fcode_goods_list')),
                    2=>array('menu_key'=>'fcode_renew', 'menu_name'=>'套餐续费', 'menu_url'=>urlShop('store_promotion_fcode', 'fcode_renew'))
                );
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
