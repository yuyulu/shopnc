<?php
/**
 * 显示图片
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class show_picsControl extends BaseMemberControl {

    public function indexOp(){

        $type = trim($_GET['type']);
        if(empty($_GET['pics'])) {
            $this->goto_index();
        }
        $pics = explode('|',trim($_GET['pics']));
        $pic_path = '';
        switch ($type) {
            case 'inform':
                $pic_path = UPLOAD_SITE_URL.DS.'shop/inform/';
                break;
            case 'complain':
                $pic_path = UPLOAD_SITE_URL.DS.'shop/complain/';
                break;
            default:
                $this->goto_index();
                break;
        }

        Tpl::output('pic_path',$pic_path);
        Tpl::output('pics',$pics);
        //输出页面
        Tpl::showpage('show_pics','null_layout');
    }

    private function goto_index() {
        @header("Location: ".urlShop('member', 'home'));
        exit;
    }
}
