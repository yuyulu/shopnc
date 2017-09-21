<?php
/**
 * 相册管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');

class goods_albumControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('goods_album');
    }

    public function indexOp() {
        $this->listOp();
    }

    /**
     * 相册列表
     */
    public function listOp(){
						
		Tpl::setDirquna('shop');
        Tpl::showpage('goods_album.index');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model = Model();
        // 设置页码参数名称
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('aclass_id', 'aclass_name', 'store_id', 'store_name', 'aclass_cover', 'pic_count', 'aclass_des');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
                $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        $page = $_POST['rp'];

        //店铺列表
        $album_list = $model->table('album_class')->where($condition)->order($order)->page($page)->select();

        $storeid_array = array();
        $aclassid_array = array();
        foreach ($album_list as $val) {
            $storeid_array[] = $val['store_id'];
            $aclassid_array[] = $val['aclass_id'];
        }

        // 店铺名称
        $store_list = Model('store')->getStoreList(array('store_id' => array('in', $storeid_array)));
        $store_array = array();
        foreach ($store_list as $val) {
            $store_array[$val['store_id']] = $val['store_name'];
        }

        // 图片数量
        $count_list = $model->cls()->table('album_pic')->field('count(*) as count, aclass_id')->where(array('aclass_id' => array('in', $aclassid_array)))->group('aclass_id')->select();
        $count_array = array();
        foreach ($count_list as $val) {
            $count_array[$val['aclass_id']] = $val['count'];
        }

        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();
        foreach ($album_list as $value) {
            $param = array();
            $operation = "<a class='btn red' href='javascript:void(0);' onclick='fg_del(". $value['aclass_id'] .")'><i class='fa fa-trash-o'></i>删除</a><a class='btn green' href='index.php?act=goods_album&op=pic_list&aclass_id=".$value['aclass_id']."'><i class='fa fa-list-alt'></i>查看</a>";
            $param['operation'] = $operation;
            $param['aclass_id'] = $value['aclass_id'];
            $param['aclass_name'] = $value['aclass_name'];
            $param['store_id'] = $value['store_id'];
            $param['store_name'] = "<a href='". urlShop('show_store', 'index', array('store_id' => $value['store_id'])) ."' target='blank'>". $store_array[$value['store_id']] . "<i class='fa fa-external-link ' title='新窗口打开'></i></a>";
            $param['aclass_cover'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".($value['aclass_cover'] != '' ? cthumb($value['aclass_cover'], 60, $value['store_id']) : ADMIN_SITE_URL.'/templates/'.TPL_NAME.'/images/member/default_image.png').">\")'><i class='fa fa-picture-o'></i></a>";
            $param['pic_count'] = intval($count_array[$value['aclass_id']]);
            $param['aclass_des'] = $value['aclass_des'];
            $data['list'][$value['aclass_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 图片列表
     */
    public function pic_listOp(){
        $model = Model();
        $condition = array();
        $title = '查看全部图片';
        if (is_numeric($_GET['aclass_id'])){
            $condition['aclass_id'] = $_GET['aclass_id'];
            $aclass_info = $model->table('album_class')->where($condition)->find();
            $store_info = Model('store')->getStoreInfoByID($aclass_info['store_id']);
            $title = '查看“'. $store_info['store_name'] .'--'. $aclass_info['aclass_name'] .'”的图片';
        }
        $list = $model->table('album_pic')->where($condition)->order('apic_id desc')->page(36)->select();
        $show_page = $model->showpage();
        Tpl::output('page',$show_page);
        Tpl::output('list',$list);
        Tpl::output('title',$title);
						
		Tpl::setDirquna('shop');
        Tpl::showpage('goods_album.pic_list');
    }

    /**
     * 删除相册
     */
    public function aclass_delOp(){
        $aclass_id = intval($_GET['id']);
        if (!is_numeric($aclass_id)){
            exit(json_encode(array('state'=>false,'msg'=>L('param_error'))));
        }
        $model = Model();
        $pic = $model->table('album_pic')->field('apic_cover')->where(array('aclass_id'=>$aclass_id))->select();
        if (is_array($pic)){
            foreach ($pic as $v) {
                $this->del_file($v['apic_cover']);
            }
        }
        $model->table('album_pic')->where(array('aclass_id'=>$aclass_id))->delete();
        $model->table('album_class')->where(array('aclass_id'=>$aclass_id))->delete();
        $this->log(L('nc_delete,g_album_one').'[ID:'.intval($_GET['aclass_id']).']',1);
        exit(json_encode(array('state'=>true,'msg'=>L('nc_common_del_succ'))));
    }

    /**
     * 删除一张图片及其对应记录
     *
     */
    public function del_album_picOp(){
        list($apic_id,$filename) = @explode('|',$_GET['key']);
        if (!is_numeric($apic_id) || empty($filename)) exit('0');
        $this->del_file($filename);
        Model('album_pic')->where(array('apic_id'=>$apic_id))->delete();
        $this->log(L('nc_delete,g_album_pic_one').'[ID:'.$apic_id.']',1);
        exit('1');
    }

    /**
     * 删除多张图片
     *
     */
    public function del_more_picOp(){
        $model= Model('album_pic');
        $list = $model->where(array('apic_id'=>array('in',$_POST['delbox'])))->select();
        if (is_array($list)){
            foreach ($list as $v) {
                $this->del_file($v['apic_cover']);
            }
        }
        $model->where(array('apic_id'=>array('in',$_POST['delbox'])))->delete();
        $this->log(L('nc_delete,g_album_pic_one').'[ID:'.implode(',',$_POST['delbox']).']',1);
        redirect();
    }

    /**
     * 删除图片文件
     *
     */
    private function del_file($filename){
        //取店铺ID
        if (preg_match('/^(\d+_)/',$filename)){
            $store_id = substr($filename,0,strpos($filename,'_'));
        }else{
            $store_id = Model()->cls()->table('album_pic')->getfby_apic_cover($filename,'store_id');
        }
        if (C('oss.open')) {
            if ($filename != '') {
                oss::del(array(ATTACH_GOODS.DS.$store_id.DS.$filename));
            }
        } else {
            $path = BASE_UPLOAD_PATH.'/'.ATTACH_GOODS.'/'.$store_id.'/'.$filename;
            
            $ext = strrchr($path, '.');
            $type = explode(',', GOODS_IMAGES_EXT);
            foreach ($type as $v) {
                if (is_file($fpath = str_replace('.', $v.'.', $path))){
                    @unlink($fpath);
                }
            }
            if (is_file($path)) @unlink($path);            
        }

    }
}
