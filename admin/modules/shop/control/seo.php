<?php
/**
 * 网站设置
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class seoControl extends SystemControl{

    public function __construct(){
        parent::__construct();
        Language::read('setting');
    }

    public function indexOp() {
        $this->seoOp();
    }

    /**
     * SEO与rewrite设置
     */
    public function seoOp(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            $update_array['rewrite_enabled'] = $_POST['rewrite_enabled'];
            $result = $model_setting->updateSetting($update_array);
            if ($result === true){
                $this->log(L('nc_edit,nc_seo_set'),1);
                showMessage(L('nc_common_save_succ'));
            }else {
                $this->log(L('nc_edit,nc_seo_set'),0);
                showMessage(L('nc_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();

        //读取SEO信息
        $list = Model('seo')->select();
        $seo = array();
        foreach ((array)$list as $value) {
            $seo[$value['type']] = $value;
        }

        Tpl::output('list_setting',$list_setting);
        Tpl::output('seo',$seo);

        $category = Model('goods_class')->getGoodsClassForCacheModel();
        Tpl::output('category',$category);
		Tpl::setDirquna('shop');

        Tpl::showpage('seo.setting');
    }

    public function ajax_categoryOp(){
        $model = Model('goods_class');
        $list = $model->field('gc_title,gc_keywords,gc_description')->where(array('gc_id' => intval($_GET['id'])))->find();
        //转码
        if (strtoupper(CHARSET) == 'GBK'){
            $list = Language::getUTF8($list);//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
        }
        echo json_encode($list);exit();
    }

    /**
     * SEO设置保存
     */
    public function seo_updateOp(){
        $model_seo = Model('seo');
        if (chksubmit()){
            $update = array();
            if (is_array($_POST['SEO'][0])){
                $seo = $_POST['SEO'][0];
            }else{
                $seo = $_POST['SEO'];
            }
            foreach ((array)$seo as $key=>$value) {
                $model_seo->where(array('type'=>$key))->update($value);
            }
            dkcache('seo');
            showMessage(L('nc_common_save_succ'));
        }else{
            showMessage(L('nc_common_save_fail'));
        }
    }

    /**
     * 分类SEO保存
     *
     */
    public function seo_categoryOp(){
        if (chksubmit()){
            $where = array('gc_id' => intval($_POST['category']));
            $input = array();
            $input['gc_title'] = $_POST['cate_title'];
            $input['gc_keywords'] = $_POST['cate_keywords'];
            $input['gc_description'] = $_POST['cate_description'];
            if (Model('goods_class')->editGoodsClass($input, $where)){
                dkcache('goods_class_seo');
                showMessage(L('nc_common_save_succ'));
            }
        }
        showMessage(L('nc_common_save_fail'));
    }
}
