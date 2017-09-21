<?php
/**
 * 购买行为
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377

 */
defined('In33hao') or exit('Access Invalid!');
class buy_1Logic {

    /**
     * 取得商品最新的属性及促销[购物车]
     * @param unknown $cart_list
     * @param \StdClass $jjgObj
     */
    public function getGoodsCartList($cart_list, \StdClass $jjgObj = null) {

        $cart_list = $this->_getOnlineCartList($cart_list);
        //预定
        $this->_getBookCartList($cart_list);

        //优惠套装
        $this->_getBundlingCartList($cart_list);

        //手机专享
        $this->getMbSoleCartList($cart_list);

        //团购
        $this->getGroupbuyCartList($cart_list);

        //限时折扣
        $this->getXianshiCartList($cart_list);

        //赠品
        $this->_getGiftCartList($cart_list);

        // 加价购
        if ($jjgObj) {
            $jjgSkus = array();
            foreach ((array) $cart_list as $cart) {
                if ((int) $cart['bl_id'] === 0) {
                    $jjgSkus[$cart['goods_id']] = null;
                }
            }
            $jjgSkus = array_keys($jjgSkus);
            $jjgDetails = null;
            if ($jjgSkus) {
                $jjgIdFilter = null;
                if (isset($jjgObj->jjgPostData) && is_array($jjgObj->jjgPostData)) {
                    $jjgIdFilter = array_keys($jjgObj->jjgPostData);
                }
                $jjgDetails = Model('p_cou')->getRelationalCouDetailBySku($jjgSkus, $jjgIdFilter);
            }
            $jjgObj->details = $jjgDetails;
            // 依据加价购活动对购物车条目排序
            foreach ($cart_list as & $cartRef) {
                $rank = -1;
                if ($cartRef['bl_id'] > 0) {
                    $rank = 0;
                } else if (isset($jjgDetails['couMap'][$cartRef['goods_id']])) {
                    $rank = (int) $jjgDetails['couMap'][$cartRef['goods_id']];
                }
                $cartRef['jjgRank'] = $rank;
            }
            usort($cart_list, function($a, $b) {
                return 0 - $a['jjgRank'] + $b['jjgRank'];
            });
        }
        return $cart_list;
    }

    /**
     * 取得商品最新的属性及促销[立即购买]
     * @param int $goods_id
     * @param int $quantity
     * @return array
     */
    public function getGoodsOnlineInfo($goods_id,$quantity) {
        $goods_info = $this->_getGoodsOnlineInfo($goods_id,$quantity);

        //预定商品优先
        $this->getBookInfo($goods_info);

        if (!$goods_info['is_book']) {

            //手机专享
            $this->getMbSoleInfo($goods_info);

            //团购
            $this->getGroupbuyInfo($goods_info);

            //限时折扣
            $this->getXianshiInfo($goods_info,$goods_info['goods_num']);

        }

        //赠品
        $this->_getGoodsGiftList($goods_info);

        return $goods_info;
    }

    /**
     * 商品金额计算(分别对每个商品/优惠套装小计、每个店铺小计)
     * @param unknown $store_cart_list 以店铺ID分组的购物车商品信息
     * @return array
     */
    public function calcCartList($store_cart_list) {
        if (empty($store_cart_list) || !is_array($store_cart_list)) return array($store_cart_list,array(),0);

        //存放每个店铺的商品总金额
        $store_goods_total = array();
        //存放本次下单所有店铺商品总金额
        $order_goods_total = 0;

        foreach ($store_cart_list as $store_id => $store_cart) {
            $tmp_amount = 0;
            foreach ($store_cart as $key => $cart_info) {
                $store_cart[$key]['goods_total'] = ncPriceFormat($cart_info['goods_price'] * $cart_info['goods_num']);
                $store_cart[$key]['goods_image_url'] = cthumb($store_cart[$key]['goods_image']);
                $tmp_amount += $store_cart[$key]['goods_total'];
            }
            $store_cart_list[$store_id] = $store_cart;
            $store_goods_total[$store_id] = ncPriceFormat($tmp_amount);
        }
        return array($store_cart_list,$store_goods_total);
    }

    /**
     * 将商品价格设置为会员折扣价
     * @param array $goods_list
     * @param array $store_order_discount array(store_id => 折扣)
     */
    public function parseZhekou(& $goods_list, $store_order_discount = array()) {
        if (empty($store_order_discount)) return;
        if (array_key_exists('store_id', $goods_list)) {
            if (array_key_exists($goods_list['store_id'], $store_order_discount)) {
                //直接购买（单个商品）
                if (!$goods_list['is_book']) {
                    $goods_list['goods_price'] = ncPriceFormat($goods_list['goods_price'] * $store_order_discount[$goods_list['store_id']]/100);
                    $goods_list['zkj'] = true;                     
                }
            }
        } else {
            //购物车
            foreach ($goods_list as $k => $goods_info) {
                if (array_key_exists($goods_info['store_id'], $store_order_discount)) {
                    $orderdiscount = $store_order_discount[$goods_info['store_id']];
                    if (is_array($goods_info['bl_goods_list']) && !empty($goods_info['bl_goods_list'])) {
                        foreach ($goods_info['bl_goods_list'] as $kk => $vv) {
                            $goods_list[$k]['bl_goods_list'][$kk]['bl_goods_price'] = ncPriceFormat($vv['bl_goods_price'] * $orderdiscount/100);
                            $goods_list[$k]['bl_goods_list'][$kk]['zkj'] = true;
                        }
                    }
                    $goods_list[$k]['goods_price'] = ncPriceFormat($goods_info['goods_price'] * $orderdiscount/100);
                    $goods_list[$k]['zkj'] = true;
                }
            }            
        }

    }

    /**
     * 取得店铺级优惠 - 跟据商品金额返回每个店铺当前符合的一条活动规则，如果有赠品，则自动追加到购买列表，价格为0
     * @param unknown $store_goods_total 每个店铺的商品金额小计，以店铺ID为下标
     * @return array($premiums_list,$mansong_rule_list) 分别为赠品列表[下标自增]，店铺满送规则列表[店铺ID为下标]
     */
    public function getMansongRuleCartListByTotal($store_goods_total) {
        if (!C('promotion_allow') || empty($store_goods_total) || !is_array($store_goods_total)) return array(array(),array());

        $model_mansong = Model('p_mansong');
        $model_goods = Model('goods');

        //定义赠品数组，下标为店铺ID
        $premiums_list = array();
        //定义满送活动数组，下标为店铺ID
        $mansong_rule_list = array();

        foreach ($store_goods_total as $store_id => $goods_total) {
            $rule_info = $model_mansong->getMansongRuleByStoreID($store_id,$goods_total);
            if (is_array($rule_info) && !empty($rule_info)) {
                //即不减金额，也找不到促销商品时(已下架),此规则无效
                if (empty($rule_info['discount']) && empty($rule_info['mansong_goods_name'])) {
                    continue;
                }
                $rule_info['desc'] = $this->_parseMansongRuleDesc($rule_info);
                $rule_info['discount'] = ncPriceFormat($rule_info['discount']);
                $mansong_rule_list[$store_id] = $rule_info;
                //如果赠品在售,有库存,则追加到购买列表
                if (!empty($rule_info['mansong_goods_name']) && !empty($rule_info['goods_storage'])) {
                    $data = array();
                    $data['goods_id'] = $rule_info['goods_id'];
                    $data['goods_name'] = $rule_info['mansong_goods_name'];
                    $data['goods_num'] = 1;
                    $data['goods_price'] = 0.00;
                    $data['goods_image'] = $rule_info['goods_image'];
                    $data['goods_image_url'] = cthumb($rule_info['goods_image']);
                    $data['goods_storage'] = $rule_info['goods_storage'];
                    $premiums_list[$store_id][] = $data;
                }
            }
        }
        return array($premiums_list,$mansong_rule_list);
    }

    /**
     * 重新计算每个店铺最终商品总金额(最初计算金额减去各种优惠/加运费)
     * @param array $store_goods_total 店铺商品总金额
     * @param array $preferential_array 店铺优惠活动内容
     * @param string $preferential_type 优惠类型
     * @return array 返回扣除优惠后的店铺商品总金额
     */
    public function reCalcGoodsTotal($store_goods_total, $preferential_array = array(), $preferential_type) {
        $deny = empty($store_goods_total) || !is_array($store_goods_total) || empty($preferential_array) || !is_array($preferential_array);
        if ($deny) return $store_goods_total;

        switch ($preferential_type) {
            case 'mansong':
                if (!C('promotion_allow')) return $store_goods_total;
                foreach ($preferential_array as $store_id => $rule_info) {
                    if (is_array($rule_info) && $rule_info['discount'] > 0) {
                        $store_goods_total[$store_id] -= $rule_info['discount'];
                    }
                }
                break;

            case 'voucher':
                if (!C('voucher_allow')) return $store_goods_total;
                foreach ($preferential_array as $store_id => $voucher_info) {
                    $store_goods_total[$store_id] -= $voucher_info['voucher_price'];
                }
                break;

            case 'rpt':
                foreach ($store_goods_total as $store_id => $goods_total) {
                    if (array_key_exists($store_id, $preferential_array)) {
                        $store_goods_total[$store_id] = ncPriceFormat($store_goods_total[$store_id]*$preferential_array[$store_id]/100);
                    }
                }
                break;

            case 'freight':
                foreach ($preferential_array as $store_id => $freight_total) {
                    $store_goods_total[$store_id] += $freight_total;
                }
                break;
        }
        return $store_goods_total;
    }

