<?php
/**
 * SNS动态
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377 
 */



defined('In33hao') or exit('Access Invalid!');
class snstraceControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('snstrace');
    }

    public function indexOp() {
        $this->tracelistOp();
    }

    /**
     * 动态列表
     */
    public function tracelistOp(){
		Tpl::setDirquna('shop');
        Tpl::showpage('snstrace.index');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $tracelog_model = Model('sns_tracelog');
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = $_POST['query'];
        }
        $param = array('trace_id', 'trace_title', 'trace_membername', 'trace_memberid', 'trace_state', 'trace_addtime', 'trace_privacy', 'trace_copycount', 'trace_commentcount');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $condition['order'] = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        //分页
        $page   = new Page();
        $page->setEachNum($_POST['rp']);
        $page->setStyle('admin');
        $help_list = $tracelog_model->getTracelogList($condition, $page);

        // 可见度
        $privacy_array = $this->getTracePrivacy();

        $data = array();
        $data['now_page'] = $page->get('now_page');
        $data['total_num'] = $page->get('total_num');
        foreach ((array)$help_list as $value) {
            $param = array();
            $operation = "<a class='btn red' href=\"javascript:void(0);\" onclick=\"fg_del('".$value['trace_id']."')\"><i class='fa fa-trash-o'></i>删除</a>";
            $operation .= "<span class='btn'><em><i class='fa fa-cog'></i>" . L('nc_set') . " <i class='arrow'></i></em><ul>";
            $operation .= "<li><a href='javascript:void(0);' onclick='ajax_form(\"login\",\"动态内容\",\"". urlAdminShop('snstrace', 'traceinfo', array('id' => $value['trace_id'])) ."\",640)'>动态内容</a></li>";
            $operation .= "<li><a href='index.php?act=snstrace&op=commentlist&tid=". $value['trace_id'] ."'>查看评论</a></li>";
            $operation .= "<li><a href='javascript:void(0);' onclick='ajaxget(\"" . urlAdminShop('snstrace','traceedit',array('id'=> $value['trace_id'], 'value' => ($value['trace_state'] == 1 ? 0 : 1))) . "\")'>".($value['trace_state'] == 1 ? '显示动态' : '屏蔽动态')."</a></li>";
            $operation .= "</ul></span>";
            $param['operation'] = $operation;
            $param['trace_title'] = str_replace("%siteurl%", SHOP_SITE_URL.DS, $value['trace_title']);
            $param['trace_membername'] = $value['trace_membername'];
            $param['trace_memberid'] = $value['trace_memberid'];
            $param['trace_state'] = $value['trace_state'] ==  '0' ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            $param['trace_addtime'] = date('Y-m-d H:i:s', $value['trace_addtime']);
            $param['trace_privacy'] = $privacy_array[$value['trace_privacy']];
            $param['trace_copycount'] = $value['trace_copycount'];
            $param['trace_commentcount'] = $value['trace_commentcount'];
            $data['list'][$value['trace_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 取得可见度
     * @return multitype:Ambigous <string, NULL> Ambigous <string, NULL, multitype:>
     */
    private function getTracePrivacy() {
        return array(
                '0' => L('admin_snstrace_privacy_all'),
                '1' => L('admin_snstrace_privacy_friend'),
                '2' => L('admin_snstrace_privacy_self')
        );
    }

    /**
     * 动态详细信息
     */
    public function traceinfoOp() {
        $tid = intval($_GET['id']);
        $trace_info = Model('sns_tracelog')->getTracelogRow(array('trace_id'=>$tid));
        if(!empty($trace_info['trace_title'])){
            //替换标题中的siteurl
            $trace_info['trace_title'] = str_replace("%siteurl%", SHOP_SITE_URL.DS, $trace_info['trace_title']);
        }
        if(!empty($trace_info['trace_content'])){
            //替换内容中的siteurl
            $trace_info['trace_content'] = str_replace("%siteurl%", SHOP_SITE_URL.DS, $trace_info['trace_content']);
            //将收藏商品和店铺连接剔除
            $trace_info['trace_content'] = str_replace(Language::get('admin_snstrace_collectgoods'), "", $trace_info['trace_content']);
            $trace_info['trace_content'] = str_replace(Language::get('admin_snstrace_collectstore'), "", $trace_info['trace_content']);
        }
        Tpl::output('trace_info', $trace_info);
		Tpl::setDirquna('shop');
        Tpl::showpage('snstrace.info', 'null_layout');
    }

    /**
     * 删除动态
     */
    public function tracedelOp(){
        $tid = intval($_GET['id']);
        if ($tid <= 0) {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
        //删除动态
        $tracelog_model = Model('sns_tracelog');
        $result = $tracelog_model->delTracelog(array('trace_id'=>$tid));
        if($result){
            //删除动态下的评论
            $comment_model = Model('sns_comment');
            $condition = array();
            $condition['comment_originalid'] = $tid;
            $condition['comment_originaltype'] = "0";
            $comment_model->delComment($condition);
            //更新转帖的原帖删除状态为已经删除
            $tracelog_model->tracelogEdit(array('trace_originalstate'=>'1'),array('trace_originalid'=>$tid));
            $this->log(L('nc_del,admin_snstrace_comment'),1);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * 编辑动态
     */
    public function traceeditOp(){
        $tid = intval($_GET['id']);
        if($tid <= 0){
            showDialog(L('admin_snstrace_pleasechoose_edit'));
        }
        //删除动态
        $tracelog_model = Model('sns_tracelog');
        $value = $_GET['value'] == 1 ? 1 : 0;
        $update_arr = array();
        $update_arr['trace_state'] = $value;
        $result = $tracelog_model->tracelogEdit($update_arr,array('trace_id'=>$tid));
        if($result){
            //更新转帖的原帖删除状态为已经删除或者为显示
            $update_arr = array();
            $update_arr['trace_originalstate'] = $value;
            $tracelog_model->tracelogEdit($update_arr,array('trace_originalid'=>$tid));
            $this->log(L('nc_edit,admin_snstrace_comment'),1);
            showDialog(L('nc_common_op_succ'), '', 'succ', '$("#flexigrid").flexReload();');
        }else{
            showDialog(L('nc_common_op_fail'));
        }
    }

    /**
     * 评论列表
     */
    public function commentlistOp(){
        $id = intval($_GET['tid']);
        if ($id <= 0) {
            $title = '全部评论列表';
        } else {
            $trace_info = Model('sns_tracelog')->getTracelogRow(array('trace_id' => $id));
            $title = '查看“'.$trace_info['trace_title'].'”动态的评论';
        }
        Tpl::output('title', $title);
		Tpl::setDirquna('shop');
        Tpl::showpage('snscomment.index');
    }

    /**
     * 输出XML数据
     */
    public function get_comment_xmlOp() {
        $tracelog_model = Model('sns_comment');
        $condition = array();
        $id = intval($_GET['id']);
        if ($id > 0) {
            $condition['comment_originalid'] = $id;
            $condition['comment_originaltype'] = 0;
        }
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = $_POST['query'];
        }
        $param = array('comment_id', 'comment_content', 'comment_membername', 'comment_memberid', 'comment_state', 'comment_addtime');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $condition['order'] = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        //分页
        $page   = new Page();
        $page->setEachNum($_POST['rp']);
        $page->setStyle('admin');
        $help_list = $tracelog_model->getCommentList($condition, $page);

        $data = array();
        $data['now_page'] = $page->get('now_page');
        $data['total_num'] = $page->get('total_num');
        foreach ((array)$help_list as $value) {
            $param = array();
            $operation = "<a class='btn red' href=\"javascript:void(0);\" onclick=\"fg_del('".$value['comment_id']."')\"><i class='fa fa-trash-o'></i>删除</a>";
            $operation .= "<span class='btn'><em><i class='fa fa-cog'></i>" . L('nc_set') . " <i class='arrow'></i></em><ul>";
            $operation .= "<li><a href='javascript:void(0);' onclick='ajax_form(\"login\",\"评论内容\",\"". urlAdminShop('snstrace', 'comminfo', array('id' => $value['comment_id'])) ."\",640)'>评论内容</a></li>";
            $operation .= "<li><a href='javascript:void(0);' onclick='ajaxget(\"" . urlAdminShop('snstrace','commentedit',array('id'=> $value['comment_id'], 'value' => ($value['comment_state'] == 1 ? 0 : 1))) . "\")'>".($value['comment_state'] == 1 ? '屏蔽评论' : '显示评论')."</a></li>";
            $operation .= "</ul></span>";
            $param['operation'] = $operation;
            $param['comment_content'] = $value['comment_content'];
            $param['comment_membername'] = $value['comment_membername'];
            $param['comment_memberid'] = $value['comment_memberid'];
            $param['comment_state'] = $value['comment_state'] ==  '1' ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            $param['comment_addtime'] = date('Y-m-d H:i:s', $value['comment_addtime']);
            $data['list'][$value['comment_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 评论详细内容
     */
    public function comminfoOp() {
        $model_comment = Model('sns_comment');
        $id = $_GET['id'];
        $comm_info = $model_comment->getCommentRow(array('comment_id' => $id));
        Tpl::output('comm_info', $comm_info);
		Tpl::setDirquna('shop');
        Tpl::showpage('snscomment.info', 'null_layout');
    }

    /**
     * 删除评论
     */
    public function commentdelOp(){
        $cid = intval($_GET['id']);
        if($cid <= 0){
            exit(json_encode(array('state'=>false,'msg'=>L('admin_snstrace_pleasechoose_del'))));
        }
        //删除评论
        $comment_model = Model('sns_comment');
        $result = $comment_model->delComment(array('comment_id'=>$cid));
        if($result){
            $this->log(L('nc_del,admin_snstrace_pl'),1);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        }else{
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * 编辑评论
     */
    public function commenteditOp(){
        $cid = intval($_GET['id']);
        if($cid <= 0){
            showDialog(L('admin_snstrace_pleasechoose_edit'));
        }
        //删除动态
        $comment_model = Model('sns_comment');
        $value = $_GET['value'] == 1 ? 1 : 0;
        $result = $comment_model->commentEdit(array('comment_state' => $value),array('comment_id'=>$cid));
        if($result){
            $this->log(L('nc_edit,admin_snstrace_pl'),1);
            showDialog(L('nc_common_op_succ'), '', 'succ', '$("#flexigrid").flexReload();');
        }else{
            showDialog(L('nc_common_op_fail'));
        }
    }
}
