<?php
/**
 * 前台品牌分类
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377 
 */



defined('In33hao') or exit('Access Invalid!');
class brandControl extends BaseHomeControl {
    public function indexOp(){
        //读取语言包
        Language::read('home_brand_index');
        //分类导航
        $nav_link = array(
            0=>array(
                'title'=>Language::get('homepage'),
                'link'=>SHOP_SITE_URL
            ),
            1=>array(
                'title'=>Language::get('brand_index_all_brand')
            )
        );
        //Tpl::output('nav_link_list',$nav_link);

        //获得品牌列表
        $model = Model();
        $brand_c_list = $model->table('brand')->where(array('brand_apply'=>'1'))->order('brand_sort asc')->select();
        $brands = $this->_tidyBrand($brand_c_list);
        extract($brands);
        Tpl::output('brand_c',$brand_listnew);
        Tpl::output('brand_class',$brand_class);
        Tpl::output('brand_r',$brand_r_list);
        Tpl::output('html_title',Language::get('brand_index_brand_list'));

        //页面输出
        Tpl::output('index_sign','brand');
        Model('seo')->type('brand')->show();
        Tpl::showpage('brand');
    }

    /**
     * 整理品牌
     * 所有品牌全部显示在一级类目下，不显示二三级类目
     * @param array $brand_c_list
     * @return array
     */
    private function _tidyBrand($brand_c_list) {
        $brand_listnew = array();
        $brand_class = array();
        $brand_r_list = array();
        if (!empty($brand_c_list) && is_array($brand_c_list)){
            $goods_class = Model('goods_class')->getGoodsClassForCacheModel();
            foreach ($brand_c_list as $key=>$brand_c){
                $gc_array = $this->_getTopClass($goods_class, $brand_c['class_id']);
                if (empty($gc_array)) {
                    if ($brand_c['show_type'] == 1){
                        $brand_listnew[0]['text'][] = $brand_c;
                    } else {
                        $brand_listnew[0]['image'][] = $brand_c;
                    }
                    $brand_class[0]['brand_class'] = '其他';
                } else {
                    if ($brand_c['show_type'] == 1){
                        $brand_listnew[$gc_array['gc_id']]['text'][] = $brand_c;
                    } else {
                        $brand_listnew[$gc_array['gc_id']]['image'][] = $brand_c;
                    }
                    $brand_class[$gc_array['gc_id']]['brand_class'] = $gc_array['gc_name'];
                }
                //推荐品牌
                if ($brand_c['brand_recommend'] == 1){
                    $brand_r_list[] = $brand_c;
                }
            }
        }
        krsort($brand_class);
        krsort($brand_listnew);
        return array('brand_listnew' => $brand_listnew, 'brand_class' => $brand_class, 'brand_r_list' => $brand_r_list);
    }

    /**
     * 获取顶级商品分类
     * 递归调用
     * @param array $goods_class
     * @param int $gc_id
     * @return array
     */
    private function _getTopClass($goods_class, $gc_id) {
        if (!isset($goods_class[$gc_id])) {
            return null;
        }
        return $goods_class[$gc_id]['gc_parent_id'] == 0 ? $goods_class[$gc_id] : $this->_getTopClass($goods_class, $goods_class[$gc_id]['gc_parent_id']);
    }

