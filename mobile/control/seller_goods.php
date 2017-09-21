<?php
/**
 * 商家注销
 *
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class seller_goodsControl extends mobileSellerControl {

    public function __construct(){
        parent::__construct();
    }

    public function goods_addOp() {
        $logic_goods = Logic('goods');

        unset($_POST['key']);
        $result = $logic_goods->saveGoods(
            $_POST,
            $this->seller_info['store_id'],
            $this->store_info['store_name'],
            $this->store_info['store_state'],
            $this->seller_info['seller_id'],
            $this->seller_info['seller_name'],
            $this->store_info['bind_all_gc']
        );

        if(!$result['state']) {
            output_error($result['msg']);
        }

        output_data(array('common_id' => $result['data']));
    }

    /**
     * 出售中的商品列表
     */
    public function goods_listOp() {
        $keyword = $_POST['keyword'];
        $goods_type = $_POST['goods_type'];
        $search_type = $_POST['search_type'];

        $model_goods = Model('goods');

        $condition = array();
        $condition['store_id'] = $this->store_info['store_id'];
        if (trim($keyword) != '') {
            switch ($search_type) {
                case 0:
                    $condition['goods_name'] = array('like', '%' . trim($keyword) . '%');
                    break;
                case 1:
                    $condition['goods_serial'] = array('like', '%' . trim($keyword) . '%');
                    break;
                case 2:
                    $condition['goods_commonid'] = intval($keyword);
                    break;
            }
        }

        $fields = 'goods_commonid,goods_name,goods_price,goods_addtime,goods_image,goods_state,goods_lock';
        switch ($goods_type) {
            case 'lockup':
                $goods_list = $model_goods->getGoodsCommonLockUpList($condition, $fields, $this->page);
                break;
            case 'offline':
                $goods_list = $model_goods->getGoodsCommonOfflineList($condition, $fields, $this->page);
                break;
            default:
                $goods_list = $model_goods->getGoodsCommonOnlineList($condition, $fields, $this->page);
                break;
        }

        // 计算库存
        $storage_array = $model_goods->calculateStorage($goods_list);

        // 整理输出的数据格式
        foreach ($goods_list as $key => $value) {
            $goods_list[$key]['goods_storage_sum'] = $storage_array[$value['goods_commonid']]['sum'];
            $goods_list[$key]['goods_addtime'] = date('Y-m-d', $goods_list[$key]['goods_addtime']);
            $goods_list[$key]['goods_image'] = cthumb($goods_list[$key]['goods_image']);
        }

        $page_count = $model_goods->gettotalpage();

        output_data(array('goods_list' => $goods_list), mobile_page($page_count));
    }

    /**
     * 商品详细信息
     */
     public function goods_infoOp() {
        $common_id = $_POST['goods_commonid'];
        $model_goods = Model('goods');
        $goodscommon_info = $model_goods->getGoodsCommonInfoByID($common_id);
        if (empty($goodscommon_info) || $goodscommon_info['store_id'] != $this->store_info['store_id'] || $goodscommon_info['goods_lock'] == 1) {
            output_error('参数错误');
        }
        $where = array('goods_commonid' => $common_id, 'store_id' => $this->store_info['store_id']);
        $goodscommon_info['g_storage'] = $model_goods->getGoodsSum($where, 'goods_storage');
        $goodscommon_info['spec_name'] = unserialize($goodscommon_info['spec_name']);
        $goodscommon_info['goods_image_url'] = thumb($goodscommon_info);

        $where = array('goods_commonid' => $common_id, 'store_id' => $this->store_info['store_id']);

        // 取得商品规格的输入值
        $goods_array = $model_goods->getGoodsList($where, 'goods_id,goods_marketprice,goods_price,goods_storage,goods_serial,goods_storage_alarm,goods_spec,goods_barcode');
        $sp_value = array();
        $attr_checked = array();
        $spec_checked = array();
        if (is_array($goods_array) && !empty($goods_array)) {
            $model_type = Model('type');
            // 取得已选择了哪些商品的属性
            $attr_checked_l = $model_type->typeRelatedList(
                'goods_attr_index',
                array('goods_id' => intval($goods_array[0]['goods_id'])),
                'attr_id,attr_value_id'
            );
            if (is_array($attr_checked_l) && !empty($attr_checked_l)) {
                foreach($attr_checked_l as $val) {
                    $array = array();
                    $array['attr_id'] = $val['attr_id'];
                    $array['attr_value_id'] = $val['attr_value_id'];
                    $attr_checked[] = $array;
                }
            }

            $spec_tmp = Array();
            foreach ( $goods_array as $k => $v ) {
                $a = unserialize($v['goods_spec']);
                if (!empty($a)) {
                    // $spec_checked = Array();
                    foreach ($a as $key => $val){
                        /*
                        $spec_checked[$key]['id'] = $key;
                        $spec_checked[$key]['name'] = $val;
                        */
                        if(!array_key_exists($key,$spec_tmp)) {
                            $spec_checked[] = Array('id' => $key, 'name' => $val);
                            $spec_tmp[$key] = 1;
                        }
                    }
                    $matchs = array_keys($a);
                    sort($matchs);
                    /*
                    $id = str_replace ( ',', '', implode ( ',', $matchs ) );
                    $sp_value ['i_' . $id . '|marketprice'] = $v['goods_marketprice'];
                    $sp_value ['i_' . $id . '|price'] = $v['goods_price'];
                    $sp_value ['i_' . $id . '|id'] = $v['goods_id'];
                    $sp_value ['i_' . $id . '|stock'] = $v['goods_storage'];
                    $sp_value ['i_' . $id . '|alarm'] = $v['goods_storage_alarm'];
                    $sp_value ['i_' . $id . '|sku'] = $v['goods_serial'];
                    $sp_value ['i_' . $id . '|barcode'] = $v['goods_barcode'];
                    */
                    $spec = Array();
                    $spec['spec_ids'] = implode ( ',', $matchs ) ;
                    $spec['marketprice'] = $v['goods_marketprice'];
                    $spec['price'] = $v['goods_price'];
                    $spec['id'] = $v['goods_id'];
                    $spec['stock'] = $v['goods_storage'];
                    $spec['alarm'] = $v['goods_storage_alarm'];
                    $spec['sku'] = $v['goods_serial'];
                    $spec['barcode'] = $v['goods_barcode'];
                    $sp_value[]   =  $spec ;
                }
            }
        }

        $goods_class = Model('goods_class')->getGoodsClassLineForTag($goodscommon_info['gc_id']);

        $model_type = Model('type');
        // 获取类型相关数据
        $typeinfo = $model_type->getAttr($goods_class['type_id'], $this->store_info['store_id'], $goodscommon_info['gc_id']);
        list($spec_json, $spec_list, $attr_list, $brand_list) = $typeinfo;

        // 自定义属性
        $custom_list = Model('type_custom')->getTypeCustomList(array('type_id' => $goods_class['type_id']));
        $custom_list = array_under_reset($custom_list, 'custom_id');


        output_data(
            array(
                'goodscommon_info' => $goodscommon_info,
                'sp_value' => $sp_value,
                'attr_checked' => $attr_checked,
                'spec_checked' => $spec_checked,
                'spec_json' => $spec_json,
                'spec_list' => $spec_list,
                'attr_list' => $attr_list
            )
        );
    }

    /**
     * 商品详细信息
     */
    public function goods_image_infoOp() {
        $common_id = $_POST['goods_commonid'];
        $model_goods = Model('goods');

        $common_list = $model_goods->getGoodsCommonInfoByID($common_id, 'store_id,goods_lock,spec_value,is_virtual,is_fcode,is_presell');
        if ($common_list['store_id'] != $this->store_info['store_id'] || $common_list['goods_lock'] == 1) {
            output_error('参数错误');
        }
        
        $spec_value = unserialize($common_list['spec_value']);
        // 商品图片
        $image_list = $model_goods->getGoodsImageList(array('goods_commonid' => $common_id));
        $image_array = array();
        if (!empty($image_list)) {
            foreach ($image_list as $val) {
                $val['goods_image_url'] = cthumb($val['goods_image'], 240);
                $image_array[$val['color_id']]['color_id'] = $val['color_id'];
                $image_array[$val['color_id']]['spec_name'] = $spec_value['1'][$val['color_id']];
                $image_array[$val['color_id']]['images'][] = $val;
            }
        }

        output_data(
            array(
                'image_list' => array_values($image_array)
            ));
    }
    /**
     * 商品编辑保存
     */
    public function goods_editOp() {
        $logic_goods = Logic('goods');
    
        unset($_POST['key']);
        $result = $logic_goods->updateGoods(
            $_POST,
            $this->seller_info['store_id'],
            $this->store_info['store_name'],
            $this->store_info['store_state'],
            $this->seller_info['seller_id'],
            $this->seller_info['seller_name'],
            $this->store_info['bind_all_gc']
        );
    
        if(!$result['state']) {
            output_error($result['msg']);
        }
    
        output_data(array('common_id' => $result['data']));
    }
    
    /**
     * 商品图片保存
     */
    public function goods_edit_image() {
        $common_id = intval($_POST['goods_commonid']);
        $rs = Logic('goods')->editSaveImage($_POST['img'], $common_id, $this->store_info['store_id'], $this->seller_info['seller_id'],  $this->seller_info['seller_name']);
        if(!$rs['state']) {
            output_error($rs['msg']);
        }
        output_data('1');
    }

    /**
     * 商品上架
     */
    public function goods_showOp() {
        if ($this->store_info['store_state'] != 1) {
            output_error('店铺正在审核中或已经关闭，不能上架商品');
        }
        $result = Logic('goods')->goodsShow($_POST['commonids'], $this->store_info['store_id'], $this->seller_info['seller_id'], $this->seller_info['seller_name']);
        if(!$result['state']) {
            output_error($result['msg']);
        }
        output_data('1');
    }
    
    /**
     * 商品下架
     */
    public function goods_unshowOp() {
        $result = Logic('goods')->goodsUnShow($_POST['commonids'], $this->store_info['store_id'], $this->seller_info['seller_id'], $this->seller_info['seller_name']);
        if(!$result['state']) {
            output_error($result['msg']);
        }
        output_data('1');
    }
    
    /**
     * 商品删除
     */
    public function goods_dropOp() {
        $result = Logic('goods')->goodsDrop($_POST['commonids'], $this->store_info['store_id'], $this->seller_info['seller_id'], $this->seller_info['seller_name']);
        if (!$result['state']) {
            output_error($result['msg']);
        }
        output_data('1');
    }


}