    /**
     * 取得店铺可用的代金券
     * @param array $store_goods_total array(店铺ID=>商品总金额)
     * @return array
     */
    public function getStoreAvailableVoucherList($store_goods_total, $member_id) {
        if (!C('voucher_allow')) return array();
        $voucher_list = array();
        $model_voucher = Model('voucher');
        foreach ($store_goods_total as $store_id => $goods_total) {
            $condition = array();
            $condition['voucher_store_id'] = $store_id;
            $condition['voucher_owner_id'] = $member_id;
            $voucher_list[$store_id] = $model_voucher->getCurrentAvailableVoucher($condition,$goods_total, APP_ID == 'mobile' ? 'voucher_limit desc' : '');
        }
        return $voucher_list;
    }

    /**
     * 取得可用的平台红包
     * @param floot $goods_total 总金额 
     * @return array
     */
    public function getStoreAvailableRptList($member_id,$goods_total = 0,$order = '') {
        if (!C('redpacket_allow')) return array();
        $condition = array();
        $condition['rpacket_owner_id'] = $member_id;
        return Model('redpacket')->getCurrentAvailableRpt($condition,$goods_total,$order);
    }

    /**
     * 验证平台红包有效性
     * @param floot $goods_total 总金额
     * @return array
     */
    public function reParseRptInfo($input_rpt_info,$order_total,$member_id) {
        if (empty($input_rpt_info)) return array();
        $condition = array();
        $condition['rpacket_owner_id'] = $member_id;
        $condition['rpacket_t_id'] = $input_rpt_info['rpacket_t_id'];
        $info = Model('redpacket')->getCurrentAvailableRpt($condition,$order_total);
        if ($info) {
            return $info[$input_rpt_info['rpacket_t_id']];
        } else {
            return array();
        }
    }

    /**
     * 
     * @param array $store_order_total 每个店铺应付总金额(含运费)
     * @param number $rpt_total 红包金额
     * @return array array(每个订单减去红包后的总金额,每个订单使用的红包值)
     */
    public function parseOrderRpt($store_order_total = array(), $rpt_total = 0) {
        if (empty($store_order_total) || $rpt_total <= 0) return array($store_order_total,array());

        //总的红包优惠比例,保留3位小数
        $all_order_total = array_sum($store_order_total);
        $rpt_rate = abs(number_format($rpt_total/$all_order_total,5));
        if ($rpt_rate <= 1) {
            $rpt_rate = floatval(substr($rpt_rate,0,5));
        } else {
            $rpt_rate = 0;
        }
        //每个订单的优惠金额累加保存入 $rpt_sum
        $rpt_sum = 0;
        //存放每个订单使用了多少红包
        $store_rpt_total = array();

        foreach ($store_order_total as $store_id => $order_total) {
            //计算本订单优惠红包金额
            $rpt_value = floor($order_total*$rpt_rate);
            $store_order_total[$store_id] -= $rpt_value;
            $store_rpt_total[$store_id] = $rpt_value;
            $rpt_sum += $rpt_value; 
        }
        //将因舍出小数部分出现的差值补到其中一个订单的实际成交价中
        if ($rpt_total > $rpt_sum) {
            foreach ($store_order_total as $store_id => $order_total) {
                if ($order_total > 0) {
                    $store_order_total[$store_id] -= $rpt_total - $rpt_sum;
                    $store_rpt_total[$store_id] += $rpt_total - $rpt_sum;
                    break;
                }
            }
        }
        return array($store_order_total,$store_rpt_total);
    }

    /**
     * 将店铺红包减去运费的余额追加到店铺总优惠里
     * @param unknown $store_promotion_total
     * @param unknown $store_freight_total
     * @param unknown $store_rpt_total
     */
    public function reCalcStorePromotionTotal($store_promotion_total,$store_freight_total,$store_rpt_total) {
        if (!is_array($store_rpt_total) || empty($store_rpt_total)) return $store_promotion_total;
        foreach ($store_rpt_total as $store_id => $rpt_total) {
            $ptotal = $rpt_total - $store_freight_total[$store_id];
            if ($ptotal > 0) {
                $store_promotion_total[$store_id] += $ptotal;
            }
        }
        return $store_promotion_total;
    }

    /**
     * 验证传过来的代金券是否可用有效，如果无效，直接删除
     * @param array $input_voucher_list 代金券列表
     * @param array $store_goods_total (店铺ID=>商品总金额)
     * @return array
     */
    public function reParseVoucherList($input_voucher_list = array(), $store_goods_total = array(), $member_id) {
        if (empty($input_voucher_list) || !is_array($input_voucher_list)) return array();
        $store_voucher_list = $this->getStoreAvailableVoucherList($store_goods_total, $member_id);

        foreach ($input_voucher_list as $store_id => $voucher) {
            $tmp = $store_voucher_list[$store_id];
            if (is_array($tmp) && isset($tmp[$voucher['voucher_t_id']])) {
                $input_voucher_list[$store_id]['voucher_id'] = $tmp[$voucher['voucher_t_id']]['voucher_id'];
                $input_voucher_list[$store_id]['voucher_code'] = $tmp[$voucher['voucher_t_id']]['voucher_code'];
                $input_voucher_list[$store_id]['voucher_owner_id'] = $tmp[$voucher['voucher_t_id']]['voucher_owner_id'];
            } else {
                unset($input_voucher_list[$store_id]);
            }
        }
        return $input_voucher_list;
    }

    /**
     * 判断商品是不是限时折扣中，如果购买数量若>=规定的下限，按折扣价格计算,否则按原价计算
     * @param array $goods_info
     * @param number $quantity 购买数量
     */
    public function getXianshiInfo( & $goods_info, $quantity) {
        if (empty($quantity)) $quantity = 1;
        if (!C('promotion_allow') || empty($goods_info['xianshi_info']) || !empty($goods_info['groupbuy_info']) || $goods_info['ifsole']) return ;
        $goods_info['xianshi_info']['down_price'] = ncPriceFormat($goods_info['goods_price'] - $goods_info['xianshi_info']['xianshi_price']);
        if ($quantity >= $goods_info['xianshi_info']['lower_limit']) {
            $goods_info['goods_yprice'] = $goods_info['goods_price'];
            $goods_info['goods_price'] = $goods_info['xianshi_info']['xianshi_price'];
            $goods_info['promotions_id'] = $goods_info['xianshi_info']['xianshi_id'];
            $goods_info['ifxianshi'] = true;
        }
    }

    /**
     * 输出有货到付款时，在线支付和货到付款及每种支付下商品数量和详细列表
     * @param $buy_list 商品列表
     * @return 返回 以支付方式为下标分组的商品列表
     */
    public function getOfflineGoodsPay($buy_list) {
        //以支付方式为下标，存放购买商品
        $buy_goods_list = array();
        $offline_pay = Model('payment')->getPaymentOpenInfo(array('payment_code'=>'offline'));
        if ($offline_pay) {
            //下单里包括平台自营商品并且平台已开启货到付款，则显示货到付款项及对应商品数量,取出支持货到付款的店铺ID组成的数组，目前就一个，DEFAULT_PLATFORM_STORE_ID
            $offline_store_id_array = model('store')->getOwnShopIds();
            foreach ($buy_list as $value) {
                if (in_array($value['store_id'],$offline_store_id_array)) {
                    $buy_goods_list['offline'][] = $value;
                } else {
                    $buy_goods_list['online'][] = $value;
                }
            }
        }
        if ($buy_list[0]['is_book']) {
            unset($buy_goods_list['offline']);
        }
        return $buy_goods_list;
    }

    /**
     * 计算每个店铺(所有店铺级优惠活动)总共优惠多少金额,商品金额+运费-最终结算金额=优惠了多少
     * @param array $store_goods_total 最初店铺商品总金额
     * @param array $store_freight_total 各店铺运费
     * @param array $store_final_goods_total 去除各种店铺级促销后，最终店铺商品总金额(不含运费)
     * @return array
     */
    public function getStorePromotionTotal($store_goods_total, $store_freight_total, $store_final_goods_total) {
        if (!is_array($store_goods_total) || !is_array($store_freight_total) || !is_array($store_final_goods_total)) return array();
        $store_promotion_total = array();
        foreach ($store_goods_total as $store_id => $goods_total) {
            $store_promotion_total[$store_id] = $goods_total + $store_freight_total[$store_id] - $store_final_goods_total[$store_id];
        }
        return $store_promotion_total;
    }

