<?php
/**
 * Theme Inform
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class theme_informControl extends BaseCircleControl{
    protected $c_id = 0;        // 圈子id
    protected $identity = 0;    // 身份 0游客 1圈主 2管理 3成员 4申请中 5申请失败
    protected $circle_info = array();
    protected $cm_info = array();
    public function __construct(){
        parent::__construct();
        $this->c_id = intval($_GET['c_id']);
        if($this->c_id <= 0){
            echo '<script>DialogManager.close("inform");</script>';
        }
        Tpl::output('c_id', $this->c_id);
        Language::read('manage_inform');
    }
    /**
     * Share the binding
     */
    public function indexOp(){
        // memberInfo
        $this->memberInfo();
        if(empty($this->cm_info)){
            showDialog(L('circle_inform_error'));
        }

        $t_id = intval($_GET['t_id']);
        if($t_id <= 0){
            echo '<script>DialogManager.close("inform");</script>';
        }
        $model = Model();
        $r_id = intval($_GET['r_id']);
        $where = array();
        $where['circle_id'] = $this->c_id;
        $where['theme_id']  = $t_id;
        $where['reply_id']  = $r_id;
        $inform_info = $model->table('circle_inform')->where($where)->find();
        if(!empty($inform_info)){
            echo '<script>showError("'.L('circle_inform_have_been_reported').'");DialogManager.close("inform");</script>';exit;
        }
        if(chksubmit()){
            $circle_info = $model->table('circle')->field('circle_name')->where(array('circle_id'=>$this->c_id))->find();
            if(!empty($circle_info)){
                echo '<script>DialogManager.close("inform");</script>';
            }
            $theme_info = $model->table('circle_theme')->field('theme_name')->where(array('theme_id'=>$t_id))->find();
            if(!empty($theme_info)){
                echo '<script>DialogManager.close("inform");</script>';
            }
            $insert = array();
            $insert['circle_id']        = $this->c_id;
            $insert['circle_name']      = $circle_info['circle_name'];
            $insert['theme_id']         = $t_id;
            $insert['theme_name']       = $theme_info['theme_name'];
            $insert['reply_id']         = $r_id;
            $insert['member_id']        = $_SESSION['member_id'];
            $insert['member_name']      = $_SESSION['member_name'];
            $insert['inform_content']   = $_POST['content'];
            $insert['inform_time']      = time();
            $insert['inform_type']      = 0;
            $insert['inform_state']     = 0;
            $model->table('circle_inform')->insert($insert);

            // Update the inform number
            $update = array(
                        'new_informcount'=>array('exp', 'new_informcount+1')
                    );
            $model->table('circle')->where(array('circle_id'=>$this->c_id))->update($update);

            showDialog(L('nc_common_op_succ'), '', 'succ', '$(\'a[nctype="inform_cancel"]\').click();');
        }
        Tpl::output('t_id', $t_id);
        Tpl::showpage('theme.inform','null_layout');
    }
}
