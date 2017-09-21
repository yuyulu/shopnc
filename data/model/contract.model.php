<?php
/**
 * 消费者保障服务模型
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');
class contractModel extends Model {
    private $itemstate_arr;
    private $contract_auditstate_arr;
    private $contract_joinstate_arr;
    private $contract_closestate_arr;
    private $contract_quitstate_arr;
    private $join_auditstate_arr;
    private $quit_auditstate_arr;
    private $goods_contractstate_arr;
    private $log_role_arr;

    public function __construct(){
        parent::__construct();
        //保障项目状态
        $this->itemstate_arr = array('open'=>array('sign'=>1,'name'=>'开启'),'close'=>array('sign'=>0,'name'=>'关闭'));
        //店铺保障项目审核状态
        $this->contract_auditstate_arr = array('notaudit'=>array('sign'=>0,'name'=>'等待审核'),'auditpass'=>array('sign'=>1,'name'=>'审核通过，待支付保证金'),'auditfailure'=>array('sign'=>2,'name'=>'审核未通过'),'costpay'=>array('sign'=>3,'name'=>'保证金待审核'),'costpass'=>array('sign'=>4,'name'=>'保证金审核通过'),'costfailure'=>array('sign'=>5,'name'=>'保证金审核失败'));
        //店铺保障项目加入状态
        $this->contract_joinstate_arr = array('notapply'=>array('sign'=>0,'name'=>'未申请'),'applying'=>array('sign'=>1,'name'=>'申请进行中'),'added'=>array('sign'=>2,'name'=>'已加入'));
        //店铺保障项目关闭状态
        $this->contract_closestate_arr = array('open'=>array('sign'=>1,'name'=>'允许使用'),'close'=>array('sign'=>0,'name'=>'永久禁止使用'));
        //店铺保障项目退出状态
        $this->contract_quitstate_arr = array('notapply'=>array('sign'=>0,'name'=>'未申请'),'applying'=>array('sign'=>1,'name'=>'退出审核中'),'applyfailure'=>array('sign'=>2,'name'=>'退出失败'));
        //加入申请状态
        $this->join_auditstate_arr = array('notaudit'=>array('sign'=>0,'name'=>'等待审核'),'auditpass'=>array('sign'=>1,'name'=>'审核通过，待支付保证金'),'auditfailure'=>array('sign'=>2,'name'=>'审核未通过'),'costpay'=>array('sign'=>3,'name'=>'保证金待审核'),'costpass'=>array('sign'=>4,'name'=>'保证金审核通过'),'costfailure'=>array('sign'=>5,'name'=>'保证金审核失败'));
        //店铺保障项目退出申请审核状态
        $this->quit_auditstate_arr = array('notaudit'=>array('sign'=>0,'name'=>'等待审核'),'auditpass'=>array('sign'=>1,'name'=>'审核通过'),'auditfailure'=>array('sign'=>2,'name'=>'审核失败'));
        //商品保障服务项目开启状态
        $this->goods_contractstate_arr = array('open'=>array('sign'=>1,'name'=>'开启'),'close'=>array('sign'=>0,'name'=>'关闭'));
        //操作日志角色数组
        $this->log_role_arr = array('admin'=>'平台管理员','seller'=>'商家');
    }
    /**
     * 获取保障项目状态数组
     */
    public function getItemState(){
        return $this->itemstate_arr;
    }
    /**
     * 获取店铺保障项目审核状态
     */
    public function getContractAuditState(){
        return $this->contract_auditstate_arr;
    }
    /**
     * 获取店铺保障项目加入状态
     */
    public function getContractJoinState(){
        return $this->contract_joinstate_arr;
    }
    /**
     * 获取店铺保障项目关闭状态
     */
    public function getContractCloseState(){
        return $this->contract_closestate_arr;
    }
    /**
     * 店铺保障项目退出状态
     */
    public function getContractQuitState(){
        return $this->contract_quitstate_arr;
    }
    /**
     * 加入申请状态
     */
    public function getJoinAuditState(){
        return $this->join_auditstate_arr;
    }
    /**
     * 退出申请审核状态
     */
    public function getQuitAuditState(){
        return $this->quit_auditstate_arr;
    }
    /**
     * 商品保障服务项目开启状态
     */
    public function getGoodsContractState(){
        return $this->goods_contractstate_arr;
    }
    /**
     * 服务操作日志角色数组
     */
    public function getLogRole(){
        return $this->log_role_arr;
    }
    /**
     * 通过缓存获得保障项目列表
     * @param string state 显示的保障项目状态 'all'为全部记录，'open'为仅显示开启的项目，'close'为仅显示关闭的项目
     */
    public function getContractItemByCache($state = 'open') {
        $list_tmp = rkcache('contractitem', true);
        if ($state == 'all') {//返回全部记录
            return $list_tmp;
        }
        $list = array();
        if ($list_tmp) {
            foreach ($list_tmp as $k=>$v) {
                if ($v['cti_state_key'] == $state) {
                    $list[$k] = $v;
                }
            }
        }
        return $list;
    }
    /**
     * 查询保障项目列表
     */
    public function contractItemList($where = array(), $order = '') {
        $list = $this->table('contract_item')->where($where)->order($order)->select();
        if (!$list) {
            return array();
        }
        foreach($list as $k=>$v){
            foreach($this->itemstate_arr as $state_k=>$state_v){
                if ($state_v['sign'] == $v['cti_state']) {
                    $v['cti_state_key'] = $state_k;
                    $v['cti_state_text'] = $state_v['name'];
                }
            }
            if (!empty($v['cti_icon'])){
                $v['cti_icon_url'] = UPLOAD_SITE_URL.DS.ATTACH_CONTRACTICON.DS.$v['cti_icon'];
                $v['cti_icon_url_60'] = UPLOAD_SITE_URL.DS.ATTACH_CONTRACTICON.DS.str_ireplace('.', '_60.', $v['cti_icon']);
            }else{
                $v['cti_icon_url'] = UPLOAD_SITE_URL.DS.defaultGoodsImage(240);
            }
            $list[$k] = $v;
        }
        return $list;
    }
    /**
     * 通过缓存获得保障项目详情
     */
    public function getContractItemInfoByCache($item_id) {
        $item_list = rkcache('contractitem', true);
        $info = array();
        if ($item_list) {
            $info = $item_list[$item_id];
        }
        return $info;
    }
    /**
     * 获得保障项目详情
     */
    public function getContractItemInfo($where = array(), $field = '*', $order = '',$group = '') {
        $info = $this->table('contract_item')->where($where)->field($field)->order($order)->group($group)->find();
        if (!$info){
        	return array();
        }
        foreach($this->itemstate_arr as $state_k=>$state_v){
            if ($state_v['sign'] == $info['cti_state']) {
                $info['cti_state_key'] = $state_k;
                $info['cti_state_text'] = $state_v['name'];
            }
        }
        if (!empty($info['cti_icon'])){
            $info['cti_icon_url'] = UPLOAD_SITE_URL.DS.ATTACH_CONTRACTICON.DS.$info['cti_icon'];
            $info['cti_icon_url_60'] = UPLOAD_SITE_URL.DS.ATTACH_CONTRACTICON.DS.str_ireplace('.', '_60.', $info['cti_icon']);
        }else{
            $info['cti_icon_url'] = UPLOAD_SITE_URL.DS.defaultGoodsImage(60);
        }
        return $info;
    }
    
    /**
     * 更新保障项目信息
     */
    public function editContractItem($where,$data) {
        $result = $this->table('contract_item')->where($where)->update($data);
        if ($result) {
            dkcache('contractitem');
        }
        return $result;
    }

    /**
     * 查询店铺开启保障项目列表
     */
    public function getContractList($where = array(), $field = '*', $limit = 0, $page = 0, $order = '', $group = '') {
        $list = array();
        if (is_array($page)){
            if ($page[1] > 0){
                $list = $this->table('contract')->field($field)->where($where)->limit($limit)->page($page[0],$page[1])->order($order)->group($group)->select();
            } else {
                $list = $this->table('contract')->field($field)->where($where)->limit($limit)->page($page[0])->order($order)->group($group)->select();
            }
        } else {
            $list = $this->table('contract')->field($field)->where($where)->limit($limit)->page($page)->order($order)->group($group)->select();
        }
        if (!$list) {
            return array();
        }
        foreach($list as $k=>$v){
            foreach($this->contract_auditstate_arr as $state_k=>$state_v){
                if ($state_v['sign'] == $v['ct_auditstate']) {
                    $v['ct_auditstate_key'] = $state_k;
                    $v['ct_auditstate_text'] = $state_v['name'];
                }
            }
            foreach($this->contract_joinstate_arr as $state_k=>$state_v){
                if ($state_v['sign'] == $v['ct_joinstate']) {
                    $v['ct_joinstate_text'] = $state_v['name'];
                    $v['ct_joinstate_key'] = $state_k;
                }
            }
            foreach($this->contract_closestate_arr as $state_k=>$state_v){
                if ($state_v['sign'] == $v['ct_closestate']) {
                    $v['ct_closestate_text'] = $state_v['name'];
                    $v['ct_closestate_key'] = $state_k;
                }
            }
            foreach($this->contract_quitstate_arr as $state_k=>$state_v){
                if ($state_v['sign'] == $v['ct_quitstate']) {
                    $v['ct_quitstate_text'] = $state_v['name'];
                    $v['ct_quitstate_key'] = $state_k;
                }
            }
            if ($v['ct_closestate'] == $this->contract_closestate_arr['close']['sign']) {
                $v['ct_state_text'] = $this->contract_closestate_arr['close']['name'];
                $v['ct_state_sign'] = 'closed';
            } elseif($v['ct_joinstate'] == $this->contract_joinstate_arr['added']['sign']) {
                $v['ct_state_text'] = $this->contract_joinstate_arr['added']['name'];
                $v['ct_state_sign'] = 'added';
            } elseif($v['ct_joinstate'] == $this->contract_joinstate_arr['applying']['sign']){
                $v['ct_state_text'] = $this->contract_joinstate_arr['applying']['name'];
                $v['ct_state_sign'] = 'applying';
            } elseif($v['ct_joinstate'] == $this->contract_joinstate_arr['notapply']['sign']){
                $v['ct_state_text'] = $this->contract_joinstate_arr['notapply']['name'];
                $v['ct_state_sign'] = 'notapply';
            }
            $list[$k] = $v;
        }
        return $list;
    }

    /**
     * 查询店铺开启保障项目详情
     */
    public function getContractInfo($where = array(), $field = '*', $order = '',$group = '') {
        $info = $this->table('contract')->where($where)->field($field)->order($order)->group($group)->find();
        if (!$info){
            return array();
        }
        foreach($this->contract_auditstate_arr as $state_k=>$state_v){
            if ($state_v['sign'] == $info['ct_auditstate']) {
                $info['ct_auditstate_text'] = $state_v['name'];
                $info['ct_auditstate_key'] = $state_k;
            }
        }
        foreach($this->contract_joinstate_arr as $state_k=>$state_v){
            if ($state_v['sign'] == $info['ct_joinstate']) {
                $info['ct_joinstate_text'] = $state_v['name'];
                $info['ct_joinstate_key'] = $state_k;
            }
        }
        foreach($this->contract_closestate_arr as $state_k=>$state_v){
            if ($state_v['sign'] == $info['ct_closestate']) {
                $info['ct_closestate_text'] = $state_v['name'];
                $info['ct_closestate_key'] = $state_k;
            }
        }
        foreach($this->contract_quitstate_arr as $state_k=>$state_v){
            if ($state_v['sign'] == $info['ct_quitstate']) {
                $info['ct_quitstate_text'] = $state_v['name'];
                $info['ct_quitstate_key'] = $state_k;
            }
        }
        if ($info['ct_closestate'] == $this->contract_closestate_arr['close']['sign']) {
            $info['ct_state_text'] = $this->contract_closestate_arr['close']['name'];
            $info['ct_state_sign'] = 'closed';
        } elseif($info['ct_joinstate'] == $this->contract_joinstate_arr['added']['sign']) {
            $info['ct_state_text'] = $this->contract_joinstate_arr['added']['name'];
            $info['ct_state_sign'] = 'added';
        } elseif($info['ct_joinstate'] == $this->contract_joinstate_arr['applying']['sign']){
            $info['ct_state_text'] = $this->contract_joinstate_arr['applying']['name'];
            $info['ct_state_sign'] = 'applying';
        } elseif($info['ct_joinstate'] == $this->contract_joinstate_arr['notapply']['sign']){
            $info['ct_state_text'] = $this->contract_joinstate_arr['notapply']['name'];
            $info['ct_state_sign'] = 'notapply';
        }
        return $info;
    }

    /**
     * 更新保障项目信息
     */
    public function editContract($where,$data) {
        return $this->table('contract')->where($where)->update($data);
    }

    /**
     * 增加保障项目信息
     */
    public function addContract($insert_arr){
        return $this->table('contract')->insert($insert_arr);
    }

    /**
     * 增加保障项目申请日志
     */
    public function addContractApply($insert_arr){
        return $this->table('contract_apply')->insert($insert_arr);
    }

    /**
     * 查询店铺保障项目申请列表
     */
    public function getContractApplyList($where = array(), $field = '*', $limit = 0, $page = 0, $order = '', $group = '') {
        $list = array();
        if (is_array($page)){
            if ($page[1] > 0){
                $list = $this->table('contract_apply')->field($field)->where($where)->limit($limit)->page($page[0],$page[1])->order($order)->group($group)->select();
            } else {
                $list = $this->table('contract_apply')->field($field)->where($where)->limit($limit)->page($page[0])->order($order)->group($group)->select();
            }
        } else {
            $list = $this->table('contract_apply')->field($field)->where($where)->limit($limit)->page($page)->order($order)->group($group)->select();
        }
        if (!$list) {
            return array();
        }
        foreach($list as $k=>$v){
            foreach($this->join_auditstate_arr as $state_k=>$state_v){
                if ($state_v['sign'] == $v['cta_auditstate']) {
                    $v['cta_auditstate_text'] = $state_v['name'];
                    $v['cta_auditstate_key'] = $state_k;
                }
            }
            $list[$k] = $v;
        }
        return $list;
    }
    /**
     * 查询店铺保障项目申请详情
     */
    public function getContractApplyInfo($where = array(), $field = '*', $order = '',$group = '') {
        $info = $this->table('contract_apply')->where($where)->field($field)->order($order)->group($group)->find();
        if (!$info){
            return array();
        }
        foreach($this->join_auditstate_arr as $state_k=>$state_v){
            if ($state_v['sign'] == $info['cta_auditstate']) {
                $info['cta_auditstate_key'] = $state_k;
                $info['cta_auditstate_text'] = $state_v['name'];
            }
        }
        if ($info['cta_costimg']){
            $info['cta_costimg_url'] = UPLOAD_SITE_URL.DS.ATTACH_CONTRACTPAY.DS.$info['cta_costimg'];
        }else{
            $info['cta_costimg_url'] = UPLOAD_SITE_URL.DS.defaultGoodsImage(240);
        }
        return $info;
    }
    /**
     * 更新保障项目申请信息
     */
    public function editContractApply($where,$data) {
        return $this->table('contract_apply')->where($where)->update($data);
    }
    /**
     * 查询保证金日志列表
     */
    public function getContractCostlogList($where = array(), $field = '*', $limit = 0, $page = 0, $order = '', $group = '') {
        $list = array();
        if (is_array($page)){
            if ($page[1] > 0){
                $list = $this->table('contract_costlog')->field($field)->where($where)->limit($limit)->page($page[0],$page[1])->order($order)->group($group)->select();
            } else {
                $list = $this->table('contract_costlog')->field($field)->where($where)->limit($limit)->page($page[0])->order($order)->group($group)->select();
            }
        } else {
            $list = $this->table('contract_costlog')->field($field)->where($where)->limit($limit)->page($page)->order($order)->group($group)->select();
        }
        return $list;
    }
    /**
     * 增加保证金日志
     */
    public function addContractCostlog($insert_arr){
        return $this->table('contract_costlog')->insert($insert_arr);
    }
    /**
     * 保证金日志总数
     */
    public function contractCostlogCount($where, $group = ''){
        return $this->table('contract_costlog')->where($where)->group($group)->count();
    }
    /**
     * 增加项目退出申请
     */
    public function addQuitApply($insert_arr){
        return $this->table('contract_quitapply')->insert($insert_arr);
    }
    /**
     * 增加项目日志
     */
    public function addContractLog($insert_arr){
        return $this->table('contract_log')->insert($insert_arr);
    }
    /**
     * 查询保障服务日志列表
     */
    public function getContractLogList($where = array(), $field = '*', $limit = 0, $page = 0, $order = '', $group = '') {
        $list = array();
        if (is_array($page)){
            if ($page[1] > 0){
                $list = $this->table('contract_log')->field($field)->where($where)->limit($limit)->page($page[0],$page[1])->order($order)->group($group)->select();
            } else {
                $list = $this->table('contract_log')->field($field)->where($where)->limit($limit)->page($page[0])->order($order)->group($group)->select();
            }
        } else {
            $list = $this->table('contract_log')->field($field)->where($where)->limit($limit)->page($page)->order($order)->group($group)->select();
        }
        if (!$list) {
            return array();
        }
        return $list;
    }
    /**
     * 查询退出申请列表
     */
    public function getQuitApplyList($where = array(), $field = '*', $limit = 0, $page = 0, $order = '', $group = '') {
        $list = array();
        if (is_array($page)){
            if ($page[1] > 0){
                $list = $this->table('contract_quitapply')->field($field)->where($where)->limit($limit)->page($page[0],$page[1])->order($order)->group($group)->select();
            } else {
                $list = $this->table('contract_quitapply')->field($field)->where($where)->limit($limit)->page($page[0])->order($order)->group($group)->select();
            }
        } else {
            $list = $this->table('contract_quitapply')->field($field)->where($where)->limit($limit)->page($page)->order($order)->group($group)->select();
        }
        if (!$list) {
            return array();
        }
        foreach($list as $k=>$v){
            foreach($this->quit_auditstate_arr as $state_k=>$state_v){
                if ($state_v['sign'] == $v['ctq_auditstate']) {
                    $v['ctq_auditstate_text'] = $state_v['name'];
                    $v['ctq_auditstate_key'] = $state_k;
                }
            }
            $list[$k] = $v;
        }
        return $list;
    }
    /**
     * 查询退出申请详情
     */
    public function getQuitApplyInfo($where = array(), $field = '*', $order = '',$group = '') {
        $info = $this->table('contract_quitapply')->where($where)->field($field)->order($order)->group($group)->find();
        if (!$info){
            return array();
        }
        foreach($this->quit_auditstate_arr as $state_k=>$state_v){
            if ($state_v['sign'] == $info['ctq_auditstate']) {
                $info['ctq_auditstate_key'] = $state_k;
                $info['ctq_auditstate_text'] = $state_v['name'];
            }
        }
        return $info;
    }
    /**
     * 更新退出申请信息
     */
    public function editQuitApply($where,$data) {
        return $this->table('contract_quitapply')->where($where)->update($data);
    }
}