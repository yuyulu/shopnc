<?php
/**
 * 系统操作日志
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class admin_logControl extends SystemControl{
    const EXPORT_SIZE = 1000;

    public function __construct(){
        parent::__construct();
        Language::read('admin_log');
    }

    public function indexOp() {
        $this->listOp();
    }

    /**
     * 日志列表
     *
     */
    public function listOp(){
		Tpl::setDirquna('system');
        Tpl::showpage('admin_log.index');
    }

    public function get_xmlOp(){
        $model = Model('admin_log');
        $condition  = array();
        list($condition,$order) = $this->_get_condition($condition);
        $list = $model->where($condition)->order($order)->page($_POST['rp'])->select();
        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();
        foreach ($list as $k => $info) {
            $list = array();
            $list['operation'] = "<a class='btn red' onclick=\"fg_delete({$info['id']})\"><i class='fa fa-trash-o'></i>删除</a>";
            $list['admin_name'] = $info['admin_name'];
            $list['content'] = $info['content'];
            $list['createtime'] = date('Y-m-d H:i:s',$info['createtime']);
            $list['ip'] = $info['ip'];
            $data['list'][$info['id']] = $list;
        }
        exit(Tpl::flexigridXML($data));
    }

    /**
     * 删除日志
     *
     */
    public function list_delOp(){
        $condition = array();
        if ($_GET['type'] == 'ago'){
            $condition['createtime'] = array('lt',TIMESTAMP-15552000);
        }elseif (preg_match('/^[\d,]+$/', $_GET['del_id'])) {
            $_GET['del_id'] = explode(',',trim($_GET['del_id'],','));
            $condition['id'] = array('in',$_GET['del_id']);
        }
        if (!Model('admin_log')->where($condition)->delete()){
            $this->log(L('nc_del,nc_admin_log'),0);
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }else{
            $this->log(L('nc_del,nc_admin_log'),1);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        }
    }

    /**
     * 导出第一步
     */
    public function export_step1Op(){
        $model = Model('admin_log');
        $condition = array();
        if (preg_match('/^[\d,]+$/', $_GET['id'])) {
            $_GET['id'] = explode(',',trim($_GET['id'],','));
            $condition['id'] = array('in',$_GET['id']);
        }
        list($condition,$order) = $this->_get_condition($condition);
        if (!is_numeric($_GET['curpage'])){
            $count = $model->where($condition)->count();
            $array = array();
            if ($count > self::EXPORT_SIZE ){   //显示下载链接
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','index.php?act=admin_log&op=list');
				Tpl::setDirquna('system');
                Tpl::showpage('export.excel');
                exit;
            }
            $limit = false;
        }else{  //下载
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $limit = "{$limit1},{$limit2}";
        }
        $data = $model->where($condition)->order('id desc')->limit($limit)->select();
        $this->createExcel($data);
    }

    /**
     * 生成excel
     *
     * @param array $data
     */
    private function createExcel($data = array()){
        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('admin_log_man'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('admin_log_do'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('admin_log_dotime'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'IP');
        foreach ((array)$data as $k=>$v){
            $tmp = array();
            $tmp[] = array('data'=>$v['admin_name']);
            $tmp[] = array('data'=>$v['content']);
            $tmp[] = array('data'=>date('Y-m-d H:i:s',$v['createtime']));
            $tmp[] = array('data'=>$v['ip']);
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset(L('nc_admin_log'),CHARSET));
        $excel_obj->generateXML($excel_obj->charset(L('nc_admin_log'),CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
    }

    /**
     * 封装公共代码
     */
    private function _get_condition($condition) {
        if ($_REQUEST['query'] != '' && in_array($_REQUEST['qtype'],array('admin_name','content'))) {
            $condition[$_REQUEST['qtype']] = array('like',"%{$_REQUEST['query']}%");
        }
        if ($_GET['admin_name'] != '') {
            $condition['admin_name'] = array('like',"%{$_GET['admin_name']}%");
        }
        if ($_GET['content'] != '') {
            $condition['content'] = array('like',"%{$_GET['content']}%");
        }
        if ($_GET['ip'] != '') {
            $condition['ip'] = array('like',"%{$_GET['ip']}%");
        }
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_time ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['createtime'] = array('time',array($start_unixtime,$end_unixtime));
        }
        $sort_fields = array('admin_name','id');
        if ($_REQUEST['sortorder'] != '' && in_array($_REQUEST['sortname'],$sort_fields)) {
            $order = $_REQUEST['sortname'].' '.$_REQUEST['sortorder'];
        }
        return array($condition,$order);
    }
}
