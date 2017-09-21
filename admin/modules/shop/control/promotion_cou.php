<?php
/**
 * 加价购
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');

class promotion_couControl extends SystemControl
{
    public function __construct()
    {
        parent::__construct();

        // 检查审核功能是否开启
        if (intval($_GET['promotion_allow']) !== 1 && intval(C('promotion_allow')) !== 1){
            $url = array(
                array(
                    'url'=>'index.php?act=promotion_cou&promotion_allow=1',
                    'msg'=>Language::get('open'),
                ),
                array(
                    'url'=>'index.php?act=setting',
                    'msg'=>Language::get('close'),
                ),
            );
            showMessage(Language::get('promotion_unavailable'), $url, 'html', 'succ', 1, 6000);
        }

        // 自动开启限时折扣
        if (intval($_GET['promotion_allow']) === 1) {
            $model_setting = Model('setting');
            $update_array = array();
            $update_array['promotion_allow'] = 1;
            $model_setting->updateSetting($update_array);
        }
    }

    /**
     * 默认Op
     */
    public function indexOp()
    {
        $this->cou_listOp();
    }

    /**
     * 活动列表
     */
    public function cou_listOp()
    {
        $couModel = Model('p_cou');
        $couStates = $couModel->getCouStates();
        Tpl::output('couStates', $couStates);

        $this->showMenu('cou_list');
		Tpl::setDirquna('shop');
        Tpl::showpage('promotion_cou.list');
    }

    /**
     * 活动列表
     */
    public function cou_list_xmlOp()
    {
        $condition = array();

        if ($_REQUEST['advanced']) {
            if (strlen($q = trim((string) $_REQUEST['name']))) {
                $condition['name'] = array('like', '%' . $q . '%');
            }
            if (strlen($q = trim((string) $_REQUEST['store_name']))) {
                $condition['store_name'] = array('like', '%' . $q . '%');
            }
            if (($q = (int) $_REQUEST['state']) > 0) {
                $condition['state'] = $q;
            }

            $pdates = array();
            if (strlen($q = trim((string) $_REQUEST['pdate1'])) && ($q = strtotime($q . ' 00:00:00'))) {
                $pdates[] = "tend >= {$q}";
            }
            if (strlen($q = trim((string) $_REQUEST['pdate2'])) && ($q = strtotime($q . ' 00:00:00'))) {
                $pdates[] = "tstart <= {$q}";
            }
            if ($pdates) {
                $condition['pdates'] = array(
                    'exp',
                    implode(' or ', $pdates),
                );
            }

        } else {
            if (strlen($q = trim($_REQUEST['query']))) {
                switch ($_REQUEST['qtype']) {
                    case 'name':
                    case 'store_name':
                        $condition[$_REQUEST['qtype']] = array('like', '%' . $q . '%');
                        break;
                }
            }
        }

        $couModel = Model('p_cou');
        $couList = (array) $couModel->getCouList($condition, $_REQUEST['rp'], 'id desc');

        $couStates = $couModel->getCouStates();
        $flippedOwnShopIds = array_flip(Model('store')->getOwnShopIds());

        $data = array();
        $data['now_page'] = $couModel->shownowpage();
        $data['total_num'] = $couModel->gettotalnum();

        foreach ($couList as $val) {
            $u = urlAdminShop('promotion_cou', 'cou_detail', array(
                'id' => $val['id'],
                'store_id' => $val['store_id'],
            ));

            $o = '<a class="btn red confirm-on-click" href="' . urlAdminShop('promotion_cou', 'cou_del', array(
                'cou_id' => $val['id'],
            )) . '"><i class="fa fa-trash-o"></i>删除</a>';
            $o .= '<span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em><ul>';

            if ($val['tend'] > TIMESTAMP) {
                switch ($val['state']) {
                    case $couModel::COU_STATE_NORMAL:
                        $o .= '<li><a class="confirm-on-click" href="' . urlAdminShop('promotion_cou', 'cou_cancel', array(
                            'cou_id' => $val['id'],
                        )) . '">取消活动</a></li>';
                        break;

                    case $couModel::COU_STATE_CANCEL:
                        $o .= '<li><a class="confirm-on-click" href="' . urlAdminShop('promotion_cou', 'cou_reopen', array(
                            'cou_id' => $val['id'],
                        )) . '">开启活动</a></li>';
                        break;
                }
            }


            $o .= <<<EOB
<li><a href="javascript:;" onclick="ajax_form('cou_detail', '店铺加价购活动详情', '{$u}', 640)">活动详细</a>
EOB;
            $o .= '</ul></span>';

            $i = array();
            $i['operation'] = $o;
            $i['name'] = $val['name'];
            $i['store_name'] = '<a target="_blank" href="' . urlShop('show_store', 'index', array(
                'store_id' => $val['store_id'],
            )) . '">' . $val['store_name'] . '</a>';

            if (isset($flippedOwnShopIds[$val['store_id']])) {
                $i['store_name'] .= '<span class="ownshop">[自营]</span>';
            }

            $i['start_time_text'] = date('Y-m-d H:i', $val['tstart']);
            $i['end_time_text'] = date('Y-m-d H:i', $val['tend']);

            $i['state_text'] = $couStates[$val['state']];

            $data['list'][$val['id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 活动详细
     */
    public function cou_detailOp()
    {
        $id = (int) $_GET['id'];
        $storeId = (int) $_GET['store_id'];

        if ($id < 1 || $storeId < 1) {
            return;
            showMessage('参数错误');
        }

        $couDetail = Model('p_cou')->getRelationalCouDetailById($id, $storeId);
        if (empty($couDetail)) {
            return;
            showMessage('加价购活动详情数据不存在');
        }

        Tpl::output('couDetail', $couDetail);
		
		Tpl::setDirquna('shop');

        Tpl::showpage('promotion_cou.detail', 'null_layout');
    }

    /**
     * 活动取消
     */
    public function cou_cancelOp()
    {
        $cou_id = (int) $_REQUEST['cou_id'];
        $result = Model('p_cou')->cancelCouById($cou_id);

        if ($result) {
            $this->log('取消加价购活动，活动编号'.$cou_id);
            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }

    /**
     * 活动开启
     */
    public function cou_reopenOp()
    {
        $cou_id = (int) $_REQUEST['cou_id'];
        $result = Model('p_cou')->reopenCouById($cou_id);

        if ($result) {
            $this->log('重新开启加价购活动，活动编号'.$cou_id);
            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }

    /**
     * 活动删除
     */
    public function cou_delOp()
    {
        $cou_id = (int) $_REQUEST['cou_id'];
        $result = Model('p_cou')->delCouById((int) $cou_id);

        if ($result) {
            $this->log('删除加价购活动，活动编号'.$cou_id);
            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }

    /**
     * 设置
     */
    public function cou_settingOp()
    {
        $model_setting = Model('setting');
        $setting = $model_setting->getListSetting();
        Tpl::output('setting', $setting);

        $this->showMenu('cou_setting');
		Tpl::setDirquna('shop');
        Tpl::showpage('promotion_cou.setting');
    }

    public function cou_setting_saveOp()
    {
        $promotion_cou_price = max(0, intval($_POST['promotion_cou_price']));

        $model_setting = Model('setting');
        $update_array = array();
        $update_array['promotion_cou_price'] = $promotion_cou_price;

        $result = $model_setting->updateSetting($update_array);
        if ($result) {
            $this->log('修改加价购活动价格为'.$promotion_cou_price.'元');
            showMessage('设置保存成功', '');
        } else {
            showMessage('设置保存失败', '');
        }
    }

    public function cou_quotaOp()
    {
        $this->showMenu('cou_quota');
		Tpl::setDirquna('shop');
        Tpl::showPage('promotion_cou.quota');
    }

    public function cou_quota_xmlOp()
    {
        $condition = array();

        if (strlen($q = trim($_REQUEST['query']))) {
            switch ($_REQUEST['qtype']) {
                case 'store_name':
                    $condition['store_name'] = array('like', '%'.$q.'%');
                    break;
            }
        }

        $couQuotaModel = Model('p_cou_quota');
        $list = (array) $couQuotaModel->getCouQuotaList($condition, $_REQUEST['rp'], 'id desc');

        $data = array();
        $data['now_page'] = $couQuotaModel->shownowpage();
        $data['total_num'] = $couQuotaModel->gettotalnum();

        foreach ($list as $val) {
            $i = array();
            $i['operation'] = '<span>--</span>';

            $i['store_name'] = '<a target="_blank" href="' . urlShop('show_store', 'index', array(
                'store_id' => $val['store_id'],
            )) . '">' . $val['store_name'] . '</a>';

            $i['start_time_text'] = date("Y-m-d", $val['tstart']);
            $i['end_time_text'] = date("Y-m-d", $val['tend']);

            $data['list'][$val['id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 页面内导航菜单
     *
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function showMenu($menu_key)
    {
        $menu_array = array(
            'cou_list' => array(
                'menu_type' => 'link',
                'menu_name' => '活动列表',
                'menu_url'=>'index.php?act=promotion_cou&op=cou_list',
            ),
            'cou_detail' => array(
                'menu_type' => 'link',
                'menu_name' => '活动详情',
                'menu_url'=>'',
            ),
            'cou_quota' => array(
                'menu_type' => 'link',
                'menu_name' => '套餐管理',
                'menu_url'=>'index.php?act=promotion_cou&op=cou_quota',
            ),
            'cou_setting' => array(
                'menu_type' => 'link',
                'menu_name' => '设置',
                'menu_url'=>'index.php?act=promotion_cou&op=cou_setting',
            ),
        );

        if ($menu_key != 'cou_detail') {
            unset($menu_array['cou_detail']);
        }

        $menu_array[$menu_key]['menu_type'] = 'text';
        Tpl::output('menu', $menu_array);
    }

}