    /**
     * 品牌商品列表
     */
    public function listOp(){
        Language::read('home_brand_index');
        $lang   = Language::getLangContent();
        /**
         * 验证品牌
         */
        $model_brand = Model('brand');
        $brand_info = $model_brand->getBrandInfo(array('brand_id' => intval($_GET['brand'])));
        if(!$brand_info){
            showMessage($lang['wrong_argument'],'index.php','html','error');
        }
		Tpl::output('brand_int',$brand_info);
        
        //查询消费者保障服务
        $contract_item = array();
        if (C('contract_allow') == 1) {
            $contract_item = Model('contract')->getContractItemByCache();
        }
        Tpl::output('contract_item',$contract_item);


        /**
         * 获得推荐品牌
         */
        $brand_r_list = Model('brand')->getBrandPassedList(array('brand_recommend'=>1) ,'brand_id,brand_name,brand_pic', 0, 'brand_sort asc, brand_id desc', 10);
        Tpl::output('brand_r',$brand_r_list);

        // 得到排序方式
        $order = 'is_own_shop desc,goods_id desc';
        if (!empty($_GET['key'])) {
            $order_tmp = trim($_GET['key']);
            $sequence = $_GET['order'] == 1 ? 'asc' : 'desc';
            switch ($order_tmp) {
                case '1' : // 销量
                    $order = 'goods_salenum' . ' ' . $sequence;
                    break;
                case '2' : // 浏览量
                    $order = 'goods_click' . ' ' . $sequence;
                    break;
                case '3' : // 价格
                    $order = 'goods_promotion_price' . ' ' . $sequence;
                    break;
            }
        }

        // 字段
        $fields = "goods_id,goods_commonid,goods_name,goods_jingle,gc_id,store_id,store_name,goods_price,goods_promotion_price,goods_promotion_type,goods_marketprice,goods_storage,goods_image,goods_freight,goods_salenum,brand_id,color_id,gc_id_3,gc_id_1,gc_id_2,goods_verify,goods_state,is_own_shop,evaluation_good_star,evaluation_count,is_virtual,is_fcode,is_presell,is_book,book_down_time,have_gift,areaid_1";
        //构造消费者保障服务字段
        if ($contract_item) {
            foreach ($contract_item as $citem_key=>$citem_val) {
                $fields .= ",contract_{$citem_key}";
            }
        }
            // 条件
        $where = array();
        $where['brand_id'] = $brand_info['brand_id'];
        if (intval($_GET['area_id']) > 0) {
            $where['areaid_1'] = intval($_GET['area_id']);
        }
        if ($_GET['type'] == 1) {
            $where['is_own_shop'] = 1;
        }
        if ($_GET['gift'] == 1) {
            $where['have_gift'] = 1;
        }
        //搜索消费者保障服务
        $search_ci_arr = array();
        $search_ci_str = '';
        if ($_GET['ci'] && $_GET['ci'] != 0) {
            //处理参数
            $search_ci= $_GET['ci'];
            $search_ci_arr = explode('_',$search_ci);
            $search_ci_str = $search_ci.'_';
        }
        
        //消费者保障服务
        if ($contract_item && $search_ci_arr) {
            foreach ($search_ci_arr as $ci_val) {
                $where["contract_{$ci_val}"] = 1;
            }
        }
        $model_goods = Model('goods');
        $goods_list = $model_goods->getGoodsListByColorDistinct($where, $fields, $order, 24);
        
        Tpl::output('search_ci_str', $search_ci_str);
        Tpl::output('search_ci_arr', $search_ci_arr);
        Tpl::output('show_page1', $model_goods->showpage(4));
        Tpl::output('show_page', $model_goods->showpage(5));
        // 商品多图
        if (!empty($goods_list)) {
            $commonid_array = array(); // 商品公共id数组
                $storeid_array = array();       // 店铺id数组
            foreach ($goods_list as $value) {
                $commonid_array[] = $value['goods_commonid'];
                $storeid_array[] = $value['store_id'];
            }
            $commonid_array = array_unique($commonid_array);
            $storeid_array = array_unique($storeid_array);
            // 商品多图
            $goodsimage_more = $model_goods->getGoodsImageList(array('goods_commonid' => array('in', $commonid_array)));
            // 店铺
            $store_list = Model('store')->getStoreMemberIDList($storeid_array);

            foreach ($goods_list as $key => $value) {
                // 商品多图
                foreach ($goodsimage_more as $v) {
                    if ($value['goods_commonid'] == $v['goods_commonid'] && $value['store_id'] == $v['store_id'] && $value['color_id'] == $v['color_id']) {
                        $goods_list[$key]['image'][] = $v['goods_image'];
                    }
                }
                // 店铺的开店会员编号
                $store_id = $value['store_id'];
                $goods_list[$key]['member_id'] = $store_list[$store_id]['member_id'];
                $goods_list[$key]['store_domain'] = $store_list[$store_id]['store_domain'];
                //将关键字置红
                $goods_list[$key]['goods_name_highlight'] = $value['goods_name'];

                // 验证预定商品是否到期
                if ($value['is_book'] == 1) {
                    if ( $value['book_down_time'] < TIMESTAMP ) {
                        QueueClient::push('updateGoodsPromotionPriceByGoodsId', $value['goods_id']);
                        $goods_list[$key]['is_book'] = 0;
                    }
                }
            }
            //处理商品消费者保障服务信息
            $goods_list = $model_goods->getGoodsContract($goods_list, $contract_item);
			$goods_num= $model_goods->getGoodsCommonCount($where);
        }
		Tpl::output('goods_num',  $goods_num);
		
        Tpl::output('goods_list', $goods_list);

        // 地区
        $province_array = Model('area')->getTopLevelAreas();
        Tpl::output('province_array', $province_array);

        loadfunc('search');
        /**
         * 取浏览过产品的cookie(最大四组)
         */
        $viewed_goods = Model('goods_browse')->getViewedGoodsList($_SESSION['member_id'],20);
        Tpl::output('viewed_goods',$viewed_goods);

        /**
         * 分类导航
         */
        $nav_link = array(
            0=>array(
                'title'=>$lang['homepage'],
                'link'=>SHOP_SITE_URL
            ),
            1=>array(
                'title'=>$lang['brand_index_all_brand'],
                'link'=>urlShop('brand', 'index')
            ),
            2=>array(
                'title'=>$brand_info['brand_name']
            )
        );
        //Tpl::output('nav_link_list',$nav_link);
        /**
         * 页面输出
         */
        Tpl::output('index_sign','brand');


        Model('seo')->type('brand_list')->param(array('name'=>$brand_info['brand_name']))->show();
        Tpl::showpage('brand_goods');
    }
}