    /**
     * 跟据商品金额返回免运费店铺ID及免运费下限金额描述[已设置并达到免运费金额标准]
     * 不再返回需要计算运费的店铺ID组成的数组[未达到免运费标准或未设置免运费金额] 
     * @param array $store_goods_total 每个店铺的商品金额小计，以店铺ID为下标
     * @return array
     */
    public function getStoreFreightDescList($store_goods_total) {
        if (empty($store_goods_total) || !is_array($store_goods_total)) return array(array(),array());

        //定义返回数组
        $need_calc_sid_array = array();
        $cancel_calc_sid_array = array();

        //如果商品金额未达到免运费设置下线，则需要计算运费
        $condition = array('store_id' => array('in',array_keys($store_goods_total)));
        $store_list = Model('store')->getStoreOnlineList($condition,null,'','store_id,store_free_price');
        foreach ($store_list as $store_info) {
            $limit_price = floatval($store_info['store_free_price']);
            if ($limit_price == 0 || $limit_price > $store_goods_total[$store_info['store_id']]) {
                //需要计算运费
                $need_calc_sid_array[] = $store_info['store_id'];
            } else {
                //返回免运费金额下限
                $cancel_calc_sid_array[$store_info['store_id']]['free_price'] = $limit_price;
                $cancel_calc_sid_array[$store_info['store_id']]['desc'] = sprintf('满%s免运费',$limit_price);
            }
        }
        return $cancel_calc_sid_array;
    }

    /**
     * 取得店铺运费信息
     * 先将免运费的店铺运费置0，然后算出店铺里没使用运费模板的商品运费之和 ，存到iscalced下标中
     * 然后再计算使用运费模板的信息(array(店铺ID=>运费模板ID)，放到tpl_ids下标里
     * @param array $buy_list 购买商品列表
     * @param array $free_freight_sid_list 免运费的店铺ID数组
     */
    public function getStoreFreightList($buy_list = array(), $free_freight_sid_list) {
        //定义返回数组
        $return = array();
        //先将免运费的店铺运费置0(格式:店铺ID=>0)
        $freight_list = array();
        if (!empty($free_freight_sid_list) && is_array($free_freight_sid_list)) {
            foreach ($free_freight_sid_list as $store_id) {
                $freight_list[$store_id] = 0;
            }
        }
        //保存包邮店铺ID
        $return['free_sid_list'] = array_values($free_freight_sid_list);

        //然后算出店铺里没使用运费模板(优惠套装商品除外)的商品运费之和(格式:店铺ID=>运费)
        //定义数组，存放店铺优惠套装商品运费总额 store_id=>运费
        $store_bl_goods_freight = array();
        foreach ($buy_list as $key => $goods_info) {
            //免运费店铺的商品不需要计算，但如果设置了配送区域，则需要保存该模板ID
            if (in_array($goods_info['store_id'], $free_freight_sid_list)) {
                if (!$goods_info['transport_id']) {
                    unset($buy_list[$key]);
                    continue;                    
                }
            }
            //优惠套装商品运费另算
            if (intval($goods_info['bl_id'])) {
                unset($buy_list[$key]);
                $store_bl_goods_freight[$goods_info['store_id']][] = $goods_info['bl_id'];
                continue;
            }
            if (!intval($goods_info['transport_id']) &&  !in_array($goods_info['store_id'],$free_freight_sid_list)) {
                $freight_list[$goods_info['store_id']] += $goods_info['goods_freight'];
                unset($buy_list[$key]);
            }
        }
        //计算优惠套装商品运费
        if (!empty($store_bl_goods_freight)) {
            $model_bl = Model('p_bundling');
            foreach ($store_bl_goods_freight as $store_id => $bl_list) {
                foreach (array_unique($bl_list) as $bl_id) {
                    $bl_info = $model_bl->getBundlingInfo(array('bl_id'=>$bl_id));
                    if (!empty($bl_info) && !in_array($store_id,$return['free_sid_list'])) {
                        $freight_list[$store_id] += $bl_info['bl_freight'];
                    }                    
                }
            }
        }

        $return['iscalced'] = $freight_list;

        $freight_list = array();
        foreach ($buy_list as $goods_info) {
            $freight_list[$goods_info['store_id']][] = $goods_info['transport_id'];
        }
        //使用相同运费的模板的最后只留一个即可。
        foreach ($freight_list as $store_id => $v) {
            $freight_list[$store_id] = array_unique($v);
        }
        $return['tpl_ids'] = $freight_list;

        return $return;
    }

    /**
     * 根据地区选择计算出所有店铺最终运费
     * @param array $freight_list 运费信息(店铺ID，运费，运费模板ID)
     * @param int $city_id 市级ID
     * @return array 返回店铺ID=>运费
     */
    public function calcStoreFreight($freight_list, $city_id) {
        if (!is_array($freight_list) || empty($freight_list) || empty($city_id)) return;

        //不支持配送的运费模板ID组成的数组
        $no_send_tpl_ids = array();

        //免费和固定运费计算结果
        $return_list = $freight_list['iscalced'];

        //免运费店铺ID
        $free_sid_list = $freight_list['free_sid_list'];

        //使用运费模板的信息(array(店铺ID=>array(运费模板ID=>购买数量))
        $tpl_ids_list = $freight_list['tpl_ids'];

        //然后计算使用运费运费模板的在该$city_id时的运费值
        if (!empty($tpl_ids_list) && is_array($tpl_ids_list)) {
            //如果有商品使用的运费模板，先计算这些商品的运费总金额
            $model_transport = Model('transport');
            foreach ($tpl_ids_list as $store_id => $transport_id_list) {
                foreach ($transport_id_list as $transport_id) {
                    $freight_total = $model_transport->calc_transport($transport_id, $city_id);
                    if ($freight_total === false) {
                        if (!in_array($transport_id,$no_send_tpl_ids)) {
                            $no_send_tpl_ids[] = $transport_id;
                        }
                    } else {
                        //包邮店铺不再增加运费
                        if (!in_array($store_id,$free_sid_list)) {
                            if (empty($return_list[$store_id])) {
                                $return_list[$store_id] = $freight_total;
                            } else {
                                $return_list[$store_id] += $freight_total;
                            }    
                        }
                    }                    
                }
            }
        }
        return array($return_list,$no_send_tpl_ids);
    }

    /**
     * 追加赠品到下单列表,并更新购买数量
     * @param array $store_cart_list 购买列表
     * @param array $store_premiums_list 赠品列表
     * @param array $store_mansong_rule_list 满即送规则
     */
    public function appendPremiumsToCartList($store_cart_list, $store_premiums_list = array(), $store_mansong_rule_list = array(), $member_id) {
        if (empty($store_cart_list)) return array();

        //处理商品级赠品
        foreach ($store_cart_list as $store_id => $cart_list) {
            foreach ($cart_list as $cart_info) {
                if (empty($cart_info['gift_list'])) continue;
                if (!is_array($store_premiums_list)) $store_premiums_list = array();
                if (!array_key_exists($store_id,$store_premiums_list)) $store_premiums_list[$store_id] = array();
                $zenpin_info = array();
                foreach ($cart_info['gift_list'] as $gift_info) {
                    $zenpin_info['goods_id'] = $gift_info['gift_goodsid'];
                    $zenpin_info['goods_name'] = $gift_info['gift_goodsname'];
                    $zenpin_info['goods_image'] = $gift_info['gift_goodsimage'];
                    $zenpin_info['goods_storage'] = $gift_info['goods_storage'];
                    $zenpin_info['goods_num'] = $cart_info['goods_num'] * $gift_info['gift_amount'];
                    $store_premiums_list[$store_id][] = $zenpin_info;
                }
            }
        }

        //取得每种商品的库存[含赠品]
        $goods_storage_quantity = $this->_getEachGoodsStorageQuantity($store_cart_list,$store_premiums_list);

        //取得每种商品的购买量[不含赠品]
        $goods_buy_quantity = $this->_getEachGoodsBuyQuantity($store_cart_list);
        foreach ($goods_buy_quantity as $goods_id => $quantity) {
            $goods_storage_quantity[$goods_id] -= $quantity;
            if ($goods_storage_quantity[$goods_id] < 0) {
                //商品库存不足，请重购买
                return false;
            }
        }
        //将赠品追加到购买列表

        if(is_array($store_premiums_list)) {
            foreach ($store_premiums_list as $store_id => $goods_list) {
                $zp_list = array();
                $gift_desc = '';
                foreach ($goods_list as $goods_info) {
                    //如果没有库存了，则不再送赠品
                    if ($goods_storage_quantity[$goods_info['goods_id']] == 0) {
                        $gift_desc = '，赠品库存不足，未能全部送出 ';
                        continue;
                    }


                    $new_data = array();
                    $new_data['buyer_id'] = $member_id;
                    $new_data['store_id'] = $store_id;
                    $new_data['store_name'] = $store_cart_list[$store_id][0]['store_name'];
                    $new_data['goods_id'] = $goods_info['goods_id'];
                    $new_data['goods_name'] = $goods_info['goods_name'];
                    $new_data['goods_price'] = 0;
                    $new_data['goods_image'] = $goods_info['goods_image'];
                    $new_data['bl_id'] = 0;
                    $new_data['state'] = true;
                    $new_data['storage_state'] = true;
                    $new_data['gc_id'] = 0;
                    $new_data['transport_id'] = 0;
                    $new_data['goods_freight'] = 0;
                    $new_data['goods_vat'] = 0;
                    $new_data['goods_total'] = 0;
                    $new_data['ifzengpin'] = true;

                    //计算赠送数量，有就赠，赠完为止
                    if ($goods_storage_quantity[$goods_info['goods_id']] - $goods_info['goods_num'] >= 0) {
                        $goods_buy_quantity[$goods_info['goods_id']] += $goods_info['goods_num'];
                        $goods_storage_quantity[$goods_info['goods_id']] -= $goods_info['goods_num'];
                        $new_data['goods_num'] = $goods_info['goods_num'];
                    } else {
                        $new_data['goods_num'] = $goods_storage_quantity[$goods_info['goods_id']];
                        $goods_buy_quantity[$goods_info['goods_id']] += $goods_storage_quantity[$goods_info['goods_id']];
                        $goods_storage_quantity[$goods_info['goods_id']] = 0;
                    }
                    if (array_key_exists($goods_info['goods_id'],$zp_list)) {
                        $zp_list[$goods_info['goods_id']]['goods_num'] += $new_data['goods_num'];
                    } else {
                        $zp_list[$goods_info['goods_id']] = $new_data;
                    }
                }
                sort($zp_list);
                $store_cart_list[$store_id] = array_merge($store_cart_list[$store_id],$zp_list);
                if ($gift_desc != '') {
                    if (is_string($store_mansong_rule_list[$store_id]['desc'])) {
                        $store_mansong_rule_list[$store_id]['desc'] .= $gift_desc;
                        $store_mansong_rule_list[$store_id]['desc'] = trim($store_mansong_rule_list[$store_id]['desc'],'，');
                    }
                }
            }
        }
        return array($store_cart_list,$goods_buy_quantity,$store_mansong_rule_list);
    }

