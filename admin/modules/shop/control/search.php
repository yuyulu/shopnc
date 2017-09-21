<?php
/**
 * 搜索设置
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');

class searchControl extends SystemControl{

    private $_links = array(
        array('url'=>'act=search&op=index','text'=>'默认搜索'),
        array('url'=>'act=search&op=hot','text'=>'热门搜索')
    );

    public function __construct(){
        parent::__construct();
    }

    /**
     * 默认搜索
     */
    public function indexOp() {
        if (chksubmit()){
            $model_setting = Model('setting');
            $comma = '，';
            if (strtoupper(CHARSET) == 'GBK'){
                $comma = Language::getGBK($comma);
            }
            $result = $model_setting->updateSetting(array(
                    'hot_search'=>str_replace($comma,',',$_POST['hot_search'])));
            if ($result){
                showMessage('保存成功');
            }else {
                showMessage('保存失败');
            }
        }
        $model_setting = Model('setting');
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);

        Tpl::output('top_link',$this->sublink($this->_links,'index'));
		Tpl::setDirquna('shop');

        Tpl::showpage('search.index');
    }

    /**
     * 热门搜索词列表
     */
    public function hotOp() {
        $model_setting = Model('setting');
        $search_info = $model_setting->getRowSetting('rec_search');
        if ($search_info !== false) {
            $search_list = @unserialize($search_info['value']);
        }
        if (!$search_list && !is_array($search_list)) {
            $search_list = array();
        }
        Tpl::output('search_list',$search_list);
        Tpl::output('top_link',$this->sublink($this->_links,'hot'));
		Tpl::setDirquna('shop');
        Tpl::showpage('search.hot');
    }

    /**
     * 热搜词添加
     */
    public function hot_addOp() {
        $model_setting = Model('setting');
        $search_info = $model_setting->getRowSetting('rec_search');
        if ($search_info !== false) {
            $search_list = @unserialize($search_info['value']);
        }
        if (!$search_list && !is_array($search_list)) {
            $search_list = array();
        }
        if (chksubmit()) {
            if (count($search_list) >= 10) {
                showMessage('最多可设置10个热搜词','index.php?act=search&op=hot');
            }
            if ($_POST['s_name'] != '' && $_POST['s_value'] != '') {
                $data = array('name'=>stripslashes($_POST['s_name']),'value'=>stripslashes($_POST['s_value']));
                array_unshift($search_list, $data);
            }
            $result = $model_setting->updateSetting(array('rec_search'=>serialize($search_list)));
            if ($result){
                showMessage('保存成功','index.php?act=search&op=hot');
            }else {
                showMessage('保存失败');
            }
        }
		Tpl::setDirquna('shop');

        Tpl::showpage('search.hot_add');
    }

    /**
     * 删除
     */
    public function hot_delOp() {
        $model_setting = Model('setting');
        $search_info = $model_setting->getRowSetting('rec_search');
        if ($search_info !== false) {
            $search_list = @unserialize($search_info['value']);
        }
        if (!empty($search_list) && is_array($search_list) && intval($_GET['id']) >= 0) {
            unset($search_list[intval($_GET['id'])]);
        }
        if (!is_array($search_list)) {
            $search_list = array();
        }
        $result = $model_setting->updateSetting(array('rec_search'=>serialize(array_values($search_list))));
        if ($result){
            showMessage('删除成功');
        }
        showMessage('删除失败');
    }

    /**
     * 编辑
     */
    public function hot_editOp() {
        $model_setting = Model('setting');
        $search_info = $model_setting->getRowSetting('rec_search');
        if ($search_info !== false) {
            $search_list = @unserialize($search_info['value']);
        }
        if (!is_array($search_list)) {
            $search_list = array();
        }
        if (!chksubmit()) {
            if (!empty($search_list) && is_array($search_list) && intval($_GET['id']) >= 0) {
                $current_info = $search_list[intval($_GET['id'])];
            }
            Tpl::output('current_info',is_array($current_info) ? $current_info : array());
			Tpl::setDirquna('shop');
            Tpl::showpage('search.hot_add');
        } else {
            if ($_POST['s_name'] != '' && $_POST['s_value'] != '' && $_POST['id'] != '' && intval($_POST['id']) >= 0) {
                $search_list[intval($_POST['id'])] = array('name'=>stripslashes($_POST['s_name']),'value'=>stripslashes($_POST['s_value']));
            }
            $result = $model_setting->updateSetting(array('rec_search'=>serialize($search_list)));
            if ($result){
                showMessage('编辑成功','index.php?act=search&op=hot');
            }
            showMessage('编辑失败');
        }


    }
}