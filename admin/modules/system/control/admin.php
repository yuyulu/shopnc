<?php
/**
 * 权限管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');

class adminControl extends SystemControl{
    private $links = array(
        array('url'=>'act=admin&op=admin','lang'=>'limit_admin'),
        array('url'=>'act=admin&op=gadmin','lang'=>'limit_gadmin'),
    );
    public function __construct(){
        parent::__construct();
        Language::read('admin');
    }

    public function indexOp() {
        $this->adminOp();
    }

    /**
     * 管理员列表
     */
    public function adminOp(){
        $model = Model();
        $admin_list = $model->table('admin,gadmin')->join('left join')->on('gadmin.gid=admin.admin_gid')->limit(200)->select();

        Tpl::output('admin_list',$admin_list);
        Tpl::output('page',$model->showpage());

        Tpl::output('top_link',$this->sublink($this->links,'admin'));
		Tpl::setDirquna('system');
        Tpl::showpage('admin.index');
    }

    /**
     * 管理员删除
     */
    public function admin_delOp(){
        if (!empty($_GET['admin_id'])){
            if ($_GET['admin_id'] == 1){
                showMessage(L('nc_common_save_fail'));
            }
            Model()->table('admin')->where(array('admin_id'=>intval($_GET['admin_id'])))->delete();
            $this->log(L('nc_delete,limit_admin').'[ID:'.intval($_GET['admin_id']).']',1);
            showMessage(L('nc_common_del_succ'));
        }else {
            showMessage(L('nc_common_del_fail'));
        }
    }

    /**
     * 管理员添加
     */
    public function admin_addOp(){
        if (chksubmit()){
            $limit_str = '';
            $model_admin = Model('admin');
            $param['admin_name'] = $_POST['admin_name'];
            $param['admin_gid'] = $_POST['gid'];
            $param['admin_password'] = md5($_POST['admin_password']);
            $rs = $model_admin->addAdmin($param);
            if ($rs){
                $this->log(L('nc_add,limit_admin').'['.$_POST['admin_name'].']',1);
                showMessage(L('nc_common_save_succ'),'index.php?act=admin&op=admin');
            }else {
                showMessage(L('nc_common_save_fail'));
            }
        }

        //得到权限组
        $gadmin = Model('gadmin')->field('gname,gid')->select();
        Tpl::output('gadmin',$gadmin);
        Tpl::output('top_link',$this->sublink($this->links,'admin_add'));
        Tpl::output('limit',$this->permission());
		Tpl::setDirquna('system');
        Tpl::showpage('admin.add');
    }

    /**
     * ajax操作
     */
    public function ajaxOp(){
        switch ($_GET['branch']){
            //管理人员名称验证
            case 'check_admin_name':
                $model_admin = Model('admin');
                $condition['admin_name'] = $_GET['admin_name'];
                $list = $model_admin->infoAdmin($condition);
                if (!empty($list)){
                    exit('false');
                }else {
                    exit('true');
                }
                break;
            //权限组名称验证
            case 'check_gadmin_name':
                $condition = array();
                if (is_numeric($_GET['gid'])){
                    $condition['gid'] = array('neq',intval($_GET['gid']));
                }
                $condition['gname'] = $_GET['gname'];
                $info = Model('gadmin')->where($condition)->find();
                if (!empty($info)){
                    exit('false');
                }else {
                    exit('true');
                }
                break;
        }
    }

    /**
     * 设置管理员权限
     */
    public function admin_editOp(){
        if (chksubmit()){
            //没有更改密码
            if ($_POST['new_pw'] != ''){
                $data['admin_password'] = md5($_POST['new_pw']);
            }
            $data['admin_id'] = intval($_GET['admin_id']);
            $data['admin_gid'] = intval($_POST['gid']);
            //查询管理员信息
            $admin_model = Model('admin');
            $result = $admin_model->updateAdmin($data);
            if ($result){
                $this->log(L('nc_edit,limit_admin').'[ID:'.intval($_GET['admin_id']).']',1);
                showMessage(Language::get('admin_edit_success'),'index.php?act=admin&op=admin');
            }else{
                showMessage(Language::get('admin_edit_fail'),'index.php?act=admin&op=admin');
            }
        }else{
            //查询用户信息
            $admin_model = Model('admin');
            $admininfo = $admin_model->getOneAdmin(intval($_GET['admin_id']));
            if (!is_array($admininfo) || count($admininfo)<=0){
                showMessage(Language::get('admin_edit_admin_error'),'index.php?act=admin&op=admin');
            }
            Tpl::output('admininfo',$admininfo);
            Tpl::output('top_link',$this->sublink($this->links,'admin'));

            //得到权限组
            $gadmin = Model('gadmin')->field('gname,gid')->select();
            Tpl::output('gadmin',$gadmin);
			Tpl::setDirquna('system');
            Tpl::showpage('admin.edit');
        }
    }

    /**
     * 取得所有权限项
     *
     * @return array
     */
    private function permission() {
        return rkcache('admin_menu', true);
    }

    /**
     * 权限组
     */
    public function gadminOp(){
        $model = Model('gadmin');
        if (chksubmit()){
            if (@in_array(1,$_POST['del_id'])){
                showMessage(L('admin_index_not_allow_del'));
            }

            if (!empty($_POST['del_id'])){
                if (is_array($_POST['del_id'])){
                    foreach ($_POST['del_id'] as $k => $v){
                        $model->where(array('gid'=>intval($v)))->delete();
                    }
                }
                $this->log(L('nc_delete,limit_gadmin').'[ID:'.implode(',',$_POST['del_id']).']',1);
                showMessage(L('nc_common_del_succ'));
            }else {
                showMessage(L('nc_common_del_fail'));
            }
        }
        $list = $model->limit(100)->select();

        Tpl::output('list',$list);
        Tpl::output('page',$model->showpage());

        Tpl::output('top_link',$this->sublink($this->links,'gadmin'));
		Tpl::setDirquna('system');
        Tpl::showpage('gadmin.index');
    }

    /**
     * 添加权限组
     */
    public function gadmin_addOp(){
        if (chksubmit()){
            $model = Model('gadmin');
            $data['limits'] = encrypt(serialize($_POST['permission']),MD5_KEY.md5($_POST['gname']));
            $data['gname'] = $_POST['gname'];
            if ($model->insert($data)){
                $this->log(L('nc_add,limit_gadmin').'['.$_POST['gname'].']',1);
                showMessage(L('nc_common_save_succ'),'index.php?act=admin&op=gadmin');
            }else {
                showMessage(L('nc_common_save_fail'));
            }
        }
        Tpl::output('top_link',$this->sublink($this->links,'gadmin_add'));
        Tpl::output('limit',$this->permission());
		Tpl::setDirquna('system');
        Tpl::showpage('gadmin.add');
    }

    /**
     * 设置权限组权限
     */
    public function gadmin_editOp(){
        $model = Model('gadmin');
        $gid = intval($_GET['gid']);

        $ginfo = $model->getby_gid($gid);
        if (empty($ginfo)){
            showMessage(L('admin_set_admin_not_exists'));
        }
        if (chksubmit()){
            $limit_str = '';
            $limit_str = encrypt(serialize($_POST['permission']),MD5_KEY.md5($_POST['gname']));
            $data['limits'] = $limit_str;
            $data['gname']  = $_POST['gname'];
            $update = $model->where(array('gid'=>$gid))->update($data);
            if ($update){
                $this->log(L('nc_edit,limit_gadmin').'['.$_POST['gname'].']',1);
                showMessage(L('nc_common_save_succ'),'index.php?act=admin&op=gadmin');
            }else {
                showMessage(L('nc_common_save_succ'));
            }
        }

        //解析已有权限
        $hlimit = decrypt($ginfo['limits'],MD5_KEY.md5($ginfo['gname']));
        $ginfo['limits'] = unserialize($hlimit);
        Tpl::output('ginfo',$ginfo);
        Tpl::output('limit',$this->permission());
        Tpl::output('top_link',$this->sublink($this->links,'gadmin'));
		Tpl::setDirquna('system');
        Tpl::showpage('gadmin.edit');
    }

    /**
     * 组删除
     */
    public function gadmin_delOp(){
        if (is_numeric($_GET['gid'])){
            Model('gadmin')->where(array('gid'=>intval($_GET['gid'])))->delete();
            $this->log(L('nc_delete,limit_gadmin').'[ID'.intval($_GET['gid']).']',1);
            showMessage(Language::get('nc_common_op_succ'),'index.php?act=admin&op=gadmin');
        }else {
            showMessage(L('nc_common_op_fail'));
        }
    }
}
