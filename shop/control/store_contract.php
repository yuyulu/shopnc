<?php
/**
 * 消费者保障服务管理
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');
class store_contractControl extends BaseSellerControl {
    private $itemstate_arr;
    private $contract_auditstate_arr;
    private $contract_joinstate_arr;
    private $contract_closestate_arr;
    private $contract_quitstate_arr;
    private $join_auditstate_arr;
    private $quit_auditstate_arr;

    public function __construct() {
        parent::__construct();
        if (C('contract_allow') != 1){
            showDialog('需开启“消费者保障服务”功能','index.php?act=seller_center&op=index','error');
        }
        $model_contract = Model('contract');
        $this->itemstate_arr = $model_contract->getItemState();
        $this->contract_auditstate_arr = $model_contract->getContractAuditState();
        $this->contract_joinstate_arr = $model_contract->getContractJoinState();
        $this->contract_closestate_arr = $model_contract->getContractCloseState();
        $this->contract_quitstate_arr = $model_contract->getContractQuitState();
        $this->join_auditstate_arr = $model_contract->getJoinAuditState();
        $this->quit_auditstate_arr = $model_contract->getQuitAuditState();
    }
    /**
     * 保障服务首页
     */
    public function indexOp(){
        //查询保障服务项目
        $model_contract = Model('contract');
        $item_list = $model_contract->getContractItemByCache();
        if (!$item_list) {
            showDialog('平台尚未开启任何消费者保障服务','index.php?act=seller_center&op=index','error');
        }
        //查询店铺保障项目开启情况
        $c_list = $model_contract->getContractList(array('ct_storeid'=>$_SESSION['store_id']));
        if ($c_list) {
            foreach ($c_list as $k=>$v) {
                $item_list[$v['ct_itemid']] = array_merge($item_list[$v['ct_itemid']], $v);
            }
        }
        Tpl::output('item_list',$item_list);
        self::profile_menu('','index');
        Tpl::showpage('store_contract.index');
    }
    /**
     * 申请加入保障项目
     */
    public function ctiapplyOp(){
        $itemid = intval($_GET['itemid']);
        if ($itemid <= 0) {
            showDialog('参数错误','','error');
        }
        //查询保障服务
        $model_contract = Model('contract');
        $item_info = $model_contract->getContractItemInfoByCache($itemid);
        if (!$item_info) {
            showDialog('参数错误','','error');
        }
        //查询店铺保障服务记录
        $where = array();
        $where['ct_itemid'] = $itemid;
        $where['ct_storeid'] = $_SESSION['store_id'];
        $c_info = $model_contract->getContractInfo($where);
        //检验是否可以提交申请
        if ($c_info) {
            if ($c_info['ct_closestate'] == $this->contract_closestate_arr['close']['sign']) {
                showDialog('申请加入失败', '', 'error');
            } elseif ($c_info['ct_joinstate'] != $this->contract_joinstate_arr['notapply']['sign']) {
                showDialog('您已申请加入，请耐心等待审核结果', '', 'error');
            }
        }
        if (checkPlatformStore()) {
            $this->applyadd_platformstore($item_info, $c_info);
        } else {
            $this->applyadd_joininstore($item_info, $c_info);
        }
    }

    /**
     * 平台店铺加入保障服务
     */
    private function applyadd_platformstore($item_info, $c_info){
        $model_contract = Model('contract');
        try {
            $model_contract->beginTransaction();
            if ($c_info) {
                $update_arr = array();
                $update_arr['ct_joinstate'] = $this->contract_joinstate_arr['added']['sign'];
                $update_arr['ct_auditstate'] = $this->contract_auditstate_arr['notaudit']['sign'];
                $result = $model_contract->editContract(array('ct_id'=>$c_info['ct_id']),$update_arr);
            } else {
                $insert_arr = array();
                $insert_arr['ct_storeid'] = $_SESSION['store_id'];
                $insert_arr['ct_storename'] = $_SESSION['store_name'];
                $insert_arr['ct_itemid'] = $item_info['cti_id'];
                $insert_arr['ct_auditstate'] = $this->contract_auditstate_arr['notaudit']['sign'];
                $insert_arr['ct_joinstate'] = $this->contract_joinstate_arr['added']['sign'];
                $insert_arr['ct_closestate'] = $this->contract_closestate_arr['open']['sign'];
                $insert_arr['ct_quitstate'] = $this->contract_quitstate_arr['notapply']['sign'];
                $result = $model_contract->addContract($insert_arr);
            }
            if (!$result) {
                throw new Exception('加入失败');
            }
            //记录服务操作日志
            $result = $this->saveContractLog($item_info, '自营店铺加入保障服务');
            if (!$result) {
                throw new Exception('加入失败');
            }
            $model_contract->commit();
            QueueClient::push('updateStoreGoodsContract', array('store_id'=>$_SESSION['store_id'],'item_id'=>$item_info['cti_id']));
            showDialog('加入成功','index.php?act=store_contract&op=index','succ');
        }catch (Exception $e){
            $model_contract->rollback();
            showDialog($e->getMessage(), '', 'error');
        }
    }
    /**
     * 第三方店铺加入保障服务
     */
    private function applyadd_joininstore($item_info, $c_info){
        $model_contract = Model('contract');
        try {
            $model_contract->beginTransaction();
            //增加申请记录
            $insert_arr = array();
            $insert_arr['cta_itemid'] = $item_info['cti_id'];
            $insert_arr['cta_storeid'] = $_SESSION['store_id'];
            $insert_arr['cta_storename'] = $_SESSION['store_name'];
            $insert_arr['cta_addtime'] = time();
            $insert_arr['cta_auditstate'] = $this->join_auditstate_arr['notaudit']['sign'];
            $result = $model_contract->addContractApply($insert_arr);
            if (!$result) {
                throw new Exception('申请加入失败');
            }
            if ($c_info) {
                $update_arr = array();
                $update_arr['ct_joinstate'] = $this->contract_joinstate_arr['applying']['sign'];
                $update_arr['ct_auditstate'] = $this->contract_auditstate_arr['notaudit']['sign'];
                $result = $model_contract->editContract(array('ct_id'=>$c_info['ct_id']),$update_arr);
            } else {
                $insert_arr = array();
                $insert_arr['ct_storeid'] = $_SESSION['store_id'];
                $insert_arr['ct_storename'] = $_SESSION['store_name'];
                $insert_arr['ct_itemid'] = $item_info['cti_id'];
                $insert_arr['ct_auditstate'] = $this->contract_auditstate_arr['notaudit']['sign'];
                $insert_arr['ct_joinstate'] = $this->contract_joinstate_arr['applying']['sign'];
                $insert_arr['ct_closestate'] = $this->contract_closestate_arr['open']['sign'];
                $insert_arr['ct_quitstate'] = $this->contract_quitstate_arr['notapply']['sign'];
                $result = $model_contract->addContract($insert_arr);
            }
            if (!$result) {
                throw new Exception('申请加入失败');
            }
            //记录服务操作日志
            $result = $this->saveContractLog($item_info, '店铺申请加入保障服务');
            if (!$result) {
                throw new Exception('申请加入失败');
            }
            $model_contract->commit();
            showDialog('加入申请成功，请耐心等待审核结果','index.php?act=store_contract&op=index','succ');
        }catch (Exception $e){
            $model_contract->rollback();
            showDialog($e->getMessage(), '', 'error');
        }
    }
    /**
     * 支付保证金
     */
    public function applypayOp(){
        $itemid = intval($_GET['itemid']);
        if ($itemid <= 0) {
            $itemid = intval($_POST['itemid']);
        }
        if ($itemid <= 0) {
            showDialog('参数错误','','error');
        }
        //查询保障服务
        $model_contract = Model('contract');
        $item_info = $model_contract->getContractItemInfoByCache($itemid);
        if (!$item_info) {
            showDialog('参数错误','','error');
        }
        //查询店铺保障服务
        $where = array();
        $where['ct_itemid'] = $itemid;
        $where['ct_storeid'] = $_SESSION['store_id'];
        $c_info = $model_contract->getContractInfo($where);
        //检验是否可以提交申请
        if (!$c_info) {
            showDialog('申请信息错误', '', 'error');
        }
        //未关闭且在申请进行中
        if ($c_info['ct_closestate'] == $this->contract_closestate_arr['close']['sign'] || $c_info['ct_joinstate'] != $this->contract_joinstate_arr['applying']['sign']) {
            showDialog('申请信息错误', '', 'error');
        }
        //申请状态在审核通过或者保证金审核失败的状态下
        if (!in_array($c_info['ct_auditstate'],array($this->contract_auditstate_arr['auditpass']['sign'],$this->contract_auditstate_arr['costfailure']['sign']))) {
            showDialog('申请信息错误', '', 'error');
        }
        //查询申请信息
        $where = array();
        $where['cta_itemid'] = $itemid;
        $where['cta_storeid'] = $_SESSION['store_id'];
        $where['cta_auditstate'] = array('in',array($this->join_auditstate_arr['auditpass']['sign'],$this->join_auditstate_arr['costfailure']['sign']));
        $apply_info = $model_contract->getContractApplyInfo($where, '*', 'cta_id desc');
        if (!$apply_info) {
            showDialog('申请信息错误', '', 'error');
        }
        if(chksubmit()){
            try {
                $model_contract->beginTransaction();
                //更新申请状态
                $update_arr = array();
                $update_arr['cta_auditstate'] = $this->join_auditstate_arr['costpay']['sign'];
                $update_arr['cta_cost'] = $item_info['cti_cost'];
                //付款凭证
                if (!empty($_FILES['costimg']['name'])){
                    $upload = new UploadFile();
                    $upload->set('default_dir', ATTACH_CONTRACTPAY);
                    $result = $upload->upfile('costimg');
                    if ($result){
                        $update_arr['cta_costimg'] =  $upload->file_name;
                        //删除已有图片
                        $old_image = BASE_UPLOAD_PATH.DS.ATTACH_CONTRACTPAY.DS.$apply_info['cta_costimg'];
                        if(is_file($old_image)) {
                            unlink($old_image);
                        }
                    }
                }
                $result = $model_contract->editContractApply(array('cta_id'=>$apply_info['cta_id']), $update_arr);
                if (!$result) {
                    throw new Exception('支付保证金操作失败');
                }
                $update_arr = array();
                $update_arr['ct_auditstate'] = $this->contract_auditstate_arr['costpay']['sign'];
                $result = $model_contract->editContract(array('ct_id'=>$c_info['ct_id']),$update_arr);
                if (!$result) {
                    throw new Exception('支付保证金操作失败');
                }
                //记录服务操作日志
                $result = $this->saveContractLog($item_info, '店铺支付保证金');
                if (!$result) {
                    throw new Exception('支付保证金操作失败');
                }
                $model_contract->commit();
                showDialog('支付保证金操作成功，请耐心等待审核结果','index.php?act=store_contract&op=index','succ');
            }catch (Exception $e){
                $model_contract->rollback();
                showDialog($e->getMessage(), '', 'error');
            }
        } else {
            Tpl::output('item_info',$item_info);
            Tpl::output('apply_info',$apply_info);
            self::profile_menu('costpay','applypay');
            Tpl::showpage('store_contract.applypay');
        }
    }
    /**
     * 查看保障项目日志
     */
    public function contractlogOp()
    {
        $itemid = intval($_GET['itemid']);
        if ($itemid <= 0) {
            showDialog('参数错误', '', 'error');
        }
        $model_contract = Model('contract');
        //查询保障项目
        $item_info = $model_contract->getContractItemInfoByCache($itemid);
        if (!$item_info) {
            showDialog('参数错误','','error');
        }
        //查询店铺加入服务详情
        $where = array();
        $where['ct_itemid'] = $itemid;
        $where['ct_storeid'] = $_SESSION['store_id'];
        $contract_info = $model_contract->getContractInfo($where);
        if (!$contract_info) {
            showDialog('参数错误','','error');
        }
        //查询保证金日志总数
        $where = array();
        $where['clog_itemid'] = $itemid;
        $where['clog_storeid'] = $_SESSION['store_id'];
        $costlog_count = $model_contract->contractCostlogCount($where);
        //查询店铺保障服务操作日志
        $where = array();
        $where['log_itemid'] = $itemid;
        $where['log_storeid'] = $_SESSION['store_id'];
        $log_list = $model_contract->getContractLogList($where, '*', 0, 10, 'log_id desc');
        Tpl::output('costlog_count',$costlog_count);
        Tpl::output('item_info',$item_info);
        Tpl::output('contract_info',$contract_info);
        Tpl::output('log_list',$log_list);
        Tpl::output('logrole_arr',$model_contract->getLogRole());
        Tpl::output('show_page',$model_contract->showpage(2));
        self::profile_menu('log','contractlog');
        Tpl::showpage('store_contract.log');
    }
    /**
     * 查看保证金日志
     */
    public function costlogOp()
    {
        $itemid = intval($_GET['itemid']);
        if ($itemid <= 0) {
            showDialog('参数错误', '', 'error');
        }
        $model_contract = Model('contract');
        //查询保障项目
        $item_info = $model_contract->getContractItemInfoByCache($itemid);
        if (!$item_info) {
            showDialog('参数错误','','error');
        }
        //查询店铺加入服务详情
        $where = array();
        $where['ct_itemid'] = $itemid;
        $where['ct_storeid'] = $_SESSION['store_id'];
        $contract_info = $model_contract->getContractInfo($where);
        if (!$contract_info) {
            showDialog('参数错误','','error');
        }
        //查询保证金日志
        $where = array();
        $where['clog_itemid'] = $itemid;
        $where['clog_storeid'] = $_SESSION['store_id'];
        $costlog_list = $model_contract->getContractCostlogList($where, '*', 0, 10, 'clog_id desc');

        Tpl::output('item_info',$item_info);
        Tpl::output('contract_info',$contract_info);
        Tpl::output('costlog_list',$costlog_list);
        Tpl::output('show_page',$model_contract->showpage(2));
        self::profile_menu('cost','cost');
        Tpl::showpage('store_contract.costlog');
    }
    /**
     * 申请退出保障项目
     */
    public function ctiquitOp(){
        $itemid = intval($_GET['itemid']);
        if ($itemid <= 0) {
            showDialog('参数错误','','error');
        }
        //查询保障项目
        $model_contract = Model('contract');
        $item_info = $model_contract->getContractItemInfoByCache($itemid);
        if (!$item_info) {
            showDialog('参数错误','','error');
        }
        //查询店铺保障服务
        $where = array();
        $where['ct_itemid'] = $itemid;
        $where['ct_storeid'] = $_SESSION['store_id'];
        $where['ct_closestate'] = $this->contract_closestate_arr['open']['sign'];
        $where['ct_joinstate'] = $this->contract_joinstate_arr['added']['sign'];
        $c_info = $model_contract->getContractInfo($where);
        if (!$c_info) {
            showDialog('参数错误','','error');
        }
        if (checkPlatformStore()) {
            $this->applyquit_platformstore($item_info, $c_info);
        } else {
            $this->applyquit_joininstore($item_info, $c_info);
        }
    }
    /**
     * 平台店铺退出
     */
    private function applyquit_platformstore($item_info, $c_info){
        $model_contract = Model('contract');
        try {
            $model_contract->beginTransaction();
            //更新店铺保障项目
            $update_arr = array();
            $update_arr['ct_joinstate'] = $this->contract_joinstate_arr['notapply']['sign'];
            $result = $model_contract->editContract(array('ct_id'=>$c_info['ct_id']),$update_arr);
            if (!$result) {
                throw new Exception('提交失败');
            }
            //新增保障项目日志
            $result = $this->saveContractLog($item_info, '店铺退出保障服务');
            if (!$result) {
                throw new Exception('提交失败');
            }
            $model_contract->commit();
            QueueClient::push('updateStoreGoodsContract', array('store_id'=>$_SESSION['store_id'],'item_id'=>$item_info['cti_id']));
            showDialog('退出成功','index.php?act=store_contract&op=index','succ');
        }catch (Exception $e){
            $model_contract->rollback();
            showDialog($e->getMessage(), '', 'error');
        }
    }
    /**
     * 第三方店铺退出
     */
    private function applyquit_joininstore($item_info, $c_info){
        $model_contract = Model('contract');
        try {
            $model_contract->beginTransaction();
            //增加退出申请记录
            $insert_arr = array();
            $insert_arr['ctq_itemid'] = $item_info['cti_id'];
            $insert_arr['ctq_itemname'] = $item_info['cti_name'];
            $insert_arr['ctq_storeid'] = $_SESSION['store_id'];
            $insert_arr['ctq_storename'] = $_SESSION['store_name'];
            $insert_arr['ctq_addtime'] = time();
            $insert_arr['ctq_auditstate'] = $this->quit_auditstate_arr['notaudit']['sign'];
            $result = $model_contract->addQuitApply($insert_arr);
            if (!$result) {
                throw new Exception('申请提交失败');
            }
            //更新店铺保障项目
            $update_arr = array();
            $update_arr['ct_quitstate'] = $this->contract_quitstate_arr['applying']['sign'];
            $result = $model_contract->editContract(array('ct_id'=>$c_info['ct_id']),$update_arr);
            if (!$result) {
                throw new Exception('申请提交失败');
            }
            //新增保障项目日志
            $result = $this->saveContractLog($item_info, '店铺申请退出保障服务');
            if (!$result) {
                throw new Exception('申请提交失败');
            }
            $model_contract->commit();
            showDialog('退出申请提交成功，请耐心等待审核结果','index.php?act=store_contract&op=index','succ');
        }catch (Exception $e){
            $model_contract->rollback();
            showDialog($e->getMessage(), '', 'error');
        }
    }
    /**
     * 记录服务操作日志
     */
    private function saveContractLog($item_info, $log_msg){
        $insert_arr = array();
        $insert_arr['log_storeid'] = $_SESSION['store_id'];
        $insert_arr['log_storename'] = $_SESSION['store_name'];
        $insert_arr['log_itemid'] = $item_info['cti_id'];
        $insert_arr['log_itemname'] = $item_info['cti_name'];
        $insert_arr['log_addtime'] = time();
        $insert_arr['log_role'] = 'seller';
        $insert_arr['log_userid'] = $_SESSION['member_id'];
        $insert_arr['log_username'] = $_SESSION['member_name'];
        $insert_arr['log_msg'] = $log_msg;
        return Model('contract')->addContractLog($insert_arr);
    }
   /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function profile_menu($menu_type='',$menu_key='') {
        $menu_array = array();
        switch ($menu_type) {
            case 'log':
                $menu_array = array(
                    1=>array('menu_key'=>'index', 'menu_name'=>'消费者保障服务', 'menu_url'=>'index.php?act=store_contract&op=index'),
                    2=>array('menu_key'=>'contractlog', 'menu_name'=>'保障服务详情', 'menu_url'=>'')
                );
                break;
            case 'cost':
                $menu_array = array(
                    1=>array('menu_key'=>'index', 'menu_name'=>'消费者保障服务', 'menu_url'=>'index.php?act=store_contract&op=index'),
                    2=>array('menu_key'=>'cost', 'menu_name'=>'保证金日志', 'menu_url'=>'')
                );
                break;
            case 'costpay':
                $menu_array = array(
                    1=>array('menu_key'=>'index', 'menu_name'=>'消费者保障服务', 'menu_url'=>'index.php?act=store_contract&op=index'),
                    2=>array('menu_key'=>'applypay', 'menu_name'=>'支付保证金', 'menu_url'=>'')
                );
                break;
            default:
                $menu_array = array(
                    1=>array('menu_key'=>'index', 'menu_name'=>'消费者保障服务', 'menu_url'=>'index.php?act=store_contract&op=index')
                );
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
