<?php
/**
 * 代金券
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */


defined('In33hao') or exit('Access Invalid!');

class member_voucherControl extends BaseMemberControl
{

    public function __construct()
    {
        parent::__construct();
        Language::read('member_layout,member_voucher');
        // 判断系统是否开启代金券功能
        if (intval(C('voucher_allow')) !== 1) {
            showMessage('系统未开启代金券功能', urlShop('member', 'home'), 'html', 'error');
        }
    }

    /*
     * 默认显示代金券模版列表
     */
    public function indexOp()
    {
        $this->voucher_listOp();
    }

    /*
     * 获取代金券模版详细信息
     */
    public function voucher_listOp()
    {
        $model = Model('voucher');
        $list = $model->getMemberVoucherList($_SESSION['member_id'], $_GET['select_detail_state'], 10, 'voucher_active_date desc');
        
        // 取已经使用过并且未有voucher_order_id的代金券的订单ID
        $used_voucher_code = array();
        $voucher_order = array();
        if (! empty($list)) {
            foreach ($list as $v) {
                if ($v['voucher_state'] == 2 && empty($v['voucher_order_id'])) {
                    $used_voucher_code[] = $v['voucher_code'];
                }
            }
        }
        if (! empty($used_voucher_code)) {
            $order_list = Model('order')->getOrderCommonList(array(
                'voucher_code' => array(
                    'in',
                    $used_voucher_code
                )
            ), 'order_id,voucher_code');
            if (! empty($order_list)) {
                foreach ($order_list as $v) {
                    $voucher_order[$v['voucher_code']] = $v['order_id'];
                    $model->editVoucher(array(
                        'voucher_order_id' => $v['order_id']
                    ), array(
                        'voucher_code' => $v['voucher_code']
                    ));
                }
            }
        }
        
        // 清空缓存
        dcache($_SESSION['member_id'], 'm_voucher');
        
        Tpl::output('list', $list);
        Tpl::output('voucherstate_arr', $model->getVoucherStateArray());
        Tpl::output('show_page', $model->showpage(2));
        $this->profile_menu('voucher_list');
        Tpl::showpage('member_voucher.list');
    }

    /**
     * 通过卡密绑定代金券
     */
    public function voucher_bindingOp()
    {
        if (chksubmit(false, true)) {
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array(
                    "input" => $_POST["pwd_code"],
                    "require" => "true",
                    "message" => '请输入代金券卡密'
                )
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showDialog($error,'','error','submiting = false');
            }
            // 查询代金券
            $model_voucher = Model('voucher');
            $where = array();
            $where['voucher_pwd'] = md5($_POST["pwd_code"]);
            $voucher_info = $model_voucher->getVoucherInfo($where);
            if (! $voucher_info) {
                showDialog('代金券卡密错误','','error','submiting = false');
            }
            if (intval($_SESSION['store_id']) == $voucher_info['voucher_store_id']) {
                showDialog('不能领取自己店铺的代金券','','error','submiting = false');
            }
            if ($voucher_info['voucher_owner_id'] > 0) {
                showDialog('该代金券卡密已被使用，不可重复领取','','error','submiting = false');
            }
            $where = array();
            $where['voucher_id'] = $voucher_info['voucher_id'];
            $update_arr = array();
            $update_arr['voucher_owner_id'] = $_SESSION['member_id'];
            $update_arr['voucher_owner_name'] = $_SESSION['member_name'];
            $update_arr['voucher_active_date'] = time();
            $result = $model_voucher->editVoucher($update_arr, $where, $_SESSION['member_id']);
            if ($result) {
                // 更新代金券模板
                $update_arr = array();
                $update_arr['voucher_t_giveout'] = array(
                    'exp',
                    'voucher_t_giveout+1'
                );
                $model_voucher->editVoucherTemplate(array(
                    'voucher_t_id' => $voucher_info['voucher_t_id']
                ), $update_arr);
                showDialog('代金券领取成功', 'index.php?act=member_voucher&op=voucher_list', 'succ');
            } else {
                showDialog('代金券领取失败','','error','submiting = false');
            }
        }
        $this->profile_menu('voucher_binding');
        Tpl::showpage('member_voucher.binding');
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string $menu_type
     *            导航类型
     * @param string $menu_key
     *            当前导航的menu_key
     * @param array $array
     *            附加菜单
     * @return
     *
     */
    private function profile_menu($menu_key = '')
    {
        $menu_array = array(
            1 => array(
                'menu_key' => 'voucher_list',
                'menu_name' => Language::get('nc_myvoucher'),
                'menu_url' => 'index.php?act=member_voucher&op=voucher_list'
            ),
            2 => array(
                'menu_key' => 'voucher_binding',
                'menu_name' => '领取代金券',
                'menu_url' => 'index.php?act=member_voucher&op=voucher_binding'
            )
        );
        Tpl::output('member_menu', $menu_array);
        Tpl::output('menu_key', $menu_key);
    }
}
