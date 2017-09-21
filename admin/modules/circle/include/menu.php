<?php
/**
 * 菜单
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */
defined('In33hao') or exit('Access Invalid!');
$_menu['circle'] = array (
        'name' => $lang['nc_circle'],
        'child' => array (
                array (
                        'name' => $lang['nc_config'],
                        'child' => array(
                                'circle_setting' => $lang['nc_circle_setting'],
                                'circle_adv' => '首页幻灯'
                        )
                ),
                array (
                        'name' => '成员',
                        'child' => array(
                                'circle_member' => $lang['nc_circle_membermanage'],
                                'circle_memberlevel' => '成员头衔'
                        )
                ),
                array (
                        'name' => '圈子',
                        'child' => array(
                                'circle_manage' => $lang['nc_circle_manage'],
                                'circle_class' => $lang['nc_circle_classmanage'],
                                'circle_theme' => $lang['nc_circle_thememanage'],
                                'circle_inform' => '举报管理'
                        )
                )
        ) 
);