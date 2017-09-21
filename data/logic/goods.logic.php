<?php
/**
 * 商品
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */
defined('In33hao') or exit('Access Invalid!');

class goodsLogic {

    public function __construct() {
        Language::read('member_store_goods_index');
    }

    public function saveGoods($param, $store_id, $store_name, $store_state, $seller_id, $seller_name, $bind_all_gc) {
        $model_goods = Model('goods');
        $count = $model_goods->getGoodsCommonCount(array());
        if ($count >= C('sg_goods_limit') && C('sg_goods_limit') != 0) {
            return callback(false, '发布商品数量已超过最大总商品数量，请不要继续添加');
        }
        
        // 验证参数
        $error = $this->_validParam($param);
        if ($error != '') {
            return callback(false, $error);
        }

        $gc_id = intval($param['cate_id']);
        // 验证商品分类是否存在且商品分类是否为最后一级
        $data = Model('goods_class')->getGoodsClassForCacheModel();
        if (!isset($data[$gc_id]) || isset($data[$gc_id]['child']) || isset($data[$gc_id]['childchild'])) {
            return callback(false, '您选择的分类不存在，或没有选择到最后一级，请重新选择分类。');
        }
        
        // 三方店铺验证是否绑定了该分类
        if (!checkPlatformStoreBindingAllGoodsClass($store_id, $bind_all_gc)) {
            $where = array();
            $where['class_1|class_2|class_3'] = $gc_id;
            $where['store_id'] = $store_id;
            $rs = Model('store_bind_class')->getStoreBindClassInfo($where);
            if (empty($rs)) {
                return callback(false, '您的店铺没有绑定该分类，请重新选择分类。');
            }
        }

        // 根据参数初始化通用商品数据
        $common_array = $this->_initCommonGoodsByParam($param, $store_id, $store_name, $store_state);
        // 生成通用商品返回通用商品编号
        $common_id = $model_goods->addGoodsCommon($common_array);
        if (!$common_id) {
            return callback(false, '商品添加失败');
        }

        // 商品多图保存
        if(!empty($param['image_all'])) {
            $this->_imageAll($common_id, $store_id, $param['image_all'], $common_array['goods_image']);
        }

        // 生成商品返回商品ID(SKU)数组
        $goodsid_array = $this->_addGoods($param, $common_id, $common_array);

        // 生成商品二维码
        if (!empty($goodsid_array)) {
            QueueClient::push('createGoodsQRCode', array('store_id' => $store_id, 'goodsid_array' => $goodsid_array));
        }

        // 商品加入上架队列
        if (isset($param['starttime'])) {
            $selltime = strtotime($param['starttime']) + intval($param['starttime_H'])*3600 + intval($param['starttime_i'])*60;
            if ($selltime > TIMESTAMP) {
                Model('cron')->addCron(array('exetime' => $selltime, 'exeid' => $common_id, 'type' => 1), true);
            }
        }

        //商品加入消费者保障服务更新队列
        Model('cron')->addCron(array('exetime' => TIMESTAMP, 'exeid' => $common_id, 'type' => 9), true);

        // 记录日志
        $this->_recordLog('添加商品，SPU:'.$common_id, $seller_id, $seller_name, $store_id);

        return callback(true, '', $common_id);
    }
    
