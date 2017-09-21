<?php
/**
 * SNS动态
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class sns_memberControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('sns_member');
    }
    public function indexOp(){
        $this->member_tagOp();
    }

    /**
     * 标签列表
     */
    public function member_tagOp(){
        // 实例化模型
        $model = Model();
        if(chksubmit()){
            switch ($_POST['submit_type']){
                case 'del':
                    $result = $model->table('sns_membertag')->where(array('mtag_id'=>array('in',implode(',', $_POST['id']))))->delete();

                    if ($result){
                        showMessage(Language::get('nc_common_op_succ'));
                    }else {
                        showMessage(Language::get('nc_common_op_fail'));
                    }
                    break;
            }
        }
        $tag_list = $model->table('sns_membertag')->order('mtag_sort asc')->page(10)->select();
        Tpl::output('showpage', $model->showpage(2));
        Tpl::output('tag_list', $tag_list);
		Tpl::setDirquna('shop');
        Tpl::showpage('sns_membertag.index');
    }

    /**
     * 添加标签
     */
    public function tag_addOp(){
        if(chksubmit()){
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                    array("input"=>$_POST["membertag_name"], "require"=>"true", "message"=>Language::get('sns_member_tag_name_not_null')),
                    array("input"=>$_POST["membertag_sort"], "require"=>"true", 'validator'=>'Number', "message"=>Language::get('sns_member_tag_sort_is_number')),
            );
            $error = $obj_validate->validate();
            if($error != ''){
                showMessage($error);
            }else{
                /**
                 * 上传图片
                 */
                $upload = new UploadFile();
                $upload->set('default_dir',ATTACH_PATH.'/membertag');
                $input = '';
                if (!empty($_FILES['membertag_img']['name'])){
                    $result = $upload->upfile('membertag_img');
                    if (!$result){
                        showMessage($upload->error,'','','error');
                    }else{
                        $input = $upload->file_name;
                    }
                }
                $insert = array(
                        'mtag_name'=>$_POST['membertag_name'],
                        'mtag_sort'=>intval($_POST['membertag_sort']),
                        'mtag_recommend'=>intval($_POST['membertag_recommend']),
                        'mtag_desc'=>trim($_POST['membertag_desc']),
                        'mtag_img'=>$input
                    );
                $model = Model();
                $result = $model->table('sns_membertag')->insert($insert);
                if ($result){
                    $url = array(
                            array(
                                    'url'=>'index.php?act=sns_member&op=tag_add',
                                    'msg'=>Language::get('sns_member_add_once_more'),
                            ),
                            array(
                                    'url'=>'index.php?act=sns_member&op=member_tag',
                                    'msg'=>Language::get('sns_memner_return_list'),
                            )
                    );
                    $this->log(L('nc_add,sns_member_tag').'['.$_POST['membertag_name'].']',1);
                    showMessage(Language::get('nc_common_op_succ'),$url);
                }else {
                    showMessage(Language::get('nc_common_op_fail'));
                }
            }
        }