    /**
     * 充值卡支付,依次循环每个订单
     * 如果充值卡足够就单独支付了该订单，如果不足就暂时冻结，等API支付成功了再彻底扣除
     */
    public function rcbPay($order_list, $input, $buyer_info) {
        $member_id = $buyer_info['member_id'];
        $member_name = $buyer_info['member_name'];
    
        $available_rcb_amount = floatval(ncPriceFormat($buyer_info['available_rc_balance']));
        if ($available_rcb_amount <= 0) return $order_list;
    
        $model_order = Model('order');
        $model_pd = Model('predeposit');
        foreach ($order_list as $key => $order_info) {
    
            //货到付款的订单、已经支付的订单跳过
            if ($order_info['payment_code'] == 'offline') continue;
    
            $order_amount = floatval($order_info['order_amount']);
            $data_pd = array();
            $data_pd['member_id'] = $member_id;
            $data_pd['member_name'] = $member_name;
            $data_pd['amount'] = $order_info['order_amount'];
            $data_pd['order_sn'] = $order_info['order_sn'];
    
            if ($available_rcb_amount >= $order_amount) {
                //立即支付，订单支付完成
                $model_pd->changeRcb('order_pay',$data_pd);
                $available_rcb_amount -= $order_amount;
                $available_rcb_amount = floatval(ncPriceFormat($available_rcb_amount));
                //记录订单日志(已付款)
                $data = array();
                $data['order_id'] = $order_info['order_id'];
                $data['log_role'] = 'buyer';
                $data['log_msg'] = '支付订单';
                $data['log_orderstate'] = ORDER_STATE_PAY;
                $insert = $model_order->addOrderLog($data);
                if (!$insert) {
                    throw new Exception('记录订单充值卡支付日志出现错误');
                }
    
                //订单状态 置为已支付
                $data_order = array();
                $order_list[$key]['order_state'] = $data_order['order_state'] = ORDER_STATE_PAY;
                $order_list[$key]['payment_time'] = $data_order['payment_time'] = TIMESTAMP;
                $order_list[$key]['payment_code'] = $data_order['payment_code'] = 'predeposit';
                if ($order_info['order_type'] == 2) {
                    $order_list[$key]['rcb_amount'] = $data_order['rcb_amount'] = ncPriceFormat(floatval($order_info['original_rcb_amount']) + $order_amount);
                } else {
                    $order_list[$key]['rcb_amount'] = $data_order['rcb_amount'] = $order_amount;
                }
                $result = $model_order->editOrder($data_order,array('order_id'=>$order_info['order_id']));
                if (!$result) {
                    throw new Exception('订单更新失败');
                }
    
                //非预定订单下单或预定订单全部付款完成
                if ($order_info['order_type'] != 2 || $order_info['if_send_store_msg_pay_success']) {
                    // 发送商家提醒
                    $param = array();
                    $param['code'] = 'new_order';
                    $param['store_id'] = $order_info['store_id'];
                    $param['param'] = array(
                            'order_sn' => $order_info['order_sn']
                    );
                    QueueClient::push('sendStoreMsg', $param);
                    //门店自提发送提货码
                    if ($order_info['order_type'] == 3) {
                        $_code = rand(100000,999999);
                        $result = $model_order->editOrder(array('chain_code'=>$_code),array('order_id'=>$order_info['order_id']));
                        if (!$result) {
                            throw new Exception('门店自提订单更新提货码失败');
                        }
                        $param = array();
                        $param['chain_code'] = $_code;
                        $param['order_sn'] = $order_info['order_sn'];
                        $param['buyer_phone'] = $order_info['buyer_phone'];
                        QueueClient::push('sendChainCode', $param);
                    }
                }
            } else {
                //暂冻结充值卡,后面还需要 API彻底完成支付
                if ($available_rcb_amount > 0) {
                    $data_pd['amount'] = $available_rcb_amount;
                    if ($order_info['order_type'] != 2) {
                        $model_pd->changeRcb('order_freeze',$data_pd);
                    } else {
                        //预定订单没有冻结，是直接扣除
                        $model_pd->changeRcb('order_pay',$data_pd);
                    }
    
                    //支付金额保存到订单
                    $data_order = array();
                    if ($order_info['order_type'] == 2) {
                        $order_list[$key]['rcb_amount'] = $data_order['rcb_amount'] = ncPriceFormat(floatval($order_info['original_rcb_amount']) + $available_rcb_amount);
                    } else {
                        $order_list[$key]['rcb_amount'] = $data_order['rcb_amount'] = $available_rcb_amount;
                    }
                    $result = $model_order->editOrder($data_order,array('order_id'=>$order_info['order_id']));
                    $available_rcb_amount = 0;
                    if (!$result) {
                        throw new Exception('订单更新失败');
                    }
                }
            }
        }
        return $order_list;
    }
    
