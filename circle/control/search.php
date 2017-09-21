<?php
/**
 * 圈子首页
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class searchControl extends BaseCircleControl{
    public function __construct(){
        parent::__construct();
        Language::read('circle');
        $this->themeTop();
    }
    /**
     * 话题搜索
     */
    public function themeOp(){
        $model = Model();
        $where = array();
        if($_GET['keyword'] != ''){
            $where['theme_name'] = array('like', '%'.$_GET['keyword'].'%');
        }
        $count = $model->table('circle_theme')->where($where)->count();
        $theme_list = $model->table('circle_theme')->where($where)->page(10,$count)->order('theme_addtime desc')->select();
        Tpl::output('count', $count);
        Tpl::output('show_page', $model->showpage('2'));
        Tpl::output('theme_list', $theme_list);
        Tpl::output('search_sign', 'theme');

        $this->circleSEO(L('search_theme'));
        Tpl::showpage('search.theme');
    }
    /**
     * 圈子搜索
     */
    public function groupOp(){
        $model = Model();
        $where = array();
        $where['circle_status'] = 1;
        if($_GET['keyword'] != ''){
            $where['circle_name|circle_tag'] = array('like', '%'.$_GET['keyword'].'%');
        }
        if(intval($_GET['class_id']) > 0){
            $where['class_id'] = intval($_GET['class_id']);
        }
        $count = $model->table('circle')->where($where)->count();
        $circle_list = $model->table('circle')->where($where)->page(10,$count)->select();
        Tpl::output('count', $count);
        Tpl::output('circle_list', $circle_list);
        Tpl::output('show_page', $model->showpage('2'));
        Tpl::output('search_sign', 'group');

        $this->circleSEO(L('search_circle'));
        Tpl::showpage('search.group');
    }
}
