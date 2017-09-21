<?php
/**
 * 圈子话题管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class circle_themeControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('circle');
    }

    public function indexOp() {
        $this->theme_listOp();
    }
    /**
     * 话题列表
     */
    public function theme_listOp(){
        Tpl::setDirquna('circle');
Tpl::showpage('circle_theme.list');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model = Model();
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('theme_id', 'theme_name', 'is_recommend', 'affix', 'circle_name', 'circle_id', 'member_id', 'member_name', 'is_identity', 
                'theme_addtime', 'theme_addtime', 'theme_likecount', 'theme_commentcount', 'theme_browsecount', 'theme_sharecount', 'is_stick',
                'is_digest', 'theme_special'
        );
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $theme_list = $model->table('circle_theme')->where($condition)->order($order)->page($page)->select();

        $themeid_array = array();
        foreach ($theme_list as $value) {
            $themeid_array[] = $value['theme_id'];
        }

        // 附件
        $affix_list = $model->table('circle_affix')->field('theme_id,max(affix_filethumb) as affix_filethumb')->where(array('theme_id'=>array('in', $themeid_array), 'affix_type'=>1))->group('theme_id')->select();
        $affix_list = array_under_reset($affix_list, 'theme_id');

        // 成员身份
        $identity_array = $this->getMemberIdentity();

        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();
        foreach ($theme_list as $value) {
            $param = array();
            $operation = "<a class='btn red' href='javascript:void(0);' onclick=\"fg_del('".$value['theme_id']."')\"><i class='fa fa-trash-o'></i>删除</a>";
            $operation .= "<span class='btn'><em><i class='fa fa-cog'></i>" . L('nc_set') . " <i class='arrow'></i></em><ul>";
            $operation .= "<li><a href='index.php?act=circle_theme&op=theme_info&t_id=".$value['theme_id']."'>查看话题内容</a></li>";
            $operation .= "<li><a href='index.php?act=circle_theme&op=theme_reply&t_id=".$value['theme_id']."'>查看话题回复</a></li>";
            if ($value['is_recommend'] == 1) {
                $operation .= "<li><a href='javascript:void(0);' onclick=\"fg_recommend('".$value['theme_id']."', 0)\">取消话题推荐</a></li>";
            } else {
                $operation .= "<li><a href='javascript:void(0);' onclick=\"fg_recommend('".$value['theme_id']."', 1)\">推荐优秀话题</a></li>";
            }
            $operation .= "</ul></span>";
            $param['operation'] = $operation;
            $param['theme_id'] = $value['theme_id'];
            $param['theme_name'] = $value['theme_name'];
            $param['is_recommend'] = $value['is_recommend'] == '1' ? '是' : '否';
            $param['affix'] = isset($affix_list[$value['theme_id']]) ? "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".themeImageUrl($affix_list[$value['theme_id']]['affix_filethumb']).">\")'><i class='fa fa-picture-o'></i></a>" : '--';
            $param['circle_name'] = $value['circle_name'];
            $param['circle_id'] = $value['circle_id'];
            $param['member_id'] = $value['member_id'];
            $param['member_name'] = $value['member_name'];
            $param['is_identity'] = $identity_array[$value['is_identity']];
            $param['theme_addtime'] = date('Y-m-d H:i:s', $value['theme_addtime']);
            $param['theme_likecount'] = $value['theme_likecount'];
            $param['theme_commentcount'] = $value['theme_commentcount'];
            $param['theme_browsecount'] = $value['theme_browsecount'];
            $param['theme_sharecount'] = $value['theme_sharecount'];
            $param['is_stick'] = $value['is_stick'] == '1' ? '是' : '否';
            $param['is_digest'] = $value['is_digest'] == '1' ? '是' : '否';
            $param['theme_special'] = $value['theme_special'] == '1' ? '投票' : '普通';
            $data['list'][$value['theme_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 成员身份
     * @return multitype:string
     */
    private function getMemberIdentity() {
        return array(
                '1' => '圈主',
                '2' => '管理',
                '3' => '成员'
        );
    }

    /**
     * 话题详细
     */
    public function theme_infoOp(){
        $model = Model();
        $t_id = intval($_GET['t_id']);
        $theme_info = $model->table('circle_theme')->where(array('theme_id'=>$t_id))->find();

        Tpl::output('theme_info', $theme_info);

        if($theme_info['theme_special'] == 1){
            $poll_info = $model->table('circle_thpoll')->where(array('theme_id'=>$t_id))->find();
            $option_list = $model->table('circle_thpolloption')->where(array('theme_id'=>$t_id))->order('pollop_sort asc')->select();
            Tpl::output('poll_info', $poll_info);
            Tpl::output('option_list', $option_list);
        }
        Tpl::setDirquna('circle');
Tpl::showpage('circle_theme.info');
    }
    /**
     * 删除话题
     */
    public function theme_delOp(){
        $model = Model();
        // 验证话题
        $t_id = intval($_GET['t_id']); $c_id = intval($_GET['c_id']);
        $theme_info = $model->table('circle_theme')->where(array('theme_id'=>$t_id, 'circle_id'=>$c_id))->find();
        if(empty($theme_info)){
            showMessage(L('param_error'));
        }

        // 删除附件
        $affix_list = $model->table('circle_affix')->where(array('theme_id'=>$t_id))->select();
        if(!empty($affix_list)){
            foreach ($affix_list as $val){
                @unlink(themeImagePath($val['affix_filename']));
                @unlink(themeImagePath($val['affix_filethumb']));
            }
            $model->table('circle_affix')->where(array('theme_id'=>$t_id))->delete();
        }

        // 删除商品
        $model->table('circle_thg')->where(array('theme_id'=>$t_id))->delete();

        // 删除赞表相关
        $model->table('circle_like')->where(array('theme_id'=>$t_id))->delete();

        // 删除回复
        $model->table('circle_threply')->where(array('theme_id'=>$t_id))->delete();

        // The recycle bin add delete records
        $param = array();
        $param['theme_id']  = $t_id;
        $param['op_id']     = 0;
        $param['op_name']   = L('cirlce_administrator');
        $param['type']      = 'admintheme';
        Model('circle_recycle')->saveRecycle($param, $theme_info);

        // 删除话题
        $model->table('circle_theme')->where(array('theme_id'=>$t_id))->delete();


        // 更新圈子主题数量
        $model->table('circle')->where(array('circle_id'=>$c_id))->update(array('circle_thcount'=>array('exp','circle_thcount-1')));

        // Experience
        if(intval($theme_info['theme_exp']) > 0){
            $param = array();
            $param['member_id']     = $theme_info['member_id'];
            $param['member_name']   = $theme_info['member_name'];
            $param['circle_id']     = $theme_info['circle_id'];
            $param['itemid']        = $t_id;
            $param['type']          = 'delRelease';
            $param['exp']           = $theme_info['theme_exp'];
            Model('circle_exp')->saveExp($param);
        }

        showMessage(L('nc_common_op_succ'), 'index.php?act=circle_theme&op=theme_list');
    }
    /**
     * 话题回复
     */
    public function theme_replyOp(){
        $model = Model();
        if(chksubmit()){
            $t_id = intval($_POST['t_id']);
            if (!empty($_POST['check_reply_id']) && is_array($_POST['check_reply_id'])){
                foreach ($_POST['check_reply_id'] as $r_id){
                    // 验证回复
                    $reply_info = $model->table('circle_threply')->where(array('theme_id'=>$t_id, 'reply_id'=>$r_id))->find();
                    if(empty($reply_info)){
                        showMessage(L('param_error'));
                    }

                    // 删除附件
                    $affix_list = $model->table('circle_affix')->where(array('affix_type'=>2, 'theme_id'=>$t_id, 'reply_id'=>$r_id))->select();
                    if(!empty($affix_list)){
                        foreach ($affix_list as $val){
                            @unlink(themeImagePath($val['affix_filename']));
                            @unlink(themeImagePath($val['affix_filethumb']));
                        }
                        $model->table('circle_affix')->where(array('affix_type'=>2 ,'theme_id'=>$t_id, 'reply_id'=>$r_id))->delete();
                    }

                    // 删除商品
                    $model->table('circle_thg')->where(array('theme_id'=>$t_id, 'reply_id'=>$r_id))->delete();

                    // 添加删除记录
                    $param = array();
                    $param['theme_id']  = $t_id;
                    $param['reply_id']  = $r_id;
                    $param['op_id']     = 0;
                    $param['op_name']   = L('cirlce_administrator');
                    $param['type']      = 'adminreply';
                    Model('circle_recycle')->saveRecycle($param, $reply_info);

                    // 删除回复
                    $model->table('circle_threply')->where(array('theme_id'=>$t_id, 'reply_id'=>$r_id))->delete();

                    // 更新话题回复数
                    $model->table('circle_theme')->where(array('theme_id'=>$t_id))->update(array('theme_commentcount'=>array('exp', 'theme_commentcount-1')));

                    // 经验
                    if(intval($reply_info['reply_exp']) > 0){
                        $param = array();
                        $param['member_id']     = $reply_info['member_id'];
                        $param['member_name']   = $reply_info['member_name'];
                        $param['circle_id']     = $reply_info['circle_id'];
                        $param['itemid']        = $t_id.','.$r_id;
                        $param['type']          = 'delReplied';
                        $param['exp']           = $reply_info['reply_exp'];
                        Model('circle_exp')->saveExp($param);
                    }
                }
            }

            showMessage(L('nc_common_op_succ'));
        }
        $t_id = intval($_GET['t_id']);
        $reply_list = $model->table('circle_threply')->where(array('theme_id'=>$t_id))->page(10)->select();
        Tpl::output('t_id', $t_id);
        Tpl::output('page', $model->showpage(2));
        Tpl::output('reply_list', $reply_list);
        Tpl::setDirquna('circle');
Tpl::showpage('circle_theme.reply');
    }

    /**
     * 输出XML数据
     */
    public function get_reply_xmlOp() {
        $model = Model();
        $condition = array();
        $condition['theme_id'] = intval($_GET['t_id']);
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('reply_content', 'member_name', 'reply_addtime');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $reply_list = $model->table('circle_threply')->where($condition)->order($order)->page($page)->select();

        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();
        foreach ($reply_list as $value) {
            $param = array();
            $param['operation'] = "<a class='btn green' href='javascript:void(0);' onclick=\"ajax_form('reply_content', '回复详细', 'index.php?act=circle_theme&op=reply_info&t_id=".$value['theme_id']."&r_id=".$value['reply_id']."', 480)\"><i class='fa fa-list-alt'></i>查看</a><a class='btn red' href=\"javascript:void(0);\" onclick=\"fg_del(".$value['theme_id'].", ".$value['reply_id'].")\"><i class='fa fa-trash-o'></i>删除</a>";
            $param['reply_content'] = removeUBBTag($value['reply_content']);
            $param['member_name'] = $value['member_name'];
            $param['reply_addtime'] = date('Y-m-d H:i:s', $value['reply_addtime']);
            $data['list'][$value['theme_id']. '|' . $value['reply_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }


    /**
     * 话题详细
     */
    public function reply_infoOp(){
        $model = Model();
        $reply_info = $model->table('circle_threply')->where(array('theme_id'=>$_GET['t_id'], 'reply_id' => intval($_GET['r_id'])))->find();

        Tpl::output('reply_info', $reply_info);
        Tpl::setDirquna('circle');
Tpl::showpage('circle_theme.reply_info', 'null_layout');
    }
    /**
     * 话题回复删除
     */
    public function theme_replydelOp(){
        $t_id = intval($_GET['t_id']);
        $r_id = intval($_GET['r_id']);
        $model = Model();
        // 验证回复
        $reply_info = $model->table('circle_threply')->where(array('theme_id'=>$t_id, 'reply_id'=>$r_id))->find();
        if(empty($reply_info)){
            exit(json_encode(array('state'=>false,'msg'=>L('param_error'))));
        }

        // 删除附件
        $affix_list = $model->table('circle_affix')->where(array('affix_type'=>2, 'theme_id'=>$t_id, 'reply_id'=>$r_id))->select();
        if(!empty($affix_list)){
            foreach ($affix_list as $val){
                @unlink(themeImagePath($val['affix_filename']));
                @unlink(themeImagePath($val['affix_filethumb']));
            }
            $model->table('circle_affix')->where(array('affix_type'=>2 ,'theme_id'=>$t_id, 'reply_id'=>$r_id))->delete();
        }

        // 删除商品
        $model->table('circle_thg')->where(array('theme_id'=>$t_id, 'reply_id'=>$r_id))->delete();

        // The recycle bin add delete records
        $param = array();
        $param['theme_id']  = $t_id;
        $param['reply_id']  = $r_id;
        $param['op_id']     = 0;
        $param['op_name']   = L('cirlce_administrator');
        $param['type']      = 'adminreply';
        Model('circle_recycle')->saveRecycle($param, $reply_info);

        // 删除回复
        $model->table('circle_threply')->where(array('theme_id'=>$t_id, 'reply_id'=>$r_id))->delete();

        // 更新话题回复数
        $model->table('circle_theme')->where(array('theme_id'=>$t_id))->update(array('theme_commentcount'=>array('exp', 'theme_commentcount-1')));

        // Experience
        if(intval($reply_info['reply_exp']) > 0){
            $param = array();
            $param['member_id']     = $reply_info['member_id'];
            $param['member_name']   = $reply_info['member_name'];
            $param['circle_id']     = $reply_info['circle_id'];
            $param['itemid']        = $t_id.','.$r_id;
            $param['type']          = 'delReplied';
            $param['exp']           = $reply_info['reply_exp'];
            Model('circle_exp')->saveExp($param);
        }
        exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
    }
    /**
     * 推荐/取消话题
     */
    public function theme_recommendOp(){
        $update = array('is_recommend'=>($_GET['value'] == '1' ? 1 : 0));
        Model()->table('circle_theme')->where(array('theme_id'=>intval($_GET['id'])))->update($update);
        exit(json_encode(array('state'=>true,'msg'=>'操作成功')));
    }
}