    /**
     * 预存款支付,依次循环每个订单
     * 如果预存款足够就单独支付了该订单，如果不足就暂时冻结，等API支付成功了再彻底扣除
     */
    public function pdPay($order_list, $input, $buyer_info) {
        $member_id = $buyer_info['member_id'];
        $member_name = $buyer_info['member_name'];
    
        $available_pd_amount = floatval(ncPriceFormat($buyer_info['available_predeposit']));
        if ($available_pd_amount <= 0) return $order_list;
    
        $model_order = Model('order');
        $model_pd = Model('predeposit');
        foreach ($order_list as $key => $order_info) {
    
            //货到付款的订单、已经充值卡支付或支付的订单跳过
            if ($order_info['payment_code'] == 'offline') continue;
    
            $order_amount = floatval($order_info['order_amount']) - floatval($order_info['rcb_amount']);
            $order_amount = floatval(ncPriceFormat($order_amount));
            $data_pd = array();
            $data_pd['member_id'] = $member_id;
            $data_pd['member_name'] = $member_name;
            $data_pd['amount'] = $order_amount;
            $data_pd['order_sn'] = $order_info['order_sn'];
    
            if ($available_pd_amount >= $order_amount) {
                //预存款立即支付，订单支付完成
                $model_pd->changePd('order_pay',$data_pd);
                $available_pd_amount -= $order_amount;
                $available_pd_amount = floatval(ncPriceFormat($available_pd_amount));
    
                //支付被冻结的充值卡(预定订单没有冻结，是直接扣除，除外)
                $rcb_amount = floatval($order_info['rcb_amount']);
                if ($rcb_amount > 0 && $order_info['order_type'] != 2) {
                    $data_pd = array();
                    $data_pd['member_id'] = $member_id;
                    $data_pd['member_name'] = $member_name;
                    $data_pd['amount'] = $rcb_amount;
                    $data_pd['order_sn'] = $order_info['order_sn'];
                    $model_pd->changeRcb('order_comb_pay',$data_pd);
                }
    
                //记录订单日志(已付款)
                $data = array();
                $data['order_id'] = $order_info['order_id'];
                $data['log_role'] = 'buyer';
                $data['log_msg'] = '支付订单';
                $data['log_orderstate'] = ORDER_STATE_PAY;
                $insert = $model_order->addOrderLog($data);
                if (!$insert) {
                    throw new Exception('记录订单预存款支付日志出现错误');
                }
    
                //订单状态 置为已支付
                $data_order = array();
                $order_list[$key]['order_state'] = $data_order['order_state'] = ORDER_STATE_PAY;
                $order_list[$key]['payment_time'] = $data_order['payment_time'] = TIMESTAMP;
                $order_list[$key]['payment_code'] = $data_order['payment_code'] = 'predeposit';
                if ($order_info['order_type'] == 2) {
                    $order_list[$key]['pd_amount'] = $data_order['pd_amount'] = ncPriceFormat(floatval($order_info['original_pd_amount']) + $order_amount);
                } else {
                    $order_list[$key]['pd_amount'] = $data_order['pd_amount'] = $order_amount;
                }
                $result = $model_order->editOrder($data_order,array('order_id'=>$order_info['order_id']));
                if (!$result) {
                    throw new Exception('订单更新失败');
                }
    
                //非预定订单下单或预定订单全部付款完成
                if ($order_info['order_type'] != 2 || $order_info['if_send_store_msg_pay_success']) {
                    // 发送商家提醒
                    $param = array();
                    $param['code'] = 'new_order';
                    $param['store_id'] = $order_info['store_id'];
                    $param['param'] = array(
                            'order_sn' => $order_info['order_sn']
                    );
                    QueueClient::push('sendStoreMsg', $param);
    
                    //门店自提发送提货码
                    if ($order_info['order_type'] == 3) {
                        $_code = rand(100000,999999);
                        $result = $model_order->editOrder(array('chain_code'=>$_code),array('order_id'=>$order_info['order_id']));
                        if (!$result) {
                            throw new Exception('门店自提订单更新提货码失败');
                        }
                        $param = array();
                        $param['chain_code'] = $_code;
                        $param['order_sn'] = $order_info['order_sn'];
                        $param['buyer_phone'] = $order_info['buyer_phone'];
                        QueueClient::push('sendChainCode', $param);
                    }
                }
            } else {
                //暂冻结预存款,后面还需要 API彻底完成支付
                if ($available_pd_amount > 0) {
                    $data_pd['amount'] = $available_pd_amount;
                    if ($order_info['order_type'] != 2) {
                        $model_pd->changePd('order_freeze',$data_pd);
                    } else {
                        //预定订单没有冻结，是直接扣除
                        $model_pd->changePd('order_pay',$data_pd);
                    }
                    //预存款支付金额保存到订单
                    $data_order = array();
                    if ($order_info['order_type'] == 2) {
                        $order_list[$key]['pd_amount'] = $data_order['pd_amount'] = ncPriceFormat(floatval($order_info['original_pd_amount']) + $available_pd_amount);
                    } else {
                        $order_list[$key]['pd_amount'] = $data_order['pd_amount'] = $available_pd_amount;
                    }
                    $result = $model_order->editOrder($data_order,array('order_id'=>$order_info['order_id']));
                    $available_pd_amount = 0;
                    if (!$result) {
                        throw new Exception('订单更新失败');
                    }
                }
            }
        }
        return $order_list;
    }
    /**
     * 生成支付单编号(两位随机 + 从2000-01-01 00:00:00 到现在的秒数+微秒+会员ID%1000)，该值会传给第三方支付接口
     * 长度 =2位 + 10位 + 3位 + 3位  = 18位
     * 1000个会员同一微秒提订单，重复机率为1/100
     * @return string
     */
    public function makePaySn($member_id) {
        return mt_rand(10,99)
              . sprintf('%010d',time() - 946656000)
              . sprintf('%03d', (float) microtime() * 1000)
              . sprintf('%03d', (int) $member_id % 1000);
    }

    /**
     * 订单编号生成规则，n(n>=1)个订单表对应一个支付表，
     * 生成订单编号(年取1位 + $pay_id取13位 + 第N个子订单取2位)
     * 1000个会员同一微秒提订单，重复机率为1/100
     * @param $pay_id 支付表自增ID
     * @return string
     */
    public function makeOrderSn($pay_id) {
        //记录生成子订单的个数，如果生成多个子订单，该值会累加
        static $num;
        if (empty($num)) {
            $num = 1;
        } else {
            $num ++;
        }
        return (date('y',time()) % 9+1) . sprintf('%013d', $pay_id) . sprintf('%02d', $num);
    }

    /**
     * 更新库存与销量
     *
     * @param array $buy_items 商品ID => 购买数量
     */
    public function editGoodsNum($buy_items) {
        foreach ($buy_items as $goods_id => $buy_num) {
            $data = array('goods_storage'=>array('exp','goods_storage-'.$buy_num),'goods_salenum'=>array('exp','goods_salenum+'.$buy_num));
            $result = Model('goods')->editGoods($data,array('goods_id'=>$goods_id));
            if (!$result) throw new Exception(L('cart_step2_submit_fail'));
        }
    }

    /**
     * 取得店铺级活动 - 每个店铺可用的满即送活动规则列表
     * @param unknown $store_id_array 店铺ID数组
     */
    public function getMansongRuleList($store_id_array) {
        if (!C('promotion_allow') || empty($store_id_array) || !is_array($store_id_array)) return array();
        $model_mansong = Model('p_mansong');
        $mansong_rule_list = array();
        foreach ($store_id_array as $store_id) {
            $store_mansong_rule = $model_mansong->getMansongInfoByStoreID($store_id);
            if (!empty($store_mansong_rule['rules']) && is_array($store_mansong_rule['rules'])) {
                foreach ($store_mansong_rule['rules'] as $rule_info) {
                    //如果减金额 或 有赠品(在售且有库存)
                    if (!empty($rule_info['discount']) || (!empty($rule_info['mansong_goods_name']) && !empty($rule_info['goods_storage']))) {
                        $_tmp = $this->_parseMansongRuleDesc($rule_info);
                        if ($_tmp) {
                            $mansong_rule_list[$store_id][] = $_tmp;
                        }
                    }
                }
            }
        }
        return $mansong_rule_list;
    }

    /**
     * 取得哪些店铺有满免运费活动
     * @param array $store_id_array 店铺ID数组
     * @return array
     */
    public function getFreeFreightActiveList($store_id_array) {
        if (empty($store_id_array) || !is_array($store_id_array)) return array();

        //定义返回数组
        $store_free_freight_active = array();

        //如果商品金额未达到免运费设置下线，则需要计算运费
        $condition = array('store_id' => array('in',$store_id_array));
        $store_list = Model('store')->getStoreOnlineList($condition,null,'','store_id,store_free_price');
        foreach ($store_list as $store_info) {
            $limit_price = floatval($store_info['store_free_price']);
            if ($limit_price > 0) {
                $store_free_freight_active[$store_info['store_id']] = sprintf('满%s免运费',$limit_price);
            }
        }
        return $store_free_freight_active;
    }

    /**
     * 取得收货人地址信息
     * @param array $address_info
     * @return array
     */
    public function getReciverAddr($address_info = array()) {
        if (intval($address_info['dlyp_id'])) {
            $reciver_info['phone'] = trim($address_info['dlyp_mobile'].($address_info['dlyp_telephony'] ? ','.$address_info['dlyp_telephony'] : null),',');
            $reciver_info['tel_phone'] = $address_info['dlyp_telephony'];
            $reciver_info['mob_phone'] = $address_info['dlyp_mobile'];
            $reciver_info['address'] = $address_info['dlyp_area_info'].' '.$address_info['dlyp_address'];
            $reciver_info['area'] = $address_info['dlyp_area_info'];
            $reciver_info['street'] = $address_info['dlyp_address'];
            $reciver_info['dlyp'] = 1;
            $reciver_str = serialize($reciver_info);
            $reciver_name = $address_info['dlyp_address_name'];
        } else {
            $reciver_info['phone'] = trim($address_info['mob_phone'].($address_info['tel_phone'] ? ','.$address_info['tel_phone'] : null),',');
            $reciver_info['mob_phone'] = $address_info['mob_phone'];
            $reciver_info['tel_phone'] = $address_info['tel_phone'];
            $reciver_info['address'] = $address_info['area_info'].' '.$address_info['address'];
            $reciver_info['area'] = $address_info['area_info'];
            $reciver_info['street'] = $address_info['address'];
            $reciver_str = serialize($reciver_info);
            $reciver_name = $address_info['true_name'];
        }
        return array($reciver_str, $reciver_name, $reciver_info['mob_phone']);
    }

