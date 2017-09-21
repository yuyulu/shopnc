<?php
/**
 * 默认展示页面
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */


defined('In33hao') or exit('Access Invalid!');
class linkControl extends BaseHomeControl{
    public function indexOp(){

         //友情链接
                $model_link = Model('link');
                $link_list = $model_link->getLinkList($condition,$page);
                /**
                 * 整理图片链接
                 */
                if (is_array($link_list)){
                        foreach ($link_list as $k => $v){
                                if (!empty($v['link_pic'])){
                                        $link_list[$k]['link_pic'] = UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/common/'.DS.$v['link_pic'];
                                }
                        }
                }
                Tpl::output('$link_list',$link_list);
        Model('seo')->type('index')->show();
        Tpl::showpage('link');
    }
   
}
