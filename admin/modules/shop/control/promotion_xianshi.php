<?php
/**
 * 限时折扣管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class promotion_xianshiControl extends SystemControl{

    public function __construct(){
        parent::__construct();

        //读取语言包
        Language::read('promotion_xianshi');

        //检查审核功能是否开启
        if (intval($_GET['promotion_allow']) !== 1 && intval(C('promotion_allow')) !== 1){
            $url = array(
                array(
                    'url'=>'index.php?act=promotion_xianshi&promotion_allow=1',
                    'msg'=>Language::get('open'),
                ),
                array(
                    'url'=>'index.php?act=setting',
                    'msg'=>Language::get('close'),
                ),
            );
            showMessage(Language::get('promotion_unavailable'),$url,'html','succ',1,6000);
        }

        //自动开启限时折扣
        if (intval($_GET['promotion_allow']) === 1){
            $model_setting = Model('setting');
            $update_array = array();
            $update_array['promotion_allow'] = 1;
            $model_setting->updateSetting($update_array);
        }
    }

    /**
     * 默认Op
     */
    public function indexOp() {

        $this->xianshi_listOp();

    }

    /**
     * 活动列表
     */
    public function xianshi_listOp()
    {
        $model_xianshi = Model('p_xianshi');
        Tpl::output('xianshi_state_array', $model_xianshi->getXianshiStateArray());

        $this->show_menu('xianshi_list');
		Tpl::setDirquna('shop');
        Tpl::showpage('promotion_xianshi.list');
    }

    /**
     * 活动列表
     */
    public function xianshi_list_xmlOp()
    {
        $condition = array();

        if ($_REQUEST['advanced']) {
            if (strlen($q = trim((string) $_REQUEST['xianshi_name']))) {
                $condition['xianshi_name'] = array('like', '%' . $q . '%');
            }
            if (strlen($q = trim((string) $_REQUEST['store_name']))) {
                $condition['store_name'] = array('like', '%' . $q . '%');
            }
            if (($q = (int) $_REQUEST['state']) > 0) {
                $condition['state'] = $q;
            }

            $pdates = array();
            if (strlen($q = trim((string) $_REQUEST['pdate1'])) && ($q = strtotime($q . ' 00:00:00'))) {
                $pdates[] = "end_time >= {$q}";
            }
            if (strlen($q = trim((string) $_REQUEST['pdate2'])) && ($q = strtotime($q . ' 00:00:00'))) {
                $pdates[] = "start_time <= {$q}";
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
                    case 'xianshi_name':
                        $condition['xianshi_name'] = array('like', '%'.$q.'%');
                        break;
                    case 'store_name':
                        $condition['store_name'] = array('like', '%'.$q.'%');
                        break;
                }
            }
        }

        $model_xianshi = Model('p_xianshi');
        $xianshi_list = (array) $model_xianshi->getXianshiList($condition, $_REQUEST['rp'], 'state desc, end_time desc');

        $flippedOwnShopIds = array_flip(Model('store')->getOwnShopIds());

        $data = array();
        $data['now_page'] = $model_xianshi->shownowpage();
        $data['total_num'] = $model_xianshi->gettotalnum();

        foreach ($xianshi_list as $val) {
            $o  = '<a class="btn red confirm-on-click" href="javascript:;" data-href="' . urlAdminShop('promotion_xianshi', 'xianshi_del', array(
                'xianshi_id' => $val['xianshi_id'],
            )) . '"><i class="fa fa-trash-o"></i>删除</a>';

            $o .= '<span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em><ul>';

            if ($val['editable']) {
                $o .= '<li><a class="confirm-on-click" href="javascript:;" data-href="' . urlAdminShop('promotion_xianshi', 'xianshi_cancel', array(
                    'xianshi_id' => $val['xianshi_id'],
                )) . '">取消活动</a></li>';
            }

            $o .= '<li><a class="confirm-on-click" href="' . urlAdminShop('promotion_xianshi', 'xianshi_detail', array(
                'xianshi_id' => $val['xianshi_id'],
            )) . '">活动详细</a></li>';

            $o .= '</ul></span>';

            $i = array();
            $i['operation'] = $o;
            $i['xianshi_name'] = $val['xianshi_name'];
            $i['store_name'] = '<a target="_blank" href="' . urlShop('show_store', 'index', array(
                'store_id'=>$val['store_id'],
            )) . '">' . $val['store_name'] . '</a>';

            if (isset($flippedOwnShopIds[$val['store_id']])) {
                $i['store_name'] .= '<span class="ownshop">[自营]</span>';
            }

            $i['start_time_text'] = date('Y-m-d H:i', $val['start_time']);
            $i['end_time_text'] = date('Y-m-d H:i', $val['end_time']);

            $i['lower_limit'] = $val['lower_limit'];
            $i['xianshi_state_text'] = $val['xianshi_state_text'];

            $data['list'][$val['xianshi_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 限时折扣活动取消
     **/
    public function xianshi_cancelOp() {
        $xianshi_id = intval($_REQUEST['xianshi_id']);
        $model_xianshi = Model('p_xianshi');
        $result = $model_xianshi->cancelXianshi(array('xianshi_id' => $xianshi_id));
        if($result) {
            $this->log('取消限时折扣活动，活动编号'.$xianshi_id);

            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }

    /**
     * 限时折扣活动删除
     **/
    public function xianshi_delOp() {
        $xianshi_id = intval($_REQUEST['xianshi_id']);
        $model_xianshi = Model('p_xianshi');
        $result = $model_xianshi->delXianshi(array('xianshi_id' => $xianshi_id));
        if($result) {
            $this->log('删除限时折扣活动，活动编号'.$xianshi_id);

            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }

    /**
     * 活动详细信息
     **/
    public function xianshi_detailOp() {
        $xianshi_id = intval($_GET['xianshi_id']);

        $model_xianshi = Model('p_xianshi');
        $model_xianshi_goods = Model('p_xianshi_goods');

        $xianshi_info = $model_xianshi->getXianshiInfoByID($xianshi_id);
        if(empty($xianshi_info)) {
            showMessage(L('param_error'));
        }
        Tpl::output('xianshi_info',$xianshi_info);

        //获取限时折扣商品列表
        $condition = array();
        $condition['xianshi_id'] = $xianshi_id;
        $xianshi_goods_list = $model_xianshi_goods->getXianshiGoodsExtendList($condition);
        Tpl::output('list',$xianshi_goods_list);

        $this->show_menu('xianshi_detail');
		Tpl::setDirquna('shop');
        Tpl::showpage('promotion_xianshi.detail');
    }

    /**
     * 套餐管理
     */
    public function xianshi_quotaOp()
    {
        $this->show_menu('xianshi_quota');
		Tpl::setDirquna('shop');
        Tpl::showpage('promotion_xianshi_quota.list');
    }

    /**
     * 套餐管理XML
     */
    public function xianshi_quota_xmlOp()
    {
        $condition = array();

        if (strlen($q = trim($_REQUEST['query']))) {
            switch ($_REQUEST['qtype']) {
                case 'store_name':
                    $condition['store_name'] = array('like', '%'.$q.'%');
                    break;
            }
        }

        $model_xianshi_quota = Model('p_xianshi_quota');
        $list = (array) $model_xianshi_quota->getXianshiQuotaList($condition, $_REQUEST['rp'], 'end_time desc');

        $data = array();
        $data['now_page'] = $model_xianshi_quota->shownowpage();
        $data['total_num'] = $model_xianshi_quota->gettotalnum();

        foreach ($list as $val) {
            $i = array();
            $i['operation'] = '<span>--</span>';

            $i['store_name'] = '<a target="_blank" href="' . urlShop('show_store', 'index', array(
                'store_id' => $val['store_id'],
            )) . '">' . $val['store_name'] . '</a>';

            $i['start_time_text'] = date("Y-m-d", $val['start_time']);
            $i['end_time_text'] = date("Y-m-d", $val['end_time']);

            $data['list'][$val['quota_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 设置
     **/
    public function xianshi_settingOp() {

        $model_setting = Model('setting');
        $setting = $model_setting->GetListSetting();
        Tpl::output('setting',$setting);

        $this->show_menu('xianshi_setting');
		Tpl::setDirquna('shop');
        Tpl::showpage('promotion_xianshi.setting');
    }

    public function xianshi_setting_saveOp() {

        $promotion_xianshi_price = intval($_POST['promotion_xianshi_price']);
        if($promotion_xianshi_price < 0) {
            $promotion_xianshi_price = 20;
        }

        $model_setting = Model('setting');
        $update_array = array();
        $update_array['promotion_xianshi_price'] = $promotion_xianshi_price;

        $result = $model_setting->updateSetting($update_array);
        if ($result){
            $this->log('修改限时折扣价格为'.$promotion_xianshi_price.'元');
            showMessage(Language::get('setting_save_success'),'');
        }else {
            showMessage(Language::get('setting_save_fail'),'');
        }
    }

    /**
     * ajax修改抢购信息
     */
    public function ajaxOp(){
        $result = true;
        $update_array = array();
        $where_array = array();

        switch ($_GET['branch']){
         case 'recommend':
            $model= Model('p_xianshi_goods');
            $update_array['xianshi_recommend'] = $_GET['value'];
            $where_array['xianshi_goods_id'] = $_GET['id'];
            $result = $model->editXianshiGoods($update_array, $where_array);
            break;
        }

        if($result) {
            echo 'true';exit;
        } else {
            echo 'false';exit;
        }

    }


    /*
     * 发送消息
     */
    private function send_message($member_id,$member_name,$message) {
        $param = array();
        $param['from_member_id'] = 0;
        $param['member_id'] = $member_id;
        $param['to_member_name'] = $member_name;
        $param['message_type'] = '1';//表示为系统消息
        $param['msg_content'] = $message;
        $model_message = Model('message');
        return $model_message->saveMessage($param);
    }

    /**
     * 页面内导航菜单
     *
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function show_menu($menu_key) {
        $menu_array = array(
            'xianshi_list'=>array('menu_type'=>'link','menu_name'=>Language::get('xianshi_list'),'menu_url'=>'index.php?act=promotion_xianshi&op=xianshi_list'),
            'xianshi_detail'=>array('menu_type'=>'link','menu_name'=>Language::get('xianshi_detail'),'menu_url'=>'index.php?act=promotion_xianshi&op=xianshi_detail'),
            'xianshi_quota'=>array('menu_type'=>'link','menu_name'=>Language::get('xianshi_quota'),'menu_url'=>'index.php?act=promotion_xianshi&op=xianshi_quota'),
            'xianshi_setting'=>array('menu_type'=>'link','menu_name'=>Language::get('xianshi_setting'),'menu_url'=>'index.php?act=promotion_xianshi&op=xianshi_setting'),
        );
        if($menu_key != 'xianshi_detail') unset($menu_array['xianshi_detail']);
        $menu_array[$menu_key]['menu_type'] = 'text';
        Tpl::output('menu',$menu_array);
    }

}