    /**
     * 整理发票信息
     * @param array $invoice_info 发票信息数组
     * @return string
     */
    public function createInvoiceData($invoice_info){
        //发票信息
        $inv = array();
        if ($invoice_info['inv_state'] == 1) {
            $inv['类型'] = '普通发票 ';
            $inv['抬头'] = $invoice_info['inv_title_select'] == 'person' ? '个人' : $invoice_info['inv_title'];
            $inv['内容'] = $invoice_info['inv_content'];
        } elseif (!empty($invoice_info)) {
            $inv['单位名称'] = $invoice_info['inv_company'];
            $inv['纳税人识别号'] = $invoice_info['inv_code'];
            $inv['注册地址'] = $invoice_info['inv_reg_addr'];
            $inv['注册电话'] = $invoice_info['inv_reg_phone'];
            $inv['开户银行'] = $invoice_info['inv_reg_bname'];
            $inv['银行账户'] = $invoice_info['inv_reg_baccount'];
            $inv['收票人姓名'] = $invoice_info['inv_rec_name'];
            $inv['收票人手机号'] = $invoice_info['inv_rec_mobphone'];
            $inv['收票人省份'] = $invoice_info['inv_rec_province'];
            $inv['送票地址'] = $invoice_info['inv_goto_addr'];
        }
        return !empty($inv) ? serialize($inv) : serialize(array());
    }

    /**
     * 计算本次下单中每个店铺订单是货到付款还是线上支付,店铺ID=>付款方式[online在线支付offline货到付款]
     * @param array $store_id_array 店铺ID数组
     * @param boolean $if_offpay 是否支持货到付款 true/false
     * @param string $pay_name 付款方式 online/offline
     * @return array
     */
    public function getStorePayTypeList($store_id_array, $if_offpay, $pay_name) {
        $store_pay_type_list = array();
        if ($_POST['pay_name'] == 'online') {
            foreach ($store_id_array as $store_id) {
                $store_pay_type_list[$store_id] = 'online';
            }
        } else {
            $offline_pay = Model('payment')->getPaymentOpenInfo(array('payment_code'=>'offline'));
            if ($offline_pay) {
                //下单里包括平台自营商品并且平台已开启货到付款
                $offline_store_id_array = model('store')->getOwnShopIds();
                foreach ($store_id_array as $store_id) {
                    if (in_array($store_id,$offline_store_id_array)) {
                        $store_pay_type_list[$store_id] = 'offline';
                    } else {
                        $store_pay_type_list[$store_id] = 'online';
                    }
                }
            }
        }
        return $store_pay_type_list;
    }

    /**
     * 特殊订单站内支付处理
     */
    public function extendInPay($order_list) {
        //处理预定订单
        if ($order_list[0]['order_type'] == 2) {
            $model_order_book = Model('order_book');
            $order_info = $order_list[0];
            $data = array();
            if (!empty($order_info['rcb_amount'])) {
                $data['book_rcb_amount'] = $order_info['rcb_amount'] - $order_info['original_rcb_amount'];
            }
            if (!empty($order_info['pd_amount'])) {
                $data['book_pd_amount'] = $order_info['pd_amount'] - $order_info['original_pd_amount'];
            }

            //如果未使用站内余额，返回
            if (empty($order_info['rcb_amount']) && empty($order_info['pd_amount'])) {
                return callback(true);
            }

            if ($order_info['order_state'] == ORDER_STATE_PAY) {
                //使用站内余额即可全部支付，说明支付完成，记录支付时间和支付方式
                $data['book_pay_time'] = TIMESTAMP;
                $data['book_pay_name'] = '站内余额支付';
                //更新预定人数
                $order_goods_info = Model('order')->getOrderGoodsInfo(array('order_id'=>$order_info['order_id']),'goods_id','rec_id asc');
                $update = Model('goods')->editGoods(array('book_buyers'=>array('exp','book_buyers+1')),array('goods_id'=>$order_goods_info['goods_id']));
                if (!$update) {
                    throw new Exception('更新商品预定人数失败');
                }
            }
            $condition = array();
            $condition['book_order_id'] = $order_info['order_id'];
            if (empty($order_info['book_list'][0]['book_pay_time'])) {
                //付定金或全款
                $condition['book_id'] = $order_info['book_list'][0]['book_id'];
            } else {
                //付尾款
                $condition['book_id'] = $order_info['book_list'][1]['book_id'];
            }
            $update = $model_order_book->editOrderBook($data,$condition);
            if (!$update) {
                throw new Exception('更新站内余额失败');
            }
        }
        return callback(true);
    }

    /**
     * 直接购买时返回最新的在售商品信息（需要在售）
     *
     * @param int $goods_id 所购商品ID
     * @param int $quantity 购买数量
     * @return array
     */
    private function _getGoodsOnlineInfo($goods_id,$quantity) {
        $model_goods = Model('goods');
        //取目前在售商品
        $goods_info = $model_goods->getGoodsOnlineInfoAndPromotionById($goods_id);
        if(empty($goods_info)){
            return null;
        }
        //处理商品消费者保障服务信息
        $goods_list = $model_goods->getGoodsContract(array(0=>$goods_info));
        $goods_info = $goods_list[0];

        $new_array = array();
        $new_array['goods_num'] = $goods_info['is_fcode'] ? 1 : $quantity;
        $new_array['goods_id'] = $goods_id;
        $new_array['goods_commonid'] = $goods_info['goods_commonid'];
        $new_array['gc_id'] = $goods_info['gc_id'];
        $new_array['store_id'] = $goods_info['store_id'];
        $new_array['goods_name'] = $goods_info['goods_name'];
        $new_array['goods_price'] = $goods_info['goods_price'];
        $new_array['store_name'] = $goods_info['store_name'];
        $new_array['goods_image'] = $goods_info['goods_image'];
        $new_array['transport_id'] = $goods_info['transport_id'];
        $new_array['goods_freight'] = $goods_info['goods_freight'];
        $new_array['goods_vat'] = $goods_info['goods_vat'];
        $new_array['goods_storage'] = $goods_info['goods_storage'];
        $new_array['goods_storage_alarm'] = $goods_info['goods_storage_alarm'];
        $new_array['is_fcode'] = $goods_info['is_fcode'];
        $new_array['have_gift'] = $goods_info['have_gift'];
        $new_array['state'] = true;
        $new_array['storage_state'] = intval($goods_info['goods_storage']) < intval($quantity) ? false : true;
        $new_array['groupbuy_info'] = $goods_info['groupbuy_info'];
        $new_array['xianshi_info'] = $goods_info['xianshi_info'];
        $new_array['is_chain'] = $goods_info['is_chain'];

        //预定信息
        $new_array['is_book'] = $goods_info['is_book'];
        $new_array['book_down_payment'] = $goods_info['book_down_payment'];
        $new_array['book_final_payment'] = $goods_info['book_final_payment'];
        $new_array['book_down_time'] = $goods_info['book_down_time'];

        //填充必要下标，方便后面统一使用购物车方法与模板
        //cart_id=goods_id,优惠套装目前只能进购物车,不能立即购买
        $new_array['cart_id'] = $goods_id;
        $new_array['bl_id'] = 0;

        //规格
        $_tmp_name = unserialize($goods_info['spec_name']);
        $_tmp_value = unserialize($goods_info['goods_spec']);
        if (is_array($_tmp_name) && is_array($_tmp_value)) {
            $_tmp_name = array_values($_tmp_name);$_tmp_value = array_values($_tmp_value);
            foreach ($_tmp_name as $sk => $sv) {
                $new_array['goods_spec'] .= $sv.'：'.$_tmp_value[$sk].'，';
            }
            $new_array['goods_spec'] = rtrim($new_array['goods_spec'],'，');
        }
        if (array_key_exists('sole_info', $goods_info)) {
            $new_array['sole_info'] = $goods_info['sole_info'];
        }
        $new_array['contractlist'] = $goods_info['contractlist'];
        return $new_array;
    }

    /**
     * 直接购买时，判断商品是不是正在团购中，如果是，按团购价格计算，购买数量若超过团购规定的上限，则按团购上限计算
     * @param array $goods_info
     */
    public function getGroupbuyInfo(& $goods_info = array()) {
        if (!C('groupbuy_allow') || empty($goods_info['groupbuy_info']) || $goods_info['ifsole']) return ;
        $groupbuy_info = $goods_info['groupbuy_info'];
        $goods_info['goods_price'] = $groupbuy_info['groupbuy_price'];
        if ($groupbuy_info['upper_limit'] && $goods_info['goods_num'] > $groupbuy_info['upper_limit']) {
            $goods_info['goods_num'] = $groupbuy_info['upper_limit'];
        }
        $goods_info['upper_limit'] = $groupbuy_info['upper_limit'];
        $goods_info['promotions_id'] = $goods_info['groupbuy_id'] = $groupbuy_info['groupbuy_id'];
        $goods_info['ifgroupbuy'] = true;
    }