    public function updateGoods($param, $store_id, $store_name, $store_state, $seller_id, $seller_name, $bind_all_gc) {
        $model_goods = Model('goods');
        $common_id = intval($param['commonid']);
        if ($common_id <= 0) {
            return callback(false, '商品编辑失败');
        }
        // 验证参数
        $error = $this->_validParam($param);
        if ($error != '') {
            return callback(false, $error);
        }

        $gc_id = intval($param['cate_id']);
        // 验证商品分类是否存在且商品分类是否为最后一级
        $data = Model('goods_class')->getGoodsClassForCacheModel();
        if (!isset($data[$gc_id]) || isset($data[$gc_id]['child']) || isset($data[$gc_id]['childchild'])) {
            return callback(false, '您选择的分类不存在，或没有选择到最后一级，请重新选择分类。');
        }
        
        // 三方店铺验证是否绑定了该分类
        if (!checkPlatformStoreBindingAllGoodsClass($store_id, $bind_all_gc)) {
            $where = array();
            $where['class_1|class_2|class_3'] = $gc_id;
            $where['store_id'] = $store_id;
            $rs = Model('store_bind_class')->getStoreBindClassInfo($where);
            if (empty($rs)) {
                return callback(false, '您的店铺没有绑定该分类，请重新选择分类。');
            }
        }

        // 根据参数初始化通用商品数据
        $common_array = $this->_initCommonGoodsByParam($param, $store_id, $store_name, $store_state);
        
        // 接口不标记字段
        if (APP_ID == 'mobile') {
            unset($common_array['brand_id']);
            unset($common_array['brand_name']);
            unset($common_array['mobile_body']);
            unset($common_array['plateid_top']);
            unset($common_array['plateid_bottom']);
            unset($common_array['sup_id']);
        }
        // 更新商品数据
        extract($this->_editGoods($param, $common_id, $common_array, $store_id));
        
        // 清理商品数据
        $model_goods->delGoods(array('goods_id' => array('not in', $goodsid_array), 'goods_commonid' => $common_id, 'store_id' => $store_id));
        // 清理商品图片表
        $model_goods->delGoodsImages(array('goods_commonid' => $common_id, 'color_id' => array('not in', $colorid_array)));
        // 更新商品默认主图
        $default_image_list = $model_goods->getGoodsImageList(array('goods_commonid' => $common_id, 'is_default' => 1), 'color_id ,goods_image');
        if (!empty($default_image_list)) {
            foreach ($default_image_list as $val) {
                $model_goods->editGoods(array('goods_image' => $val['goods_image']), array('goods_commonid' => $common_id, 'color_id' => $val['color_id']));
            }
        }
        
        // 商品加入上架队列
        if (isset($param['starttime'])) {
            $selltime = strtotime($param['starttime']) + intval($param['starttime_H'])*3600 + intval($param['starttime_i'])*60;
            if ($selltime > TIMESTAMP) {
                Model('cron')->addCron(array('exetime' => $selltime, 'exeid' => $common_id, 'type' => 1), true);
            }
        }
        
        if ($common_array['is_virtual'] == 1) {
            // 如果是特殊商品清理促销活动，抢购、限时折扣、组合销售
            QueueClient::push('clearSpecialGoodsPromotion', array('goods_commonid' => $common_id, 'goodsid_array' => $goodsid_array));
        } else {
            // 更新商品促销价格
            QueueClient::push('updateGoodsPromotionPriceByGoodsCommonId', $common_id);
        }
        
        $return = $model_goods->editGoodsCommon($common_array, array('goods_commonid' => $common_id, 'store_id' => $store_id));
        if (!$return) {
            return callback(false, '商品编辑失败');
        }

        // 生成商品二维码
        if (!empty($goodsid_array)) {
            QueueClient::push('createGoodsQRCode', array('store_id' => $store_id, 'goodsid_array' => $goodsid_array));
        }

        // 记录日志
        $this->_recordLog('编辑商品，SPU:'.$common_id, $seller_id, $seller_name, $store_id);
        
        return callback(true, '', $common_id);
    }

    /**
     * 验证参数
     */
    private function _validParam($param) {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array (
                "input" => $param["g_name"],
                "require" => "true",
                "message" => L('store_goods_index_goods_name_null')
            ),
            array (
                "input" => $param["g_price"],
                "require" => "true",
                "validator" => "Double",
                "message" => L('store_goods_index_goods_price_null')
            )
        );

