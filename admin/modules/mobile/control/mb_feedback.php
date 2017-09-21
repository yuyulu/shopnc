<?php
/**
 * 合作伙伴管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class mb_feedbackControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('mobile');
    }

    public function indexOp() {
        $this->flistOp();
    }
    /**
     * 意见反馈
     */
    public function flistOp(){
        $model_mb_feedback = Model('mb_feedback');
        $list = $model_mb_feedback->getMbFeedbackList(array(), 10);

        Tpl::output('list', $list);
        Tpl::output('page', $model_mb_feedback->showpage());
        Tpl::setDirquna('mobile');
Tpl::showpage('mb_feedback.index');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_mb_feedback = Model('mb_feedback');
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('id', 'content', 'ftime', 'member_name', 'member_id');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $inform_list = $model_mb_feedback->getMbFeedbackList($condition, $page, $order);

        $data = array();
        $data['now_page'] = $model_mb_feedback->shownowpage();
        $data['total_num'] = $model_mb_feedback->gettotalnum();
        foreach ($inform_list as $value) {
            $param = array();
            $param['operation'] = "<a class='btn red' href=\"javascript:void(0);\" onclick=\"fg_del('".$value['id']."')\"><i class='fa fa-trash-o'></i>删除</a>";
            $param['id'] = $value['id'];
            $param['content'] = $value['content'];
            $param['ftime'] = date('Y-m-d H:i:s', $value['ftime']);
            $param['member_name'] = $value['member_name'];
            $param['member_id'] = $value['member_id'];
            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 删除
     */
    public function delOp(){
        $ids = explode(',', $_GET['id']);
        if (count($ids) == 0){
            exit(json_encode(array('state'=>false,'msg'=>L('wrong_argument'))));
        }
        $model_mb_feedback = Model('mb_feedback');
        $result = $model_mb_feedback->delMbFeedback($ids);
        if ($result){
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        }else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }
}