    /**
     * 手机专享价
     * @param unknown $goods_info
     */
    public function getMbSoleInfo(& $goods_info = array()) {
        if (!C('promotion_allow') || empty($goods_info['sole_info'])) return ;
        $sole_info = $goods_info['sole_info'];
        $goods_info['goods_price'] = $sole_info['sole_price'];
        $goods_info['ifsole'] = true;
    }

    /**
     * 取得商品预定信息
     * @param unknown $goods_info
     */
    public function getBookInfo(& $goods_info = array()) {
        if (empty($goods_info['is_book'])) return ;
        if (!C('promotion_allow') || $goods_info['book_down_time'] < TIMESTAMP) {
            $goods_info['is_book'] = 0;
            return ;
        }
        $goods_info['goods_price'] = $goods_info['book_down_payment']+$goods_info['book_final_payment'];
    }

    /**
     * 取得某商品赠品列表信息
     * @param array $goods_info
     */
    private function _getGoodsGiftList( & $goods_info) {
        if (!$goods_info['have_gift']) return ;
        $gift_list = Model('goods_gift')->getGoodsGiftListByGoodsId($goods_info['goods_id']);
        //取得赠品当前信息，如果未在售踢除，如果在售取出库存
        if (empty($gift_list)) return array();
        $model_goods = Model('goods');
        foreach ($gift_list as $k => $v) {
            $goods_online_info = $model_goods->getGoodsOnlineInfoByID($v['gift_goodsid'],'goods_storage');
            if (empty($goods_online_info)) {
                unset($gift_list[$k]);
            } else {
                $gift_list[$k]['goods_storage'] = $goods_online_info['goods_storage'];
            }
        }
        $goods_info['gift_list'] = $gift_list;
    }


    /**
     * 取商品最新的在售信息
     * @param unknown $cart_list
     * @return array
     */
    private function _getOnlineCartList($cart_list) {
        if (empty($cart_list) || !is_array($cart_list)) return $cart_list;
        //验证商品是否有效
        $goods_id_array = array();
        foreach ($cart_list as $key => $cart_info) {
            if (!intval($cart_info['bl_id'])) {
                $goods_id_array[] = $cart_info['goods_id'];
            }
        }
        $model_goods = Model('goods');
        $goods_online_list = $model_goods->getGoodsOnlineListAndPromotionByIdArray($goods_id_array);
        //处理商品消费者保障服务信息
        $goods_online_list = $model_goods->getGoodsContract($goods_online_list);

        $goods_online_array = array();
        foreach ($goods_online_list as $goods) {
            $goods_online_array[$goods['goods_id']] = $goods;
        }
        foreach ((array)$cart_list as $key => $cart_info) {
            if (intval($cart_info['bl_id'])) continue;
            $cart_list[$key]['state'] = true;
            $cart_list[$key]['storage_state'] = true;
            if (in_array($cart_info['goods_id'],array_keys($goods_online_array))) {
                $goods_online_info = $goods_online_array[$cart_info['goods_id']];
                $cart_list[$key]['goods_commonid'] = $goods_online_info['goods_commonid'];
                $cart_list[$key]['goods_name'] = $goods_online_info['goods_name'];
                $cart_list[$key]['gc_id'] = $goods_online_info['gc_id'];
                $cart_list[$key]['goods_image'] = $goods_online_info['goods_image'];
                $cart_list[$key]['goods_price'] = $goods_online_info['goods_price'];
                $cart_list[$key]['transport_id'] = $goods_online_info['transport_id'];
                $cart_list[$key]['goods_freight'] = $goods_online_info['goods_freight'];
                $cart_list[$key]['goods_vat'] = $goods_online_info['goods_vat'];
                $cart_list[$key]['goods_storage'] = $goods_online_info['goods_storage'];
                $cart_list[$key]['goods_storage_alarm'] = $goods_online_info['goods_storage_alarm'];
                $cart_list[$key]['is_fcode'] = $goods_online_info['is_fcode'];
                $cart_list[$key]['have_gift'] = $goods_online_info['have_gift'];
                if ($cart_info['goods_num'] > $goods_online_info['goods_storage']) {
                    $cart_list[$key]['storage_state'] = false;
                }
                $cart_list[$key]['groupbuy_info'] = $goods_online_info['groupbuy_info'];
                $cart_list[$key]['xianshi_info'] = $goods_online_info['xianshi_info'];

                //预定信息
                $cart_list[$key]['is_book'] = $goods_online_info['is_book'];
                $cart_list[$key]['book_down_payment'] = $goods_online_info['book_down_payment'];
                $cart_list[$key]['book_final_payment'] = $goods_online_info['book_final_payment'];
                $cart_list[$key]['book_down_time'] = $goods_online_info['book_down_time'];
                $cart_list[$key]['is_chain'] = $goods_online_info['is_chain'];

                //规格
                $_tmp_name = unserialize($goods_online_info['spec_name']);
                $_tmp_value = unserialize($goods_online_info['goods_spec']);
                if (is_array($_tmp_name) && is_array($_tmp_value)) {
                    $_tmp_name = array_values($_tmp_name);$_tmp_value = array_values($_tmp_value);
                    foreach ($_tmp_name as $sk => $sv) {
                        $cart_list[$key]['goods_spec'] .= $sv.'：'.$_tmp_value[$sk].'，';
                    }
                    $cart_list[$key]['goods_spec'] = rtrim($cart_list[$key]['goods_spec'],'，');
                }
                if (array_key_exists('sole_info', $goods_online_info)) {
                    $cart_list[$key]['sole_info'] = $goods_online_info['sole_info'];
                }
                //消费者保障服务
                $cart_list[$key]['contractlist'] = $goods_online_info['contractlist'];
            } else {
                //如果商品下架
                $cart_list[$key]['state'] = false;
                $cart_list[$key]['storage_state'] = false;
            }
        }

        return $cart_list;
    }

    /**
     *  直接购买时，判断商品是不是正在团购中，如果是，按团购价格计算，购买数量若超过团购规定的上限，则按团购上限计算
     * @param array $cart_list
     */
    public function getGroupbuyCartList(& $cart_list) {
        if (!C('groupbuy_allow') || empty($cart_list)) return ;
        foreach ($cart_list as $key => $cart_info) {
            if (intval($cart_info['bl_id']) || empty($cart_info['groupbuy_info']) || $cart_info['ifsole']) continue;
            $this->getGroupbuyInfo($cart_info);
            $cart_list[$key] = $cart_info;
        }
    }

    /**
     * 手机专享价
     * @param unknown $cart_list
     */
    public function getMbSoleCartList(& $cart_list) {
        foreach ($cart_list as $key => $cart_info) {
            if (intval($cart_info['bl_id']) || empty($cart_info['sole_info'])) continue;
            $this->getMbSoleInfo($cart_info);
            $cart_list[$key] = $cart_info;
        }
    }

    /**
     * 批量判断购物车内的商品是不是限时折扣中，如果购买数量若>=规定的下限，按折扣价格计算,否则按原价计算
     * 并标识该商品为限时商品
     * @param array $cart_list
     */
    public function getXianshiCartList(& $cart_list) {
        if (!C('promotion_allow') || empty($cart_list)) return ;
        foreach ($cart_list as $key => $cart_info) {
            if (intval($cart_info['bl_id']) || empty($cart_info['xianshi_info']) || !empty($cart_info['groupbuy_info']) || $cart_info['ifsole']) continue;
            $this->getXianshiInfo($cart_info, $cart_info['goods_num']);
            $cart_list[$key] = $cart_info;
        }
    }

    /**
     * 取得购物车商品的赠品列表[商品级赠品]
     *
     * @param array $cart_list
     */
    private function _getGiftCartList(& $cart_list) {
        foreach ($cart_list as $k => $cart_info) {
            if ($cart_info['bl_id']) continue;
            $this->_getGoodsGiftList($cart_info);
            $cart_list[$k] = $cart_info;
        }
    }

    /**
     * 如果购物车内的商品正在预定中，则置为不可购买状态，预定只能单独下单购买
     * @param unknown $cart_list
     */
    private function _getBookCartList(& $cart_list) {
        if (!C('promotion_allow') || empty($cart_list)) return ;
        foreach ($cart_list as $key => $cart_info) {
            if (intval($cart_info['bl_id'])) continue;
            $this->getBookInfo($cart_info);
            if ($cart_info['is_book']) $cart_info['state'] = false;
            $cart_list[$key] = $cart_info;
        }
    }

