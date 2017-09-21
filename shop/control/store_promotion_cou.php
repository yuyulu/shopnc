<?php
/**
 * 用户中心-加价购
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */


defined('In33hao') or exit('Access Invalid!');

class store_promotion_couControl extends BaseSellerControl
{
    const LINK_COU_LIST = 'index.php?act=store_promotion_cou&op=cou_list';

    public function __construct()
    {
        parent::__construct() ;

        // 读取语言包
        Language::read('member_layout');

        // 检查加价购是否开启
        if (intval(C('promotion_allow')) !== 1) {
            showMessage("商品促销功能尚未开启", urlShop('seller_center', 'index'), '', 'error');
        }
    }

    public function indexOp()
    {
        $this->cou_listOp();
    }

    /**
     * 发布的加价购活动列表
     */
    public function cou_listOp()
    {
        if (checkPlatformStore()) {
            Tpl::output('isOwnShop', true);
        } else {
            $current_cou_quota = Model('p_cou_quota')->getCurrentCouQuota($_SESSION['store_id']);
            Tpl::output('current_cou_quota', $current_cou_quota);
        }

        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];

        if (($state = (int) $_GET['state']) > 0) {
            $condition['state'] = $state;
        }
        if (strlen($couName = trim($_GET['cou_name']))) {
            $condition['name'] = array('like', '%' . $couName . '%');
        }

        $couModel = Model('p_cou');

        Tpl::output('list', $couModel->getCouList($condition, 10, 'state desc, tend desc'));
        Tpl::output('show_page', $couModel->showpage());
        Tpl::output('couStates', $couModel->getCouStates());

