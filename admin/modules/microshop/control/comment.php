<?php
/**
 * 微商城
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class commentControl extends SystemControl{

    const GOODS_FLAG = 1;
    const PERSONAL_FLAG = 2;
    const ALBUM_FLAG = 3;
    const STORE_FLAG = 4;

    public function __construct(){
        parent::__construct();
        Language::read('store');
        Language::read('microshop');
    }

    public function indexOp() {
       $this->comment_manageOp();
    }


    /**
     * 评论管理
     */
    public function comment_manageOp()
    {
        $this->get_channel_array();
        Tpl::setDirquna('microshop');
Tpl::showpage('microshop_comment.manage');
    }

    /**
     * 评论管理XML
     */
    public function comment_manage_xmlOp()
    {
        $condition = array();

        if ($_REQUEST['advanced']) {
            if (strlen($q = trim((string) $_REQUEST['comment_id']))) {
                $condition['comment_id'] = (int) $q;
            }
            if (strlen($q = trim((string) $_REQUEST['member_name']))) {
                $condition['member_name'] = array('like', '%' . $q . '%');
            }
            if (strlen($q = trim((string) $_REQUEST['comment_type']))) {
                $condition['comment_type'] = (int) $q;
            }
            if (strlen($q = trim((string) $_REQUEST['comment_object_id']))) {
                $condition['comment_object_id'] = (int) $q;
            }
            if (strlen($q = trim((string) $_REQUEST['comment_message']))) {
                $condition['comment_message'] = array('like', '%' . $q . '%');
            }
        } else {
            if (strlen($q = trim($_REQUEST['query'])) > 0) {
                switch ($_REQUEST['qtype']) {
                    case 'comment_id':
                        $condition['comment_id'] = (int) $q;
                        break;
                    case 'member_name':
                        $condition['member_name'] = array('like', '%' . $q . '%');
                        break;
                    case 'comment_object_id':
                        $condition['comment_object_id'] = (int) $q;
                        break;
                    case 'comment_message':
                        $condition['comment_message'] = array('like', '%' . $q . '%');
                        break;
                }
            }
        }

        $model_comment = Model("micro_comment");
        $list = (array) $model_comment->getListWithUserInfo($condition, $_REQUEST['rp'], 'comment_time desc');

        $data = array();
        $data['now_page'] = $model_comment->shownowpage();
        $data['total_num'] = $model_comment->gettotalnum();

        $channel_array = $this->get_channel_array();

        foreach ($list as $val) {
            $channel = $channel_array[$val['comment_type']];
            $o = '<a class="btn red confirm-del-on-click" href="index.php?act=comment&op=comment_drop&comment_id=' .
                    $val['comment_id'] .
                    '"><i class="fa fa-trash"></i>删除</a>';

            $o .= '<a class="btn green" target="_blank" href="' .
                MICROSHOP_SITE_URL.DS.'index.php?act=' .
                $channel['key'] .
                '&op=detail&' .
                $channel['key'] .
                '_id=' .
                $val['comment_object_id'] .
                '"><i class="fa fa-list-alt"></i>查看</a>';

            $i = array();
            $i['operation'] = $o;
            $i['comment_id'] = $val['comment_id'];

            $i['member_name'] = '<a href="' .
                MICROSHOP_SITE_URL.DS.'index.php?act=home&member_id=' .
                $val['comment_member_id'] .
                '" target="_blank">' .
                $val['member_name'] .
                '</a>';

            $i['comment_type'] = $channel['name'];
            $i['comment_object_id'] = $val['comment_object_id'];
            $i['comment_message'] = parsesmiles($val['comment_message']);

            $data['list'][$val['comment_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 评论删除
     */
    public function comment_dropOp() {
        $model = Model('micro_comment');
        $condition = array();
        $condition['comment_id'] = array('in',trim($_REQUEST['comment_id']));
        $result = $model->drop($condition);
        if($result) {
            showMessage(Language::get('nc_common_del_succ'),'');
        } else {
            showMessage(Language::get('nc_common_del_fail'),'','','error');
        }
    }

    /**
     * 获取频道数组
     */
    private function get_channel_array() {
        $channel_array = array();
        $channel_array[self::GOODS_FLAG] = array('name'=>Language::get('nc_microshop_goods'),'key'=>'goods');
        $channel_array[self::PERSONAL_FLAG] = array('name'=>Language::get('nc_microshop_personal'),'key'=>'personal');
        $channel_array[self::STORE_FLAG] = array('name'=>Language::get('nc_microshop_store'),'key'=>'store');
        Tpl::output('channel_array', $channel_array);

        return $channel_array;
    }
}