        return $obj_validate->validate();
    }

    /**
     * 根据参数初始化通用商品数据
     */
    private function _initCommonGoodsByParam($param, $store_id, $store_name, $store_state) {
        // 分类信息
        $goods_class = Model('goods_class')->getGoodsClassLineForTag(intval($param['cate_id']));

        $common_array = array();
        $common_array['goods_name']         = $param['g_name'];
        $common_array['goods_jingle']       = $param['g_jingle'];
        $common_array['gc_id']              = intval($param['cate_id']);
        $common_array['gc_id_1']            = intval($goods_class['gc_id_1']);
        $common_array['gc_id_2']            = intval($goods_class['gc_id_2']);
        $common_array['gc_id_3']            = intval($goods_class['gc_id_3']);
        $common_array['gc_name']            = $param['cate_name'];
        $common_array['brand_id']           = $param['b_id'];
        $common_array['brand_name']         = $param['b_name'];
        $common_array['type_id']            = intval($param['type_id']);
        $common_array['goods_image']        = $param['image_path'];
        $common_array['goods_price']        = floatval($param['g_price']);
        $common_array['goods_marketprice']  = floatval($param['g_marketprice']);
        $common_array['goods_costprice']    = floatval($param['g_costprice']);
        $common_array['goods_discount']     = floatval($param['g_discount']);
        $common_array['goods_serial']       = $param['g_serial'];
        $common_array['goods_storage_alarm']= intval($param['g_alarm']);
        $common_array['goods_barcode']      = $param['g_barcode'];
        $common_array['goods_attr']         = serialize($param['attr']);
        $common_array['goods_custom']       = serialize($param['custom']);
        $common_array['goods_body']         = $param['g_body'];
        $common_array['mobile_body']        = $this->_getMobileBody($param['m_body']); 
        $common_array['goods_commend']      = intval($param['g_commend']);
        $common_array['goods_state']        = ($store_state != 1) ? 0 : intval($param['g_state']);            // 店铺关闭时，商品下架
        $common_array['goods_addtime']      = TIMESTAMP;
        $common_array['goods_selltime']     = strtotime($param['starttime']) + intval($param['starttime_H'])*3600 + intval($param['starttime_i'])*60;
        $common_array['goods_verify']       = (C('goods_verify') == 1) ? 10 : 1;
        $common_array['store_id']           = $store_id;
        $common_array['store_name']         = $store_name;
        $common_array['spec_name']          = is_array($param['spec']) ? serialize($param['sp_name']) : serialize(null);
        $common_array['spec_value']         = is_array($param['spec']) ? serialize($param['sp_val']) : serialize(null);
        $common_array['goods_vat']          = intval($param['g_vat']);
        $common_array['areaid_1']           = intval($param['province_id']);
        $common_array['areaid_2']           = intval($param['city_id']);
        $common_array['transport_id']       = ($param['freight'] == '0') ? '0' : intval($param['transport_id']); // 运费模板
        $common_array['transport_title']    = $param['transport_title'];
        $common_array['goods_freight']      = floatval($param['g_freight']);
        $common_array['goods_stcids']       = $this->_getStoreClassArray($param['sgcate_id'], $store_id);
        $common_array['plateid_top']        = intval($param['plate_top']) > 0 ? intval($param['plate_top']) : 0;
        $common_array['plateid_bottom']     = intval($param['plate_bottom']) > 0 ? intval($param['plate_bottom']) : 0;
        $common_array['is_virtual']         = intval($param['is_gv']);
        $common_array['virtual_indate']     = !empty($param['g_vindate']) ? (strtotime($param['g_vindate']) + 24*60*60 -1) : 0;  // 当天的最后一秒结束
        $common_array['virtual_limit']      = intval($param['g_vlimit']) > 10 || intval($param['g_vlimit']) < 0 ? 10 : intval($param['g_vlimit']);
        $common_array['virtual_invalid_refund'] = intval($param['g_vinvalidrefund']);
        $common_array['sup_id']             = $param['sup_id'];
        $common_array['is_own_shop']        = in_array($store_id, Model('store')->getOwnShopIds()) ? 1 : 0;

        return $common_array;
    }
    /**
     * 序列化保存手机端商品描述数据
     */
    private function _getMobileBody($mobile_body) {
        if ($mobile_body != '') {
            $mobile_body = str_replace('&quot;', '"', $mobile_body);
            $mobile_body = json_decode($mobile_body, true);
            if (!empty($mobile_body)) {
                return serialize($mobile_body);
            }
        }
        return '';
    }

    /**
     * 查询店铺商品分类
     */
    private function _getStoreClassArray($sgcate_id, $store_id) {
        $goods_stcids_arr = array();
        if (!empty($sgcate_id)){
            $sgcate_id_arr = array();
            foreach ($sgcate_id as $k=>$v){
                $sgcate_id_arr[] = intval($v);
            }
            $sgcate_id_arr = array_unique($sgcate_id_arr);
            $store_goods_class = Model('store_goods_class')->getStoreGoodsClassList(array('store_id' => $store_id, 'stc_id' => array('in', $sgcate_id_arr), 'stc_state' => '1'));
            if (!empty($store_goods_class)){
                foreach ($store_goods_class as $k=>$v){
                    if ($v['stc_id'] > 0){
                        $goods_stcids_arr[] = $v['stc_id'];
                    }
                    if ($v['stc_parent_id'] > 0){
                        $goods_stcids_arr[] = $v['stc_parent_id'];
                    }
                }
                $goods_stcids_arr = array_unique($goods_stcids_arr);
                sort($goods_stcids_arr);
            }
        }
        if (empty($goods_stcids_arr)){
            return '';
        } else {
            return ','.implode(',',$goods_stcids_arr).',';// 首尾需要加,
        }
    }

    /**
     * 生成商品返回商品ID(SKU)数组
     */
    private function _addGoods($param, $common_id, $common_array) {
        $goodsid_array = array();

        $model_goods = Model('goods');
        $model_type = Model('type');

        // 商品规格
        if (is_array($param['spec'])) {
            foreach ($param['spec'] as $value) {
                $goods = $this->_initGoodsByCommonGoods($common_id, $common_array);
                $goods['goods_name']        = $common_array['goods_name'] . ' ' . implode(' ', $value['sp_value']);
                $goods['goods_price']       = $value['price'];
                $goods['goods_promotion_price']=$value['price'];
                $goods['goods_marketprice'] = $value['marketprice'] == 0 ? $common_array['goods_marketprice'] : $value['marketprice'];
                $goods['goods_serial']      = $value['sku'];
                $goods['goods_storage_alarm']= intval($value['alarm']);
                $goods['goods_spec']        = serialize($value['sp_value']);
                $goods['goods_storage']     = $value['stock'];
                $goods['goods_barcode']     = $value['barcode'];
                $goods['color_id']          = intval($value['color']);
                $goods_id = $model_goods->addGoods($goods);
                $model_type->addGoodsType($goods_id, $common_id, array('cate_id' => $param['cate_id'], 'type_id' => $param['type_id'], 'attr' => $param['attr']));

                $goodsid_array[] = $goods_id;
            }
        } else {
            $goods = $this->_initGoodsByCommonGoods($common_id, $common_array);
            $goods['goods_name']        = $common_array['goods_name'];
            $goods['goods_price']       = $common_array['goods_price'];
            $goods['goods_promotion_price']=$common_array['goods_price'];
            $goods['goods_marketprice'] = $common_array['goods_marketprice'];
            $goods['goods_serial']      = $common_array['goods_serial'];
            $goods['goods_storage_alarm']= $common_array['goods_storage_alarm'];
            $goods['goods_spec']        = serialize(null);
            $goods['goods_storage']     = intval($param['g_storage']);
            $goods['goods_barcode']     = $common_array['goods_barcode'];
            $goods['color_id']          = 0;
            $goods_id = $model_goods->addGoods($goods);
            $model_type->addGoodsType($goods_id, $common_id, array('cate_id' => $param['cate_id'], 'type_id' => $param['type_id'], 'attr' => $param['attr']));

            $goodsid_array[] = $goods_id;
        }

        return $goodsid_array;
    }
    
    private function _editGoods($param, $common_id, $common_array, $store_id) {
        $goodsid_array = array();
        $colorid_array = array();

        $model_goods = Model('goods');
        $model_type = Model('type');
        $model_type->delGoodsAttr(array('goods_commonid' => $common_id));
        if (is_array($param['spec'])) {
            foreach ($param['spec'] as $value) {
                $goods = $this->_initGoodsByCommonGoods($common_id, $common_array);
                $goods_info = $model_goods->getGoodsInfo(array('goods_id' => $value['goods_id'], 'goods_commonid' => $common_id, 'store_id' => $store_id), 'goods_id');
                if (!empty($goods_info)) {
                    $goods_id = $goods_info['goods_id'];
                    $goods['goods_name']        = $common_array['goods_name'] . ' ' . implode(' ', $value['sp_value']);
                    $goods['goods_price']       = $value['price'];
                    $goods['goods_marketprice'] = $value['marketprice'] == 0 ? $common_array['goods_marketprice'] : $value['marketprice'];
                    $goods['goods_serial']      = $value['sku'];
                    $goods['goods_storage_alarm']= intval($value['alarm']);
                    $goods['goods_spec']        = serialize($value['sp_value']);
                    $goods['goods_storage']     = $value['stock'];
                    $goods['goods_barcode']     = $value['barcode'];
                    $goods['color_id']          = intval($value['color']);
                    // 虚拟商品不能有赠品
                    if ($common_array['is_virtual'] == 1) {
                        $goods['have_gift']    = 0;
                        Model('goods_gift')->delGoodsGift(array('goods_id' => $goods_id));
                        Model('goods_fcode')->delGoodsFCode(array('goods_id' => $goods_id));
                    }
                    unset($goods['goods_image']);
                    unset($goods['goods_addtime']);
                    $model_goods->editGoodsById($goods, $goods_id);
                } else {
                    $goods['goods_name']        = $common_array['goods_name'] . ' ' . implode(' ', $value['sp_value']);
                    $goods['goods_price']       = $value['price'];
                    $goods['goods_promotion_price']=$value['price'];
                    $goods['goods_marketprice'] = $value['marketprice'] == 0 ? $common_array['goods_marketprice'] : $value['marketprice'];
                    $goods['goods_serial']      = $value['sku'];
                    $goods['goods_storage_alarm']= intval($value['alarm']);
                    $goods['goods_spec']        = serialize($value['sp_value']);
                    $goods['goods_storage']     = $value['stock'];
                    $goods['goods_barcode']     = $value['barcode'];
                    $goods['color_id']          = intval($value['color']);
                    $rs = $goods_id = $model_goods->addGoods($goods);
                }
                $goodsid_array[] = intval($goods_id);
                $colorid_array[] = intval($value['color']);
                $model_type->addGoodsType($goods_id, $common_id, array('cate_id' => $param['cate_id'], 'type_id' => $param['type_id'], 'attr' => $param['attr']));
            }
        } else {
            if (C('dbdriver') == 'mysql') {
                $goods_spec_field_name = 'goods_spec';
            } else {
                $goods_spec_field_name = 'to_char(goods_spec)';
            }
            $goods = $this->_initGoodsByCommonGoods($common_id, $common_array);
            $goods_info = $model_goods->getGoodsInfo(array($goods_spec_field_name => serialize(null), 'goods_commonid' => $common_id, 'store_id' => $store_id), 'goods_id');
            if (!empty($goods_info)) {
                $goods_id = $goods_info['goods_id'];
                $goods['goods_name']        = $common_array['goods_name'];
                $goods['goods_price']       = $common_array['goods_price'];
                $goods['goods_marketprice'] = $common_array['goods_marketprice'];
                $goods['goods_serial']      = $common_array['goods_serial'];
                $goods['goods_storage_alarm']= $common_array['goods_storage_alarm'];
                $goods['goods_spec']        = serialize(null);
                $goods['goods_storage']     = intval($param['g_storage']);
                $goods['goods_barcode']     = $common_array['goods_barcode'];
                $goods['color_id']          = 0;
                if ($common_array['is_virtual'] == 1) {
                    $goods['have_gift']    = 0;
                    Model('goods_gift')->delGoodsGift(array('goods_id' => $goods_id));
                    Model('goods_fcode')->delGoodsFCode(array('goods_id' => $goods_id));
                }
                unset($goods['goods_image']);
                unset($goods['goods_addtime']);
                $model_goods->editGoodsById($goods, $goods_id);
            } else {
                $goods['goods_name']        = $common_array['goods_name'];
                $goods['goods_price']       = $common_array['goods_price'];
                $goods['goods_promotion_price']=$common_array['goods_price'];
                $goods['goods_marketprice'] = $common_array['goods_marketprice'];
                $goods['goods_serial']      = $common_array['goods_serial'];
                $goods['goods_storage_alarm']= $common_array['goods_storage_alarm'];
                $goods['goods_spec']        = serialize(null);
                $goods['goods_storage']     = intval($param['g_storage']);
                $goods['goods_barcode']     = $common_array['goods_barcode'];
                $goods['color_id']          = 0;
                $goods_id = $model_goods->addGoods($goods);
            }
            $goodsid_array[] = intval($goods_id);
            $colorid_array[] = 0;
            $model_type->addGoodsType($goods_id, $common_id, array('cate_id' => $param['cate_id'], 'type_id' => $param['type_id'], 'attr' => $param['attr']));
        }
        return array('goodsid_array' => $goodsid_array, 'colorid_array' =>  array_unique($colorid_array));
    }

    /**
     * 根据通用商品数据初始化商品数据
     */
    private function _initGoodsByCommonGoods($common_id, $common_array) {
        $goods = array();
        $goods['goods_commonid']    = $common_id;
        $goods['goods_jingle']      = $common_array['goods_jingle'];
        $goods['store_id']          = $common_array['store_id'];
        $goods['store_name']        = $common_array['store_name'];
        $goods['gc_id']             = $common_array['gc_id'];
        $goods['gc_id_1']           = $common_array['gc_id_1'];
        $goods['gc_id_2']           = $common_array['gc_id_2'];
        $goods['gc_id_3']           = $common_array['gc_id_3'];
        $goods['brand_id']          = $common_array['brand_id'];
        $goods['spec_name']         = $common_array['spec_name'];
        $goods['goods_image']       = $common_array['goods_image'];
        $goods['goods_state']       = $common_array['goods_state'];
        $goods['goods_verify']      = $common_array['goods_verify'];
        $goods['goods_addtime']     = TIMESTAMP;
        $goods['goods_edittime']    = TIMESTAMP;
        $goods['areaid_1']          = $common_array['areaid_1'];
        $goods['areaid_2']          = $common_array['areaid_2'];
        $goods['transport_id']      = $common_array['transport_id'];
        $goods['goods_freight']     = $common_array['goods_freight'];
        $goods['goods_vat']         = $common_array['goods_vat'];
        $goods['goods_commend']     = $common_array['goods_commend'];
        $goods['goods_stcids']      = $common_array['goods_stcids'];
        $goods['is_virtual']        = $common_array['is_virtual'];
        $goods['virtual_indate']    = $common_array['virtual_indate'];
        $goods['virtual_limit']     = $common_array['virtual_limit'];
        $goods['virtual_invalid_refund'] = $common_array['virtual_invalid_refund'];
        $goods['is_own_shop']       = $common_array['is_own_shop'];
        return $goods;
    }

    private function _imageAll($common_id, $store_id, $image_all, $image_main) {
        $model_goods = Model('goods');

        $image_array = explode(',', $image_all);

        $insert_array = array();
        foreach ($image_array as $value) {
            if(!empty($value)) {
                $tmp_insert = array();
                $tmp_insert['goods_commonid']   = $common_id;
                $tmp_insert['store_id']         = $store_id;
                $tmp_insert['color_id']         = 0;
                $tmp_insert['goods_image']      = $value;
                $tmp_insert['goods_image_sort'] = 0;
                if($value == $image_main) {
                    $tmp_insert['is_default'] = 1 ;
                } else {
                    $tmp_insert['is_default'] = 0;
                }
                $insert_array[] = $tmp_insert;
            }
        }

        $model_goods->addGoodsImagesAll($insert_array);
    }

    /**
     * 记录日志
     *
     * @param $content 日志内容
     * @param $state 1成功 0失败
     */
    private function _recordLog($content = '', $seller_id, $seller_name, $store_id, $state = 1) {
        $log = array();
        $log['log_content'] = $content;
        $log['log_time'] = TIMESTAMP;
        $log['log_seller_id'] = $seller_id;
        $log['log_seller_name'] = $seller_name;
        $log['log_store_id'] = $store_id;
        $log['log_seller_ip'] = getIp();
        $log['log_url'] = 'goodsLogic&saveGoods';
        $log['log_state'] = $state;
        $model_seller_log = Model('seller_log');
        $model_seller_log->addSellerLog($log);
    }

    /**
     * 上传图片
     *
     */
    public function uploadGoodsImage($image_name, $store_id, $album_limit)
    {
        // 判断图片数量是否超限
        $model_album = Model('album');
        if ($album_limit > 0) {
            $album_count = $model_album->getCount(array('store_id' => $store_id));
            if ($album_count >= $album_limit) {
                return callback(false, L('store_goods_album_climit'));
            }
        }

        $class_info = $model_album->getOne(array('store_id' => $store_id, 'is_default' => 1), 'album_class');
        // 上传图片
        $upload = new UploadFile();
        $upload->set('default_dir', ATTACH_GOODS . DS . $store_id . DS . $upload->getSysSetPath());
        $upload->set('max_size', C('image_max_filesize'));

        $upload->set('thumb_width', GOODS_IMAGES_WIDTH);
        $upload->set('thumb_height', GOODS_IMAGES_HEIGHT);
        $upload->set('thumb_ext', GOODS_IMAGES_EXT);
        $upload->set('fprefix', $store_id);
        $upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png'));
        $result = $upload->upfile($image_name,true);
        if (!$result) {
            return callback(false, $upload->error);
        }

        $img_path = $upload->getSysSetPath() . $upload->file_name;

        // 取得图像大小
        if (!C('oss.open')) {
            list($width, $height, $type, $attr) = getimagesize(BASE_UPLOAD_PATH . '/' . ATTACH_GOODS . '/' . $store_id . DS . $img_path);
        } else {
            list($width, $height, $type, $attr) = getimagesize(C('oss.img_url') . '/' . ATTACH_GOODS . '/' . $store_id . DS . $img_path);
        }

        // 存入相册
        $image = explode('.', $_FILES[$image_name]["name"]);
        $insert_array = array();
        $insert_array['apic_name'] = $image['0'];
        $insert_array['apic_tag'] = '';
        $insert_array['aclass_id'] = $class_info['aclass_id'];
        $insert_array['apic_cover'] = $img_path;
        $insert_array['apic_size'] = intval($_FILES[$image_name]['size']);
        $insert_array['apic_spec'] = $width . 'x' . $height;
        $insert_array['upload_time'] = TIMESTAMP;
        $insert_array['store_id'] = $store_id;
        $model_album->addPic($insert_array);

        $data = array ();
        $data ['thumb_name'] = cthumb($img_path, 240, $store_id);
        $data ['name']      = $img_path;

        return callback(true, '', $data);
    }
    
    /**
     * 编辑商品图
     */
    public function editSaveImage($img, $common_id, $store_id, $seller_id, $seller_name) {

        if ($common_id <= 0 || empty($_POST['img'])) {
            return callback(false, '参数错误');
        }
        $model_goods = Model('goods');
        // 删除原有图片信息
        $model_goods->delGoodsImages(array('goods_commonid' => $common_id, 'store_id' => $store_id));
        // 保存
        $insert_array = array();
        foreach ($_POST['img'] as $key => $value) {
            $k = 0;
            foreach ($value as $v) {
                if ($v['name'] == '') {
                    continue;
                }
                // 商品默认主图
                $update_array = array();        // 更新商品主图
                $update_where = array();
                $update_array['goods_image']    = $v['name'];
                $update_where['goods_commonid'] = $common_id;
                $update_where['store_id']       = $store_id;
                $update_where['color_id']       = $key;
                if ($k == 0 || $v['default'] == 1) {
                    $k++;
                    $update_array['goods_image']    = $v['name'];
                    $update_where['goods_commonid'] = $common_id;
                    $update_where['store_id']       = $store_id;
                    $update_where['color_id']       = $key;
                    // 更新商品主图
                    $model_goods->editGoods($update_array, $update_where);
                }
                $tmp_insert = array();
                $tmp_insert['goods_commonid']   = $common_id;
                $tmp_insert['store_id']         = $store_id;
                $tmp_insert['color_id']         = $key;
                $tmp_insert['goods_image']      = $v['name'];
                $tmp_insert['goods_image_sort'] = ($v['default'] == 1) ? 0 : $v['sort'];
                $tmp_insert['is_default']       = $v['default'];
                $insert_array[] = $tmp_insert;
            }
        }
        $rs = $model_goods->addGoodsImagesAll($insert_array);
        if ($rs) {
            $this->_recordLog('商品图片编辑，SPU:'.$common_id, $seller_id, $seller_name, $store_id);
            return callback(true);
        } else {
            return callback(false, '商品图片编辑失败');
        }
    }
    
    /**
     * 商品上架
     * @param unknown $commonid_array
     * @param unknown $store_id
     * @param unknown $seller_id
     * @param unknown $seller_name
     * @return multitype:unknown
     */
    public function goodsShow($commonid_array, $store_id, $seller_id, $seller_name) {
        $return = Model('goods')->editProducesOnline(array('goods_commonid' => array('in', $commonid_array), 'store_id' => $store_id));
        if ($return) {
            // 添加操作日志
            $this->_recordLog('商品上架，SPU:'.implode(',', $commonid_array), $seller_id, $seller_name, $store_id);
            return callback(true);
        } else {
            return callback(false, '商品上架失败');
        }
    }
    
    /**
     * 商品下架
     * @param unknown $commonid_array
     * @param unknown $store_id
     * @param unknown $seller_id
     * @param unknown $seller_name
     * @return multitype:unknown
     */
    public function goodsUnShow($commonid_array, $store_id, $seller_id, $seller_name) {
        $model_goods = Model('goods');
        $where = array();
        $where['goods_commonid'] = array('in', $commonid_array);
        $where['store_id'] = $store_id;
        $return = Model('goods')->editProducesOffline($where);
        if ($return) {
            // 更新优惠套餐状态关闭
            $goods_list = $model_goods->getGoodsList($where, 'goods_id');
            if (!empty($goods_list)) {
                $goodsid_array = array();
                foreach ($goods_list as $val) {
                    $goodsid_array[] = $val['goods_id'];
                }
                Model('p_bundling')->editBundlingCloseByGoodsIds(array('goods_id' => array('in', $goodsid_array)));
            }
            // 添加操作日志
            $this->_recordLog('商品下架，SPU:'.implode(',', $commonid_array), $seller_id, $seller_name, $store_id);
            return callback(true);
        } else {
            return callback(false, '商品下架失败');
        }
    }
    
    public function goodsDrop($commonid_array, $store_id, $seller_id, $seller_name) {
        $return = Model('goods')->delGoodsNoLock(array('goods_commonid' => array('in', $commonid_array), 'store_id' => $store_id));
        if ($return) {
            // 添加操作日志
            $this->_recordLog('删除商品，SPU：'.implode(',', $commonid_array), $seller_id, $seller_name, $store_id);
            return callback(true);
        } else {
            return callback(false, '商品删除失败');
        }
        
    }
}
