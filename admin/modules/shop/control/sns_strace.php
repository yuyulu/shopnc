<?php
/**
 * SNS动态
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377 
 */



defined('In33hao') or exit('Access Invalid!');
class sns_straceControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('snstrace,sns_strace');
    }

    public function indexOp() {
        $this->stracelistOp();
    }

    /**
     * 动态列表
     */
    public function stracelistOp(){
		Tpl::setDirquna('shop');
        Tpl::showpage('sns_strace.index');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_stracelog = Model('store_sns_tracelog');
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('strace_id', 'strace_title', 'strace_storename', 'strace_storeid', 'strace_state', 'strace_time', 'strace_cool', 'strace_spread', 'strace_comment');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $strace_list = $model_stracelog->getStoreSnsTracelogList($condition, '*', $order, null, $page);

        $data = array();
        $data['now_page'] = $model_stracelog->shownowpage();
        $data['total_num'] = $model_stracelog->gettotalnum();
        foreach ($strace_list as $value) {
            $param = array();
            $operation = "<a class='btn red' href=\"javascript:void(0);\" onclick=\"fg_del('".$value['strace_id']."')\"><i class='fa fa-trash-o'></i>删除</a>";
            $operation .= "<span class='btn'><em><i class='fa fa-cog'></i>" . L('nc_set') . " <i class='arrow'></i></em><ul>";
            $operation .= "<li><a href='javascript:void(0);' onclick='ajax_form(\"login\",\"动态内容\",\"". urlAdminShop('sns_strace', 'strace_info', array('id' => $value['strace_id'])) ."\",640)'>动态内容</a></li>";
            $operation .= "<li><a href='index.php?act=sns_strace&op=scomm_list&st_id=". $value['strace_id'] ."'>查看评论</a></li>";
            $operation .= "<li><a href='javascript:void(0);' onclick='ajaxget(\"" . urlAdminShop('sns_strace','strace_edit',array('id'=> $value['strace_id'], 'value' => ($value['strace_state'] == 1 ? 0 : 1))) . "\")'>".($value['strace_state'] == 1 ? '屏蔽动态' : '显示动态')."</a></li>";
            $operation .= "</ul></span>";
            $param['operation'] = $operation;
            $param['strace_title'] = $value['strace_title'];
            $param['strace_storename'] = $value['strace_storename'];
            $param['strace_storeid'] = $value['strace_storeid'];
            $param['strace_state'] = $value['strace_state'] ==  '1' ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            $param['strace_time'] = date('Y-m-d H:i:s', $value['strace_time']);
            $param['strace_spread'] = $value['strace_spread'];
            $param['strace_comment'] = $value['strace_comment'];
            $data['list'][$value['strace_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 动态详细信息
     */
    public function strace_infoOp() {
        $model_stracelog = Model('store_sns_tracelog');
        $id = $_GET['id'];
        $strace_info = $model_stracelog->getStoreSnsTracelogInfo(array('strace_id' => $id));
        if($strace_info['strace_content'] == ''){
            $data = json_decode($strace_info['strace_goodsdata'],true);
            $content = $model_stracelog->spellingStyle($strace_info['strace_type'], $data);
            $strace_info['strace_content'] = str_replace("%siteurl%", SHOP_SITE_URL.DS, $content);
        }
        Tpl::output('strace_info', $strace_info);
		Tpl::setDirquna('shop');
        Tpl::showpage('sns_strace.info', 'null_layout');
    }

    /**
     * 删除动态
     */
    public function strace_delOp(){
        // 验证参数
        $st_id = intval($_GET['id']);
        if($st_id <= 0){
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
        // 实例化模型
        $rs = Model('store_sns_tracelog')->delStoreSnsTracelog(array('strace_id'=>$st_id));
        if($rs){
            // 删除评论
            Model('store_sns_comment')->delStoreSnsComment(array('strace_id'=>$st_id));
            $this->log(L('nc_del,admin_snstrace_comment'),1);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        }else{
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * 编辑动态
     */
    public function strace_editOp(){
        // 验证参数
        $st_id = intval($_GET['id']);
        if($st_id <= 0){
            showDialog(L('nc_common_op_fail'));
        }
        // where条件
        $where = array();
        $where['strace_id'] = $st_id;
        // update条件
        $update = array();
        $update['strace_state'] = intval($_GET['value']) == 1 ? 1 : 0;
        // 实例化模型
        $rs = Model('store_sns_tracelog')->editStoreSnsTracelog($update, $where);
        if($rs){
            $this->log(L('nc_edit,admin_snstrace_comment'),1);
            showDialog(L('nc_common_op_succ'), '', 'succ', '$("#flexigrid").flexReload();');
        }else{
            showDialog(L('nc_common_op_fail'));
        }
    }

    /**
     * 评论列表
     */
    public function scomm_listOp(){
        $id = intval($_GET['st_id']);
        if ($id <= 0) {
            $title = '全部评论列表';
        } else {
            $strace_info = Model('store_sns_tracelog')->getStoreSnsTracelogInfo(array('strace_id' => $id));
            $title = '查看“'.$strace_info['strace_title'].'”动态的评论';
        }
        Tpl::output('title', $title);
		Tpl::setDirquna('shop');
        Tpl::showpage('sns_scomment.index');
    }

    /**
     * 输出XML数据
     */
    public function get_scomm_xmlOp() {
        $model_scomment = Model('store_sns_comment');
        $condition = array();
        if (intval($_GET['id']) > 0) {
            $condition['strace_id']= intval($_GET['id']);
        }
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('scomm_id', 'scomm_content', 'scomm_membername', 'scomm_memberid', 'scomm_state', 'scomm_time');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $scomm_list = $model_scomment->getStoreSnsCommentList($condition, '*', $order, null, $page);

        $data = array();
        $data['now_page'] = $model_scomment->shownowpage();
        $data['total_num'] = $model_scomment->gettotalnum();
        foreach ($scomm_list as $value) {
            $param = array();
            $operation = "<a class='btn red' href=\"javascript:void(0);\" onclick=\"fg_del('".$value['scomm_id']."')\"><i class='fa fa-trash-o'></i>删除</a>";
            $operation .= "<span class='btn'><em><i class='fa fa-cog'></i>" . L('nc_set') . " <i class='arrow'></i></em><ul>";
            $operation .= "<li><a href='javascript:void(0);' onclick='ajax_form(\"login\",\"评论内容\",\"". urlAdminShop('sns_strace', 'scomm_info', array('id' => $value['scomm_id'])) ."\",640)'>评论内容</a></li>";
            $operation .= "<li><a href='javascript:void(0);' onclick='ajaxget(\"" . urlAdminShop('sns_strace','scomm_edit',array('id'=> $value['scomm_id'], 'value' => ($value['scomm_state'] == 1 ? 0 : 1))) . "\")'>".($value['scomm_state'] == 1 ? '屏蔽评论' : '显示评论')."</a></li>";
            $operation .= "</ul></span>";
            $param['operation'] = $operation;
            $param['scomm_content'] = $value['scomm_content'];
            $param['scomm_membername'] = $value['scomm_membername'];
            $param['scomm_memberid'] = $value['scomm_memberid'];
            $param['scomm_state'] = $value['scomm_state'] ==  '1' ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            $param['scomm_time'] = date('Y-m-d H:i:s', $value['scomm_time']);
            $data['list'][$value['scomm_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 评论详细
     */
    public function scomm_infoOp() {
        $model_scomment = Model('store_sns_comment');
        $id = $_GET['id'];
        $scomm_info = $model_scomment->getStoreSnsCommentInfo(array('scomm_id' => $id));
        Tpl::output('scomm_info', $scomm_info);
		Tpl::setDirquna('shop');
        Tpl::showpage('sns_scomment.info', 'null_layout');
    }

    /**
     * 删除评论
     */
    public function scomm_delOp(){
        $id = intval($_GET['id']);
        if($id <= 0){
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }

        // 实例化模型
        $rs = Model('store_sns_comment')->delStoreSnsComment(array('scomm_id'=>$id));
        if($rs){
            $this->log(L('nc_del,admin_snstrace_pl'),1);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        }else{
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * 评论编辑
     */
    public function scomm_editOp(){
        $id = intval($_GET['id']);
        if($id <= 0){
            showDialog(L('nc_common_op_fail'));
        }
        $scomm_state = $_GET['value'] == 1 ? 1 : 0;
        // 实例化模型
        $rs = Model('store_sns_comment')->editStoreSnsComment(array('scomm_state'=>$scomm_state), array('scomm_id'=>$id));
        if($rs){
            $this->log(L('nc_edit,admin_snstrace_pl'),1);
            showDialog(L('nc_common_op_succ'), '', 'succ', '$("#flexigrid").flexReload();');
        }else{
            showDialog(L('nc_common_op_fail'));
        }
    }
}