    /**
     * 取得购买车内组合销售信息以及包含的商品及有效状态
     * @param array $cart_list
     */
    private function _getBundlingCartList(& $cart_list) {
        if (!C('promotion_allow') || empty($cart_list)) return ;
        $model_bl = Model('p_bundling');
        $model_goods = Model('goods');
        foreach ($cart_list as $key => $cart_info) {
            if (!intval($cart_info['bl_id'])) continue;
            $cart_list[$key]['state'] = true;
            $cart_list[$key]['storage_state'] = true;
            $bl_info = $model_bl->getBundlingInfo(array('bl_id'=>$cart_info['bl_id']));

            //标志优惠套装是否处于有效状态
            if (empty($bl_info) || !intval($bl_info['bl_state'])) {
                $cart_list[$key]['state'] = false;
            }

            //取得优惠套装商品列表
            $cart_list[$key]['bl_goods_list'] = $model_bl->getBundlingGoodsList(array('bl_id'=>$cart_info['bl_id']));

            //取最新在售商品信息
            $goods_id_array = array();
            foreach ($cart_list[$key]['bl_goods_list'] as $goods_info) {
                $goods_id_array[] = $goods_info['goods_id'];
            }
            $goods_list = $model_goods->getGoodsOnlineListAndPromotionByIdArray($goods_id_array);
            //处理商品消费者保障服务信息
            $goods_list = $model_goods->getGoodsContract($goods_list);

            $goods_online_list = array();
            foreach ($goods_list as $goods_info) {
                $goods_online_list[$goods_info['goods_id']] = $goods_info;
            }
            unset($goods_list);

            //使用最新的商品名称、图片,如果一旦有商品下架，则整个套装置置为无效状态
            $total_down_price = 0;

            //优惠套装只有全部商品都支持门店自提时，整个套装才支持门店自提
            $cart_list[$key]['is_chain'] = true;

            foreach ($cart_list[$key]['bl_goods_list'] as $k => $goods_info) {
                if (array_key_exists($goods_info['goods_id'],$goods_online_list)) {
                    $goods_online_info = $goods_online_list[$goods_info['goods_id']];
                    //如果库存不足，标识false
                    if ($cart_info['goods_num'] > $goods_online_info['goods_storage']) {
                        $cart_list[$key]['storage_state'] = false;
                    }
                    $cart_list[$key]['bl_goods_list'][$k]['goods_id'] = $goods_online_info['goods_id'];
                    $cart_list[$key]['bl_goods_list'][$k]['goods_commonid'] = $goods_online_info['goods_commonid'];
                    $cart_list[$key]['bl_goods_list'][$k]['store_id'] = $goods_online_info['store_id'];
                    $cart_list[$key]['bl_goods_list'][$k]['goods_name'] = $goods_online_info['goods_name'];
                    $cart_list[$key]['bl_goods_list'][$k]['goods_image'] = $goods_online_info['goods_image'];
                    $cart_list[$key]['bl_goods_list'][$k]['goods_storage'] = $goods_online_info['goods_storage'];
                    $cart_list[$key]['bl_goods_list'][$k]['goods_storage_alarm'] = $goods_online_info['goods_storage_alarm'];
                    $cart_list[$key]['bl_goods_list'][$k]['gc_id'] = $goods_online_info['gc_id'];
                    $cart_list[$key]['bl_goods_list'][$k]['is_chain'] = $goods_online_info['is_chain'];

                    //规格
                    $_tmp_name = unserialize($goods_online_info['spec_name']);
                    $_tmp_value = unserialize($goods_online_info['goods_spec']);
                    if (is_array($_tmp_name) && is_array($_tmp_value)) {
                        $_tmp_name = array_values($_tmp_name);$_tmp_value = array_values($_tmp_value);
                        foreach ($_tmp_name as $sk => $sv) {
                            $cart_list[$key]['bl_goods_list'][$k]['goods_spec'] .= $sv.'：'.$_tmp_value[$sk].'，';
                        }
                        $cart_list[$key]['bl_goods_list'][$k]['goods_spec'] = rtrim($cart_list[$key]['bl_goods_list'][$k]['goods_spec'],'，');
                    }

                    if (!$goods_online_info['is_chain']) {
                        $cart_list[$key]['is_chain'] = false;
                    }
                    //处理消费者保障服务
                    $cart_list[$key]['bl_goods_list'][$k]['contractlist'] = $goods_online_info['contractlist'];
                    //每个商品直降多少
                    $total_down_price += $cart_list[$key]['bl_goods_list'][$k]['down_price'] = ncPriceFormat($goods_online_info['goods_price'] - $goods_info['bl_goods_price']);
                } else {
                    //商品已经下架
                    $cart_list[$key]['state'] = false;
                    $cart_list[$key]['storage_state'] = false;
                }
            }
            $cart_list[$key]['down_price'] = ncPriceFormat($total_down_price);
        }
    }

    /**
     * 取得每种商品的库存
     * @param array $store_cart_list 购买列表
     * @param array $store_premiums_list 赠品列表
     * @return array 商品ID=>库存
     */
    private function _getEachGoodsStorageQuantity($store_cart_list, $store_premiums_list = array()) {
        if(empty($store_cart_list) || !is_array($store_cart_list)) return array();
        $goods_storage_quangity = array();
        foreach ($store_cart_list as $store_cart) {
            foreach ($store_cart as $cart_info) {
                if (!intval($cart_info['bl_id'])) {
                    //正常商品
                    $goods_storage_quangity[$cart_info['goods_id']] = $cart_info['goods_storage'];
                } elseif (!empty($cart_info['bl_goods_list']) && is_array($cart_info['bl_goods_list'])) {
                    //优惠套装
                    foreach ($cart_info['bl_goods_list'] as $goods_info) {
                        $goods_storage_quangity[$goods_info['goods_id']] = $goods_info['goods_storage'];
                    }
                }
            }
        }
        //取得赠品商品的库存
        if (is_array($store_premiums_list)) {
            foreach ($store_premiums_list as $store_id => $goods_list) {
                foreach($goods_list as $goods_info) {
                    if (!isset($goods_storage_quangity[$goods_info['goods_id']])) {
                        $goods_storage_quangity[$goods_info['goods_id']] = $goods_info['goods_storage'];
                    }
                }
            }
        }
        return $goods_storage_quangity;
    }

    /**
     * 取得每种商品的购买量
     * @param array $store_cart_list 购买列表
     * @return array 商品ID=>购买数量
     */
    private function _getEachGoodsBuyQuantity($store_cart_list) {
        if(empty($store_cart_list) || !is_array($store_cart_list)) return array();
        $goods_buy_quangity = array();
        foreach ($store_cart_list as $store_cart) {
            foreach ($store_cart as $cart_info) {
                if (!intval($cart_info['bl_id'])) {
                    //正常商品
                    $goods_buy_quangity[$cart_info['goods_id']] += $cart_info['goods_num'];
                } elseif (!empty($cart_info['bl_goods_list']) && is_array($cart_info['bl_goods_list'])) {
                    //优惠套装
                    foreach ($cart_info['bl_goods_list'] as $goods_info) {
                        $goods_buy_quangity[$goods_info['goods_id']] += $cart_info['goods_num'];
                    }
                }
            }
        }
        return $goods_buy_quangity;
    }

    /**
     * 得到所购买的id和数量
     *
     */
    private function _parseItems($cart_id) {
        //存放所购商品ID和数量组成的键值对
        $buy_items = array();
        if (is_array($cart_id)) {
            foreach ($cart_id as $value) {
                if (preg_match_all('/^(\d{1,10})\|(\d{1,6})$/', $value, $match)) {
                    $buy_items[$match[1][0]] = $match[2][0];
                }
            }
        }
        return $buy_items;
    }

    /**
     * 拼装单条满即送规则页面描述信息
     * @param array $rule_info 满即送单条规则信息
     * @return string
     */
    private function _parseMansongRuleDesc($rule_info) {
        if (empty($rule_info) || !is_array($rule_info)) return;
        $discount_desc = !empty($rule_info['discount']) ? '，立减'.$rule_info['discount'].'元' : '';
        if (APP_ID == 'mobile') {
            $_tmp = array();
            $_tmp['desc'] = '单笔订单满'.$rule_info['price'].'元'.$discount_desc;
            if (!empty($rule_info['mansong_goods_name'])) {
                $_tmp['goods_name'] = $rule_info['mansong_goods_name'];
                $_tmp['url'] = cthumb($rule_info['goods_image'], 60);
            }
            return $_tmp;
        } else {
            if (!empty($rule_info['mansong_goods_name'])) {
                $goods_desc = " 送<a href='".urlShop('goods','index',array('goods_id'=>$rule_info['goods_id']))."' title='{$rule_info['mansong_goods_name']}' target='_blank'>[赠品]</a>";
                $goods_desc .= '<a class="ncc-store-gift" title="'.$rule_info['mansong_goods_name'].'" target="_blank" href="'.urlShop('goods','index',array('goods_id'=>$rule_info['goods_id'])).'"><img alt="'.$rule_info['mansong_goods_name'].'" src="'.cthumb($rule_info['goods_image'], 60).'"></a>';                    
            }
            return sprintf('满%s%s%s',$rule_info['price'],$discount_desc,$goods_desc);
        }
    }

}