Tpl::setDirquna('shop');
        Tpl::showpage('sns_membertag.add');
    }

    /**
     * 编辑标签
     */
    public function tag_editOp(){
        // 实例化模型
        $model = Model();

        if(chksubmit()){
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                    array("input"=>$_POST["membertag_name"], "require"=>"true", "message"=>Language::get('sns_member_tag_name_not_null')),
                    array("input"=>$_POST["membertag_sort"], "require"=>"true", 'validator'=>'Number', "message"=>Language::get('sns_member_tag_sort_is_number')),
            );
            $error = $obj_validate->validate();
            if($error != ''){
                showMessage($error);
            }else{
                /**
                 * 上传图片
                 */
                $upload = new UploadFile();
                $upload->set('default_dir',ATTACH_PATH.'/membertag');
                if ($_POST['old_membertag_name'] != ''){
                    $upload->set('file_name', $_POST['old_membertag_name']);
                }
                $input = $_POST['old_membertag_name'];
                if (!empty($_FILES['membertag_img']['name'])){
                    $result = $upload->upfile('membertag_img');
                    if (!$result){
                        showMessage($upload->error,'','','error');
                    }else{
                        $input = $upload->file_name;
                    }
                }
                $update = array();
                $update['mtag_name']        = trim($_POST['membertag_name']);
                $update['mtag_sort']        = intval($_POST['membertag_sort']);
                $update['mtag_recommend']   = intval($_POST['membertag_recommend']);
                $update['mtag_desc']        = trim($_POST['membertag_desc']);
                $update['mtag_img']         = $input;

                $result = $model->table('sns_membertag')->where(array('mtag_id'=>intval($_POST['id'])))->update($update);

                if ($result){
                    $this->log(L('nc_edit,sns_member_tag').'['.$_POST['membertag_name'].']',1);
                    showMessage(Language::get('nc_common_op_succ'),'index.php?act=sns_member&op=member_tag');
                }else {
                    showMessage(Language::get('nc_common_op_fail'));
                }
            }
        }
        // 验证
        $mtag_id = intval($_GET['id']);
        if($mtag_id <= 0){
            showMessage(Language::get('param_error'),'','','error');
        }
        $mtag_info = $model->table('sns_membertag')->where(array('mtag_id'=>$mtag_id))->find();


        if(empty($mtag_info)){
            showMessage(Language::get('param_error'),'','','error');
        }
        Tpl::output('mtag_info', $mtag_info);
		Tpl::setDirquna('shop');
        Tpl::showpage('sns_membertag.edit');
    }
    /**
     * 删除标签
     */
    public function tag_delOp(){
        // 验证
        $mtag_id = intval($_GET['id']);
        if($mtag_id <= 0){
            showMessage(Language::get('param_error'),'','','error');
        }
        $model = Model();
        $result = $model->table('sns_membertag')->where(array('mtag_id'=>$mtag_id))->delete();

        if ($result){
            $this->log(L('nc_del,sns_member_tag').'[ID:'.$mtag_id.']',1);
            showMessage(Language::get('nc_common_del_succ'));
        }else {
            showMessage(Language::get('nc_common_del_fail'));
        }
    }

    /**
     * 推荐标签
     */
    public function tag_recommendOp() {
        // 验证
        $mtag_id = intval($_GET['id']);
        if($mtag_id <= 0){
            showDialog(L('param_error'),'reload');
        }
        Model('sns_membertag')->editSnsMemberTag(array('mtag_id' => $mtag_id), array('mtag_recommend' => intval($_GET['recommend'])));
        showDialog(L('nc_common_op_succ'),'reload','succ');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_tag = Model('sns_membertag');
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('mtag_id','mtag_name','mtag_sort','mtag_recommend');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $tag_list = $model_tag->getSnsMemberTagList($condition, $page, $order);

        $data = array();
        $data['now_page'] = $model_tag->shownowpage();
        $data['total_num'] = $model_tag->gettotalnum();
        foreach ($tag_list as $value) {
            $param = array();
            $param['operation'] = "<a class='btn red' href='index.php?act=sns_member&op=tag_del&id=" . $value['mtag_id'] . "'><i class='fa fa-trash-o'></i>删除</a><span class='btn'><em><i class='fa fa-cog'></i>" . L('nc_set') . " <i class='arrow'></i></em><ul><li><a href='index.php?act=sns_member&op=tag_edit&id=" . $value['mtag_id'] . "'>编辑标签</a></li><li><a href='index.php?act=sns_member&op=tag_member&id=".$value['mtag_id']."'>标签会员</a></li><li><a href='javascript:void(0);' onclick='ajaxget(\"" . urlAdminShop('sns_member','tag_recommend',array('id'=> $value['mtag_id'], 'recommend' => ($value['mtag_recommend'] == 1 ? 0 : 1))) . "\")'>" . ($value['mtag_recommend'] == 1 ? '取消推荐' : '推荐标签') . "</a></li></ul></span>";
            $param['mtag_id'] = $value['mtag_id'];
            $param['mtag_name'] = $value['mtag_name'];
            $param['mtag_sort'] = $value['mtag_sort'];
            $param['mtag_img'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getMemberTagimage($value['mtag_img']).">\")'><i class='fa fa-picture-o'></i></a>";
            $param['mtag_desc'] = $value['mtag_desc'];
            $param['mtag_recommend'] = $value['mtag_recommend'] ==  '1' ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            $data['list'][$value['mtag_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }


    /**
     * 标签所属会员列表
     */
    public function tag_memberOp(){
        // 验证
        $mtag_id = intval($_GET['id']);
        if($mtag_id <= 0){
            showMessage(Language::get('param_error'),'','','error');
        }
        $mt_info = Model('sns_membertag')->getSnsMemberTagInfoById($mtag_id);
        Tpl::output('mt_info', $mt_info);
		Tpl::setDirquna('shop');
        Tpl::showpage('sns_membertag.memberlist');
    }

    /**
     * 推荐标签会员
     */
    public function tag_mrecommendOp() {
        // 验证
        $mtag_id = intval($_GET['id']);
        $member_id = intval($_GET['member_id']);
        if($mtag_id <= 0 || $member_id <= 0){
            showDialog(L('param_error'),'reload');
        }
        Model('sns_mtagmember')->editSnsMTagMember(array('mtag_id' => $mtag_id, 'member_id' => $member_id), array('recommend' => intval($_GET['recommend'])));
        showDialog(L('nc_common_op_succ'),'reload','succ');
    }

    /**
     * 删除添加标签会员
     */
    public function mtag_delOp(){
        $mtag_id = intval($_GET['id']);
        $member_id = intval($_GET['member_id']);
        if($mtag_id <= 0 || $member_id <= 0){
            showMessage(Language::get('miss_argument'));
        }
        // 条件
        $where = array(
                'mtag_id'=>$mtag_id,
                'member_id'=>$member_id
        );
        $result = Model('sns_mtagmember')->delSnsMTagMember($where);
        if($result){
            $this->log(L('nc_del,sns_member_tag').'[ID:'.$mtag_id.']',1);
            showMessage(Language::get('nc_common_del_succ'));
        }else{
            showMessage(Language::get('nc_common_del_fail'));
        }
    }
    /**
     * 输出XML数据
     */
    public function get_tm_xmlOp() {
        // 验证
        $mtag_id = intval($_GET['id']);
        if($mtag_id <= 0){
            showMessage(Language::get('param_error'),'','','error');
        }

        $model_mtag = Model('sns_mtagmember');
        $condition = array();
        $condition['mtag_id'] = $mtag_id;
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('member_id');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $tag_list = $model_mtag->getSnsMTagMemberList($condition, $page, $order);
        $memberid_array = array();
        foreach ($tag_list as $value) {
            $memberid_array[] = $value['member_id'];
        }
        $member_list = Model('member')->getMemberList(array('member_id' => array('in', $memberid_array)));
        $member_array = array();
        foreach ($member_list as $value) {
            $member_array[$value['member_id']] = $value['member_name'];
        }

        $data = array();
        $data['now_page'] = $model_mtag->shownowpage();
        $data['total_num'] = $model_mtag->gettotalnum();
        foreach ($tag_list as $value) {
            $param = array();
            $param['operation'] = "<a class='btn red' href='index.php?act=sns_member&op=mtag_del&id=" . $value['mtag_id'] . "&member_id=" . $value['member_id']. "'><i class='fa fa-list-alt'></i>删除</a><a class='btn green' href='javascript:void(0);' onclick='ajaxget(\"" . urlAdminShop('sns_member','tag_mrecommend',array('id'=> $value['mtag_id'],'member_id' => $value['member_id'], 'recommend' => ($value['recommend'] == 1 ? 0 : 1))) . "\")'><i class='fa " . ($value['recommend'] == 1 ? 'fa-thumbs-o-down' : 'fa-thumbs-o-up') . "'></i>" . ($value['recommend'] == 1 ? '取消推荐' : '推荐会员') . "</a>";
            $param['member_id'] = $value['member_id'];
            $param['member_name'] = "<img src=".getMemberAvatarForID($value['member_id'])." class='user-avatar' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getMemberAvatarForID($value['member_id']).">\")'>" .$member_array[$value['member_id']];
            $param['recommend'] = $value['recommend'] ==  '1' ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            $data['list'][$value['mtag_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }
}
