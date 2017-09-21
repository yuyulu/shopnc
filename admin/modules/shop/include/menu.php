<?php
/**
 * 菜单
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */
defined('In33hao') or exit('Access Invalid!');

$_menu['shop'] = array(
        'name' => '商城',
        'child' => array(
                array(
                        'name' => '设置',
                        'child' => array(
                                'setting' => '商城设置',
                                'upload' => '图片设置',
                                'search' => '搜索设置',
                                'seo' => $lang['nc_seo_set'],
                                'message' => $lang['nc_message_set'],
                                'payment' => $lang['nc_pay_method'],
                                'express' => $lang['nc_admin_express_set'],
                                'waybill' => '运单模板',
                                'web_config' => '首页管理',
                                'web_channel' => '频道管理'
                        )),
                array(
                        'name' => $lang['nc_goods'],
                        'child'=>array(
                                'goods' => $lang['nc_goods_manage'],
                                'goods_class' => $lang['nc_class_manage'],
                                'brand' => $lang['nc_brand_manage'],
                                'type' => $lang['nc_type_manage'],
                                'spec' => $lang['nc_spec_manage'],
                                'goods_album' => $lang['nc_album_manage'],
                                'goods_recommend' => '商品推荐'
                        )),
                array(
                        'name' => $lang['nc_store'],
                        'child' => array(
                                'store' => $lang['nc_store_manage'],
                                'store_grade' => $lang['nc_store_grade'],
                                'store_class' => $lang['nc_store_class'],
                                'domain' => $lang['nc_domain_manage'],
                                'sns_strace' => $lang['nc_s_snstrace'],
                                'help_store' => '店铺帮助',
                                'store_joinin' => '商家入驻',
                                'ownshop' => '自营店铺'
                        )),
                array(
                        'name' => $lang['nc_member'],
                        'child' => array(
                                'member' => $lang['nc_member_manage'],
                                'member_exp' => '等级经验值',
                                'points' => $lang['nc_member_pointsmanage'],
                                'sns_sharesetting' => $lang['nc_binding_manage'],
                                'sns_malbum' => $lang['nc_member_album_manage'],
                                'snstrace' => $lang['nc_snstrace'],
                                'sns_member' => $lang['nc_member_tag'],
                                'predeposit' => $lang['nc_member_predepositmanage'],
                                'chat_log' => '聊天记录'
                        )),
                array(
                        'name' => $lang['nc_trade'],
                        'child' => array(
                                'order' => $lang['nc_order_manage'],
                                'vr_order' => '虚拟订单',
                                'refund' => '退款管理',
                                'return' => '退货管理',
                                'vr_refund' => '虚拟订单退款',
                                'consulting' => $lang['nc_consult_manage'],
                                'inform' => $lang['nc_inform_config'],
                                'evaluate' => $lang['nc_goods_evaluate'],
                                'complain' => $lang['nc_complain_config']
                        )),
                array(
                        'name' => $lang['nc_operation'],
                        'child' => array(
                                'operating' => '运营设置',
                                'bill' => $lang['nc_bill_manage'],
                                'vr_bill' => '虚拟订单结算',
                                'mall_consult' => '平台客服',
                                'rechargecard' => '平台充值卡',
                                'delivery' => '物流自提服务站',
                                'contract' => '消费者保障服务'
                        )),
                array(
                        'name' => '促销',
                        'child' => array(
                                'operation' => '促销设定',
                                'groupbuy' => $lang['nc_groupbuy_manage'],
                                'vr_groupbuy' => '虚拟抢购设置',
                                'promotion_cou' => '加价购',
                                'promotion_xianshi' => $lang['nc_promotion_xianshi'],
                                'promotion_mansong' => $lang['nc_promotion_mansong'],
                                'promotion_bundling' => $lang['nc_promotion_bundling'],
                                'promotion_booth' => '推荐展位',
                                'promotion_book' => '预售商品',
                                'promotion_fcode' => 'Ｆ码商品',
                                'promotion_combo' => '推荐组合',
                                'promotion_sole' => '手机专享',
                                'pointprod'=>$lang['nc_pointprod'],
                                'voucher' => $lang['nc_voucher_price_manage'],
                                'redpacket' => '平台红包',
                                'activity' => $lang['nc_activity_manage']
                        )),
                array(
                        'name' => $lang['nc_stat'],
                        'child' => array(
                                'stat_general' => $lang['nc_statgeneral'],
                                'stat_industry' => $lang['nc_statindustry'],
                                'stat_member' => $lang['nc_statmember'],
                                'stat_store' => $lang['nc_statstore'],
                                'stat_trade' => $lang['nc_stattrade'],
                                'stat_goods' => $lang['nc_statgoods'],
                                'stat_marketing' => $lang['nc_statmarketing'],
                                'stat_aftersale' => $lang['nc_stataftersale']
                        ))
));