        self::profile_menu('cou_list');
        Tpl::showpage('store_promotion_cou.list');
    }

    /**
     * 加价购套餐购买
     */
    public function cou_quota_addOp()
    {
        // 输出导航
        $this->profile_menu('cou_quota_add');
        Tpl::showpage('store_promotion_cou.quota_add');
    }

    /**
     * 加价购套餐购买保存
     */
    public function cou_quota_add_saveOp()
    {
        $cou_quota_quantity = intval($_POST['cou_quota_quantity']);

        if ($cou_quota_quantity < 1 || $cou_quota_quantity > 12) {
            showDialog('数量错误');
        }

        // 获取当前价格
        $current_price = intval(C('promotion_cou_price'));

        //获取该用户已有套餐
        $couQuotaModel = Model('p_cou_quota');
        $current_cou_quota= $couQuotaModel->getCurrentCouQuota($_SESSION['store_id']);
        $add_time = 86400 *30 * $cou_quota_quantity;
        if (empty($current_cou_quota)) {
            //生成套餐
            $param = array();
            $param['store_id'] = $_SESSION['store_id'];
            $param['store_name'] = $_SESSION['store_name'];
            $param['tstart'] = TIMESTAMP;
            $param['tend'] = TIMESTAMP + $add_time;
            $couQuotaModel->addCouQuota($param);
        } else {
            $param = array();
            $param['tend'] = array('exp', 'tend + ' . $add_time);
            $couQuotaModel->editCouQuota($param, array(
                'id' => $current_cou_quota['id'],
            ));
        }

        //记录店铺费用
        $this->recordStoreCost($current_price * $cou_quota_quantity, '购买加价购');
        $this->recordSellerLog('购买' . $cou_quota_quantity . '份加价购套餐，单价' . $current_price . $lang['nc_yuan']);

        showDialog('加价购套餐购买成功', self::LINK_COU_LIST, 'succ');
    }

    /**
     * 添加加价购活动
     */
    public function cou_addOp()
    {
        if (checkPlatformStore()) {
            Tpl::output('isOwnShop', true);
        } else {
            $couQuotaModel = Model('p_cou_quota');
            $current_cou_quota = $couQuotaModel->getCurrentCouQuota($_SESSION['store_id']);
            if (empty($current_cou_quota)) {
                showMessage('优惠套餐错误', '', '', 'error');
            }
            Tpl::output('current_cou_quota', $current_cou_quota);
        }

        self::profile_menu('cou_add');
        Tpl::showpage('store_promotion_cou.add');
    }

    /**
     * 保存添加的加价购活动
     */
    public function cou_add_saveOp()
    {
        // 验证输入
        $cou_name = trim($_POST['cou_name']);
        $tstart = strtotime($_POST['tstart']);
        $tend = strtotime($_POST['tend']);

        if (empty($cou_name)) {
            showDialog('活动名称不能为空');
        }

        if ($tstart >= $tend) {
            showDialog('开始时间不能晚于结束时间');
        }

        if (!checkPlatformStore()) {
            // 获取当前套餐
            $couQuotaModel = Model('p_cou_quota');
            $current_cou_quota = $couQuotaModel->getCurrentCouQuota($_SESSION['store_id']);
            if (empty($current_cou_quota)) {
                showDialog('没有可用加价购套餐,请先购买套餐');
            }

            $quota_tstart = intval($current_cou_quota['tstart']);
            $quota_tend = intval($current_cou_quota['tend']);
            if ($tstart < $quota_tstart) {
                showDialog('开始时间不能早于' . date('Y-m-d H:i', $current_cou_quota['tstart']));
            }
            if ($tend > $quota_tend) {
                showDialog('结束时间不能晚于' . date('Y-m-d H:i', $current_cou_quota['tend']));
            }
        }

        // 生成活动
        $couModel = Model('p_cou');
        $param = array();
        $param['name'] = $cou_name;
        $param['quota_id'] = $current_cou_quota['id'] ? $current_cou_quota['id'] : 0;
        $param['tstart'] = $tstart;
        $param['tend'] = $tend;
        $param['store_id'] = $_SESSION['store_id'];
        $param['store_name'] = $_SESSION['store_name'];

        $result = $couModel->addCou($param);

        if ($result) {
            $this->recordSellerLog('添加加价购活动，活动名称：' . $cou_name . '，活动编号：' . $result);

            // 添加计划任务
            Model('cron')->addCron(array('exetime' => $param['tend'], 'exeid' => $result, 'type' => 8), true);

            $editUrl = 'index.php?act=store_promotion_cou&op=cou_edit&cou_id=' . $result;
            showDialog('加价购活动添加成功', $editUrl, 'succ', '', 3);
        } else {
            showDialog('加价购活动添加失败');
        }
    }

    /**
     * 编辑加价购活动
     */
    public function cou_editOp()
    {
        $couId = (int) $_REQUEST['cou_id'];
        $couModel = Model('p_cou');

        $data = $couModel->getRelationalCouDetailById($_GET['cou_id'], $_SESSION['store_id']);
        if (empty($data)) {
            showMessage('参数错误', '', '', 'error');
        }

        Tpl::output('data', $data);

        self::profile_menu('cou_edit');
        Tpl::showpage('store_promotion_cou.edit');
    }

    /**
     * 保存编辑的加价购活动
     */
    public function cou_edit_saveOp()
    {
        // 验证输入
        $couId = (int) $_POST['cou_id'];
        if ($couId < 1) {
            showDialog('参数错误');
        }

        $cou_name = trim($_POST['cou_name']);
        if (empty($cou_name)) {
            showDialog('活动名称不能为空');
        }

        $couModel = Model('p_cou');

        $cou_info = $couModel->getCouInfoByID($couId, $_SESSION['store_id']);
        if (empty($cou_info)) {
            showDialog('参数错误');
        }

        $couModel->editCou(array(
            'name' => $cou_name,
        ), array(
            'id' => $couId,
        ));

        try {
            // 设置参加活动商品
            Model('p_cou_sku')->setCouSkus($couId, (array) $_POST['cou_sku'], $cou_info['tstart'], $cou_info['tend']);

            // 设置活动规则
            Model('p_cou_level')->setCouLevels($couId, (array) $_POST['cou_level']);
        } catch (\Exception $ex) {
            showDialog(Language::get('nc_common_op_fail') . ': ' . $ex->getMessage());
        }

        $this->recordSellerLog('编辑加价购活动，活动名称：' . $cou_name . '，活动编号：' . $couId);
        showDialog(Language::get('nc_common_op_succ'), self::LINK_COU_LIST, 'succ', '', 3);
    }

    /**
     * 加价购活动删除
     */
    public function cou_delOp()
    {
        $cou_id = intval($_POST['cou_id']);
        $couModel = Model('p_cou');

        $cou_info = $couModel->getCouInfoByID($cou_id, $_SESSION['store_id']);
        if (!$cou_info) {
            showDialog('参数错误');
        }

        $result = $couModel->delCouById($cou_id);
        if ($result) {
            $this->recordSellerLog('删除加价购活动，活动名称：' . $cou_info['cou_name'] . '活动编号：' . $cou_id);
            showDialog(L('nc_common_op_succ'), self::LINK_COU_LIST, 'succ');
        } else {
            showDialog(L('nc_common_op_fail'));
        }
    }

    /**
     * 异步设置加价购活动规则中的换购商品
     */
    public function cou_level_skuOp()
    {
        $model_goods = Model('goods');

        // where条件
        $where = array();
        $where['store_id'] = $_SESSION['store_id'];
        if (intval($_GET['stc_id']) > 0) {
            $where['goods_stcids'] = array('like', '%,' . intval($_GET['stc_id']) . ',%');
        }
        if (trim($_GET['keyword']) != '') {
            $where['goods_name'] = array('like', '%' . trim($_GET['keyword']) . '%');
        }

        $goods_list = $model_goods->getGeneralGoodsOnlineList($where, '*', 8);
        Tpl::output('show_page', $model_goods->showpage(2));
        Tpl::output('goods_list', $goods_list);

        /**
         * 商品分类
         */
        $store_goods_class = Model('store_goods_class')->getClassTree(array(
            'store_id' => $_SESSION['store_id'],
            'stc_state' => '1',
        ));
        Tpl::output('store_goods_class', $store_goods_class);

        Tpl::output('level', (int) $_REQUEST['level']);

        Tpl::showpage('store_promotion_cou.cou_level_sku', 'null_layout');
    }

    /**
     * 异步设置参与加价购活动的商品
     */
    public function cou_skuOp()
    {
        $model_goods = Model('goods');

        // where条件
        $where = array();
        $where['store_id'] = $_SESSION['store_id'];
        if (intval($_GET['stc_id']) > 0) {
            $where['goods_stcids'] = array('like', '%,' . intval($_GET['stc_id']) . ',%');
        }
        if (trim($_GET['keyword']) != '') {
            $where['goods_name'] = array('like', '%' . trim($_GET['keyword']) . '%');
        }

        $couId = (int) $_REQUEST['cou_id'];
        Tpl::output('cou_id', $couId);

        $couModel = Model('p_cou');
        $cou_info = $couModel->getCouInfoByID($couId, $_SESSION['store_id']);
        if (empty($cou_info)) {
            showDialog('参数错误');
        }

        // 加条件 已经参加其它加价购活动（时间重叠的）的sku不予显示
        $where[] = array(
            'exp',
            'goods_id not in (select sku_id from ' . C('tablepre') . 'p_cou_sku where cou_id <> ' . $couId .
            ' and tstart < ' . $cou_info['tend'] . ' and tend > ' . $cou_info['tstart'] . ')',
        );

        $goods_list = $model_goods->getGeneralGoodsOnlineList($where, '*', 8);
        Tpl::output('show_page', $model_goods->showpage(2));
        Tpl::output('goods_list', $goods_list);

        /**
         * 商品分类
         */
        $store_goods_class = Model('store_goods_class')->getClassTree(array(
            'store_id' => $_SESSION['store_id'],
            'stc_state' => '1',
        ));
        Tpl::output('store_goods_class', $store_goods_class);

        Tpl::showpage('store_promotion_cou.cou_sku', 'null_layout');
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function profile_menu($menu_key='')
    {
        $menu_array = array(
            1 => array(
                'menu_key' => 'cou_list',
                'menu_name' => '活动列表',
                'menu_url' => 'index.php?act=store_promotion_cou&op=cou_list',
            ),
        );
        switch ($menu_key) {
            case 'cou_quota_add':
                $menu_array[] = array(
                    'menu_key' => 'cou_quota_add',
                    'menu_name' => '购买套餐',
                    'menu_url' => 'index.php?act=store_promotion_cou&op=cou_add',
                );
                break;
            case 'cou_add':
                $menu_array[] = array(
                    'menu_key' => 'cou_add',
                    'menu_name' => '添加活动',
                    'menu_url' => 'javascript:;'
                );
                break;
            case 'cou_edit':
                $menu_array[] = array(
                    'menu_key' => 'cou_edit',
                    'menu_name' => '编辑活动',
                    'menu_url' => 'javascript:;'
                );
                break;
        }
        Tpl::output('member_menu', $menu_array);
        Tpl::output('menu_key', $menu_key);
    }
}
