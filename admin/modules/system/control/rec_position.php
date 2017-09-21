<?php
/**
 * 推荐位
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class rec_positionControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('rec_position');
    }

    public function indexOp() {
        $this->rec_listOp();
    }

    /**
     * 推荐位列表
     *
     */
    public function rec_listOp(){
		Tpl::setDirquna('system');
        Tpl::showpage('rec_position.index');
    }

    public function get_xmlOp(){
        $lang = Language::getLangContent();
        $model_rec = model('rec_position');
        $condition  = array();
        if ($_POST['query'] != '' && in_array($_POST['qtype'],array('ap_name'))) {
            $condition[$_POST['qtype']] = $_POST['query'];
        }
        if ($_POST['qtype'] != '') {
            $condition['pic_type'] = intval($_POST['qtype']);
        }
        if (!empty($_POST['query'])) {
            $condition['title'] = array('like','%'.$_POST['query'].'%');
        }
        if (in_array($_POST['sortname'],array('rec_id')) && in_array($_POST['sortorder'],array('asc','desc'))) {
            $order = $_POST['sortname'].' '.$_POST['sortorder'];
        }
        $list = $model_rec->where($condition)->order($order)->page($_POST['rp'])->select();
        $data = array();
        $data['now_page'] = $model_rec->shownowpage();
        $data['total_num'] = $model_rec->gettotalnum();
        foreach ($list as $k => $info) {
            $list = array();$operation_detail = '';
            $info['content'] = unserialize($info['content']);
            $list['operation'] = "<a class='btn red' onclick=\"fg_delete({$info['rec_id']})\"><i class='fa fa-trash-o'></i>删除</a>";
            $operation_detail = "<li><a href='index.php?act=rec_position&op=rec_edit&rec_id={$info['rec_id']}'>编辑内容</a></li>";
            $operation_detail .= "<li><a href=\"javascript:void(0);\" rec_id=\"{$info['rec_id']}\" nctype=\"jscode\"></i>调用代码</a></li>";
            $operation_detail .= "<li><a href=\"index.php?act=rec_position&op=rec_view&rec_id={$info['rec_id']}\" target=\"_blank\">预览效果</a></li>";
            if ($operation_detail) {
                $list['operation'] .= "<span class='btn'><em><i class='fa fa-cog'></i>设置 <i class='arrow'></i></em><ul>{$operation_detail}</ul>";
            }
            $list['title'] = $info['title'];
            $list['pic_type'] = str_replace(array(0,1,2),array($lang['rec_ps_txt'],$lang['rec_ps_picb'],$lang['rec_ps_picy']),$info['pic_type']);
            $list['pic_type'] .= $info['pic_type'] != 0 ? (count($info['content']['body']) == 1 ? $lang['rec_ps_picdan'] : $lang['rec_ps_picduo']) : null;
            if($info['pic_type'] == 0){
                $list['content'] = $info['content']['body'][0]['title'];
            } else {
                $list['content'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".UPLOAD_SITE_URL.'/'.$info['content']['body'][0]['title'].">\")'><i class='fa fa-picture-o'></i></a>";
            }
            $list['url'] = $info['content']['body'][0]['url'];
            $list['target'] = $info['content']['target'] == 1 ? '<span class="no"><i class="fa fa-ban"></i>否</span>' : '<span class="yes"><i class="fa fa-check-circle"></i>是</span>';
            $data['list'][$info['rec_id']] = $list;
        }
        exit(Tpl::flexigridXML($data));
    }

    /**
     * 新增推荐位
     *
     */
    public function rec_addOp(){
		Tpl::setDirquna('system');
        Tpl::showpage('rec_position.add');
    }

    /**
     * 编辑推荐位
     *
     */
    public function rec_editOp(){
        $model = Model('rec_position');
        $info = $model->where(array('rec_id'=>intval($_GET['rec_id'])))->find();
        if (!$info) showMessage(Language::get('no_record'));
        $info['content'] = unserialize($info['content']);
        foreach((array)$info['content']['body'] as $k=>$v){
            if ($info['pic_type'] == 1){
                $info['content']['body'][$k]['title'] = UPLOAD_SITE_URL.'/'.$v['title'];
            }
        }
        Tpl::output('info',$info);
		Tpl::setDirquna('system');
        Tpl::showpage('rec_position.edit');
    }

    /**
     * 删除
     *
     */
    public function rec_delOp(){
        $model = Model('rec_position');
        if (preg_match('/^[\d,]+$/', $_GET['rec_id'])) {
            $_GET['rec_id'] = explode(',',trim($_GET['rec_id'],','));
            if (is_array($_GET['rec_id'])) {
                foreach($_GET['rec_id'] as $rec_id) {
                    $info = $model->where(array('rec_id'=>$rec_id))->find();
                    if (!$info) {
                        exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
                    }
                    $info['content'] = unserialize($info['content']);
                    $result = $model->where(array('rec_id'=>$rec_id))->delete();
                    if ($result){
                        if ($info['pic_type'] == 1 && is_array($info['content']['body'])){
                            foreach ($info['content']['body'] as $v){
                                @unlink(BASE_UPLOAD_PATH.'/'.$v['title']);
                            }
                        }
                        dkcache("rec_position/{$info['rec_id']}");
                        $this->log(L('nc_del,rec_position').'[ID:'.$rec_id.']',1);
                    }else{
                        exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
                    }
                }
                exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
            }
        }
        exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
    }

    /**
     * 添加保存推荐位
     *
     */
    public function rec_saveOp(){
        $array = array();
        $data = array();
        $pattern = "/^http:\/\/[A-Za-z0-9]+[A-Za-z0-9.]+\.[A-Za-z0-9]+/i";
        //文字类型
        if ($_POST['rec_type'] == 1){
            if (is_array($_POST['txt']) && is_array($_POST['urltxt'])){
                foreach ($_POST['txt'] as $k=>$v){
                    if (trim($v) == '') continue;
                    $c = count($array['body']);
                    $array['body'][$c]['title'] = $v;
                    $array['body'][$c]['url'] = preg_match($pattern,$_POST['urltxt'][$k]) ? $_POST['urltxt'][$k] : '';
                    $data['pic_type'] = 0;
                }
            }else{
                showMessage(Language::get('param_error'));
            }
        }elseif ($_POST['rec_type'] == 2 && $_POST['pic_type'] == 1){
            //本地图片上传
            if (is_array($_FILES['pic']['tmp_name'])){
                foreach($_FILES['pic']['tmp_name'] as $k=>$v){
                    if (empty($v)) continue;
                    $ext = strtolower(pathinfo($_FILES['pic']['name'][$k], PATHINFO_EXTENSION));
                    if (in_array($ext,array('jpg','jpeg','gif','png'))){
                        $filename = substr(md5(microtime(true)),0,16).rand(100,999).$k.'.'.$ext;
                        if ($_FILES['pic']['size'][$k]<1024*1024){
                            move_uploaded_file($v,BASE_UPLOAD_PATH.'/'.ATTACH_REC_POSITION.'/'.$filename);
                        }
                        if ($_FILES['pic']['error'][$k] != 0) showMessage(Language::get('nc_common_op_fail'));
                        $c = count($array['body']);
                        $array['body'][$c]['title'] = ATTACH_REC_POSITION.'/'.$filename;
                        $array['body'][$c]['url']   = preg_match($pattern,$_POST['urlup'][$k]) ? $_POST['urlup'][$k] : '';
                        $array['width']             = is_numeric($_POST['rwidth']) ? $_POST['rwidth'] : '';
                        $array['height']            = is_numeric($_POST['rheight']) ? $_POST['rheight'] : '';
                        $data['pic_type']           = 1;
                    }
                    if (empty($array)) showMessage(Language::get('param_error'));
                }
            }
        }elseif ($_POST['rec_type'] == 2 && $_POST['pic_type'] == 2){

            //远程图片
            if (is_array($_POST['pic'])){
                foreach ($_POST['pic'] as $k=>$v){
                    if (!preg_match("/^(http\:\/\/)/i",$v)) continue;
                    $ext = strtolower(pathinfo($v, PATHINFO_EXTENSION));
                    if (in_array($ext,array('jpg','jpeg','gif','png','bmp'))){
                        $c = count($array['body']);
                        $array['body'][$c]['title'] = $v;
                        $array['body'][$c]['url']   = preg_match($pattern,$_POST['urlremote'][$k]) ? $_POST['urlremote'][$k] : '';
                        $array['width']             = is_numeric($_POST['rwidth']) ? $_POST['rwidth'] : '';
                        $array['height']            = is_numeric($_POST['rheight']) ? $_POST['rheight'] : '';
                        $data['pic_type']           = 2;
                    }
                    if (empty($array)) showMessage(Language::get('param_error'));
                }
            }
        }else{
            showMessage(Language::get('param_error'));
        }
        $array['target']    = intval($_POST['rtarget']);
        $data['title']      = $_POST['rtitle'];
        $data['content']    = serialize($array);
        $model = Model('rec_position');
        $model->insert($data);
        $this->log(L('nc_add,rec_position').'['.$_POST['rtitle'].']',1);
        showMessage(Language::get('nc_common_save_succ'),'index.php?act=rec_position&op=rec_list');
    }

    /**
     * 编辑保存推荐位
     *
     */
    public function rec_edit_saveOp(){
        if (!is_numeric($_POST['rec_id'])) showMessage(Language::get('param_error'));
        $array = array();
        $data = array();
        $pattern = "/^http:\/\/[A-Za-z0-9]+[A-Za-z0-9.]+\.[A-Za-z0-9]+/i";
        //文字类型
        if ($_POST['rec_type'] == 1){
            if (is_array($_POST['txt']) && is_array($_POST['urltxt'])){
                foreach ($_POST['txt'] as $k=>$v){
                    if (trim($v) == '') continue;
                    $c = count($array['body']);
                    $array['body'][$c]['title'] = $v;
                    $array['body'][$c]['url'] = preg_match($pattern,$_POST['urltxt'][$k]) ? $_POST['urltxt'][$k] : '';
                    $data['pic_type'] = 0;
                }
            }else{
                showMessage(Language::get('param_error'));
            }
        }elseif ($_POST['rec_type'] == 2 && $_POST['pic_type'] == 1){
            //本地图片上传
            if (is_array($_FILES['pic']['tmp_name'])){
                foreach($_FILES['pic']['tmp_name'] as $k=>$v){
                    //未上传新图的，还用老图
                    if (empty($v) && !empty($_POST['opic'][$k])){
                        $array['body'][$k]['title'] = str_ireplace(UPLOAD_SITE_URL.'/','',$_POST['opic'][$k]);
                        $array['body'][$k]['url']   = preg_match($pattern,$_POST['urlup'][$k]) ? $_POST['urlup'][$k] : '';
                    }
                    $ext = strtolower(pathinfo($_FILES['pic']['name'][$k], PATHINFO_EXTENSION));
                    if (in_array($ext,array('jpg','jpeg','gif','png','bmp'))){
                        $filename = substr(md5(microtime(true)),0,16).rand(100,999).$k.'.'.$ext;
                        if ($_FILES['pic']['size'][$k]<1024*1024){
                            move_uploaded_file($v,BASE_UPLOAD_PATH.'/'.ATTACH_REC_POSITION.'/'.$filename);
                        }
                        if ($_FILES['pic']['error'][$k] != 0) showMessage(Language::get('nc_common_save_fail'));

                        //删除老图
                        $old_file = str_ireplace(array(UPLOAD_SITE_URL,'..'),array(BASE_UPLOAD_PATH,''),$_POST['opic'][$k]);
                        if (is_file($old_file)) @unlink($old_file);

                        $array['body'][$k]['title'] = ATTACH_REC_POSITION.'/'.$filename;
                        $array['body'][$k]['url']   = preg_match($pattern,$_POST['urlup'][$k]) ? $_POST['urlup'][$k] : '';
                        $data['pic_type']           = 1;
                    }
                }

                //最后删除数据库里有但没有POST过来的图片
                $model = Model('rec_position');
                $oinfo = $model->where(array('rec_id'=>$_POST['rec_id']))->find();
                $oinfo = unserialize($oinfo['content']);
                foreach ($oinfo['body'] as $k=>$v) {
                    if (!in_array(UPLOAD_SITE_URL.'/'.$v['title'],(array)$_POST['opic'])){
                        if (is_file(BASE_UPLOAD_PATH.'/'.$v['title'])){
                            @unlink(BASE_UPLOAD_PATH.'/'.$v['title']);
                        }
                    }
                }
                unset($oinfo);
            }
            //如果是上传图片，则取原图片地址
            if (empty($array)){
                if (is_array($_POST['opic'])){
                    foreach ($_POST['opic'] as $k=>$v){
                        $array['body'][$k]['title'] = $v;
                        $array['body'][$k]['url']   = preg_match($pattern,$_POST['urlup'][$k]) ? $_POST['urlup'][$k] : '';
                    }
                }
            }
        }elseif ($_POST['rec_type'] == 2 && $_POST['pic_type'] == 2){

            //远程图片
            if (is_array($_POST['pic'])){
                foreach ($_POST['pic'] as $k=>$v){
                    if (!preg_match("/^(http\:\/\/)/i",$v)) continue;
                    $ext = strtolower(pathinfo($v, PATHINFO_EXTENSION));
                    if (in_array($ext,array('jpg','jpeg','gif','png','bmp'))){
                        $c = count($array['body']);
                        $array['body'][$c]['title'] = $v;
                        $array['body'][$c]['url']   = preg_match($pattern,$_POST['urlremote'][$k]) ? $_POST['urlremote'][$k] : '';
                        $data['pic_type']           = 2;
                    }
                }
            }
        }else{
            showMessage(Language::get('param_error'));
        }

        if ($_POST['rec_type'] != 1){
            $array['width']             = is_numeric($_POST['rwidth']) ? $_POST['rwidth'] : '';
            $array['height']            = is_numeric($_POST['rheight']) ? $_POST['rheight'] : '';
        }

        $array['target']    = intval($_POST['rtarget']);
        $data['title']      = $_POST['rtitle'];
        $data['content']    = serialize($array);
        $model = Model('rec_position');

        //如果是把本地上传类型改为文字或远程，则先取出原来上传的图片路径，待update成功后，再删除这些图片
        if ($_POST['opic_type'] == 1 && ($_POST['pic_type'] == 2 || $_POST['rec_type'] == 1)){
            $oinfo = $model->where(array('rec_id'=>$_POST['rec_id']))->find();
            $oinfo = unserialize($oinfo['content']);
        }
        $result = $model->where(array('rec_id'=>$_POST['rec_id']))->update($data);
        if ($result){
            if ($oinfo){
                foreach ($oinfo['body'] as $v){
                    if (is_file(BASE_UPLOAD_PATH.'/'.$v['title'])){
                        @unlink(BASE_UPLOAD_PATH.'/'.$v['title']);
                    }
                }
            }

            dkcache("rec_position/{$_POST['rec_id']}");
            showMessage(Language::get('nc_common_save_succ'),'index.php?act=rec_position&op=rec_list');
        }else{
            showMessage(Language::get('nc_common_save_fail'),'index.php?act=rec_position&op=rec_list');
        }
    }

    public function rec_codeOp(){
		Tpl::setDirquna('system');
        Tpl::showpage('rec_position.code','null_layout');
    }

    public function rec_viewOp(){
        @header("Content-type: text/html; charset=".CHARSET);
        echo rec(intval($_GET['rec_id']));
    }
}
