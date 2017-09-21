<?php
/**
 * 商品栏目管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class goodsControl extends SystemControl{
    private $links = array(
        array('url'=>'act=goods&op=goods','text'=>'所有商品'),
    );
    const EXPORT_SIZE = 5000;
    public function __construct() {
        parent::__construct ();
        Language::read('goods');
    }

    public function indexOp() {
        $this->goodsOp();
    }
    /**
     * 商品管理
     */
    public function goodsOp() {
        //父类列表，只取到第二级
        $gc_list = Model('goods_class')->getGoodsClassList(array('gc_parent_id' => 0));
        Tpl::output('gc_list', $gc_list);

        Tpl::output('top_link',$this->sublink($this->links,'goods'));
						
		Tpl::setDirquna('system');
        Tpl::showpage('goods.index');
    }
    /**
     * 违规下架商品管理
     */
    public function lockup_listOp() {
        Tpl::output('type', 'lockup');
        Tpl::output('top_link',$this->sublink($this->links,'lockup_list'));
						
		Tpl::setDirquna('shop');
        Tpl::showpage('goods.index');
    }
    /**
     * 等待审核商品管理
     */
    public function waitverify_listOp() {
        Tpl::output('type', 'waitverify');
        Tpl::output('top_link',$this->sublink($this->links,'waitverify_list'));
						
		Tpl::setDirquna('shop');
        Tpl::showpage('goods.index');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_goods = Model('goods');
        $condition = array();
        if ($_GET['goods_name'] != '') {
            $condition['goods_name'] = array('like', '%' . $_GET['goods_name'] . '%');
        }
        if ($_GET['goods_commonid'] != '') {
            $condition['goods_commonid'] = array('like', '%' . $_GET['goods_commonid'] . '%');
        }
        if ($_GET['store_name'] != '') {
            $condition['store_name'] = array('like', '%' . $_GET['store_name'] . '%');
        }
        if ($_GET['brand_name'] != '') {
            $condition['brand_name'] = array('like', '%' . $_GET['brand_name'] . '%');
        }
        if (intval($_GET['cate_id']) > 0) {
            $condition['gc_id'] = intval($_GET['cate_id']);
        }
        if ($_GET['goods_state'] != '') {
            $condition['goods_state'] = $_GET['goods_state'];
        }
        if ($_GET['goods_verify'] != '') {
            $condition['goods_verify'] = $_GET['goods_verify'];
        }
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('goods_commonid', 'goods_name', 'goods_price', 'goods_state', 'goods_verify', 'goods_image', 'goods_jingle', 'gc_id'
                , 'gc_name', 'store_id', 'store_name', 'is_own_shop', 'brand_id', 'brand_name', 'goods_addtime', 'goods_marketprice', 'goods_costprice'
                , 'goods_freight', 'is_virtual', 'virtual_indate', 'virtual_invalid_refund', 'is_fcode'
                , 'is_presell', 'presell_deliverdate'
        );
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];

        switch ($_GET['type']) {
            // 禁售
            case 'lockup':
                $goods_list = $model_goods->getGoodsCommonLockUpList($condition, '*', $page, $order);
                break;
                // 等待审核
            case 'waitverify':
                $goods_list = $model_goods->getGoodsCommonWaitVerifyList($condition, '*', $page, $order);
                break;
                // 全部商品
            default:
                $goods_list = $model_goods->getGoodsCommonList($condition, '*', $page, $order);
                break;
        }

        // 库存
        $storage_array = $model_goods->calculateStorage($goods_list);

        // 商品状态
        $goods_state = $this->getGoodsState();

        // 审核状态
        $verify_state = $this->getGoodsVerify();

        $data = array();
        $data['now_page'] = $model_goods->shownowpage();
        $data['total_num'] = $model_goods->gettotalnum();
        foreach ($goods_list as $value) {
            $param = array();
            $operation = '';
            switch ($_GET['type']) {
                // 禁售
                case 'lockup':
                    $operation .= "<a class='btn red' href='javascript:void(0);' onclick=\"fg_del('" . $value['goods_commonid'] . "')\"><i class='fa fa-trash-o'></i>删除</a>";
                    break;
                    // 等待审核
                case 'waitverify':
                    $operation .= "<a class='btn orange' href='javascript:void(0);' onclick=\"fg_verify('" . $value['goods_commonid'] . "')\"><i class='fa fa-check-square'></i>审核</a>";
                    break;
                    // 全部商品
                default:
                    $operation .= "<a class='btn red' href='javascript:void(0);' onclick=\"fg_lonkup('" . $value['goods_commonid'] . "')\"><i class='fa fa-ban'></i>下架</a>";
                    break;
            }
            $operation .= "<span class='btn'><em><i class='fa fa-cog'></i>操作 <i class='arrow'></i></em><ul>";
			$operation .= "<li><a href='javascript:void(0)' onclick=\"fg_sku('" . $value['goods_commonid'] . "')\">评论添加</a></li>";
			$operation .= "<li><a href='" . urlShop('goods', 'index', array('goods_id' => $storage_array[$value['goods_commonid']]['goods_id'])) . "' target=\"_blank\">查看商品详细</a></li>";
			$operation .= "</ul>";
            $param['operation'] = $operation;
            $param['goods_commonid'] = $value['goods_commonid'];
            $param['goods_name'] = $value['goods_name'];
            $param['goods_price'] = ncPriceFormat($value['goods_price']);
            $param['goods_state'] = $goods_state[$value['goods_state']];
            $param['goods_verify'] = $verify_state[$value['goods_verify']];
            $param['goods_image'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".thumb($value,'60').">\")'><i class='fa fa-picture-o'></i></a>";
            $param['goods_jingle'] = $value['goods_jingle'];
            $param['gc_id'] = $value['gc_id'];
            $param['gc_name'] = $value['gc_name'];
            $param['store_id'] = $value['store_id'];
            $param['store_name'] = $value['store_name'];
            $param['is_own_shop'] = $value['is_own_shop'] == 1 ? '平台自营' : '入驻商户';
            $param['brand_id'] = $value['brand_id'];
            $param['brand_name'] = $value['brand_name'];
            $param['goods_addtime'] = date('Y-m-d', $value['goods_addtime']);
            $param['goods_marketprice'] = ncPriceFormat($value['goods_marketprice']);
            $param['goods_costprice'] = ncPriceFormat($value['goods_costprice']);
            $param['goods_freight'] = $value['goods_freight'] == 0 ? '免运费' : ncPriceFormat($value['goods_freight']);
            $param['goods_storage'] = $storage_array[$value['goods_commonid']]['sum'];
            $param['is_virtual'] = $value['is_virtual'] ==  '1' ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            $param['virtual_indate'] = $value['is_virtual'] == '1' && $value['virtual_indate'] > 0 ? date('Y-m-d', $value['virtual_indate']) : '--';
            $param['virtual_invalid_refund'] = $value['is_virtual'] ==  '1' ? ($value['virtual_invalid_refund'] == 1 ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>') : '--';
            $data['list'][$value['goods_commonid']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 商品状态
     * @return multitype:string
     */
    private function getGoodsState() {
        return array('1' => '出售中', '0' => '仓库中', '10' => '违规下架');
    }

    private function getGoodsVerify() {
        return array('1' => '通过', '0' => '未通过', '10' => '等待审核');
    }
	 /**
     * 订单添加评价
     */
    public function addOp(){
		$model_evaluate_goods = Model('evaluate_goods');
		$model_evaluate_store = Model('evaluate_store');
		$model_order =Model('order');
        if (chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["member_name"], "require"=>"true", "message"=>'请填写会员名称'),
                array("input"=>$_POST["geval_content"], "require"=>"true", "message"=>'请填写评价内容'),
                        );
            $error = $obj_validate->validate();
			
            if ($error != ''){
                showMessage($error);
            }else {
			//添加订单记录  
			$order['order_sn'] = $_POST['goodsid'].mt_rand(10, 100);;
            $order['pay_sn']   = $_POST['goodsid'].mt_rand(10, 100);;
            $order['store_id'] = 0;
            $order['store_name'] = trim($_POST['storename']);
            $order['buyer_id'] = trim($_POST['member_id']);
            $order['buyer_name'] = trim($_POST['member_name']);
            $order['buyer_email'] = 0;
            $order['buyer_phone'] = 0;
            $order['add_time'] = TIMESTAMP- (mt_rand(3,10) * 24 * 3600);
            $order['payment_code'] = 'online';
            $order['order_state'] = '40';
            $order['order_amount'] = intval($_POST['goodsprice'])*$ordergoods['goods_num'];
            $order['shipping_fee'] = '0';
            $order['goods_amount'] = $order['order_amount'] - $order['shipping_fee'];
            $order['order_from'] = mt_rand(1, 2);
            $order['order_type'] = mt_rand(1, 3);
            $order['chain_id'] = 0;
            $order['rpt_amount'] = 0;

            $order_id=$model_order->addOrder($order);
            //添加订单商品记录  
			$order_goods = array();
            $order_goods['order_id'] = $order_id;
            $order_goods['goods_id'] = intval($_POST['goodsid']);
			$order_goods['goods_name'] = trim($_POST['gname']);
            $order_goods['goods_price'] = intval($_POST['goodsprice']);
            $order_goods['goods_num']= intval($_POST['goods_num']);
			$order_goods['goods_image'] = trim($_POST['goodsimage']);
			$order_goods['goods_pay_price'] = intval($_POST['goodsprice'])*$ordergoods['goods_num'];
			$order_goods['store_id'] = intval($_POST['storeid']);
			$order_goods['buyer_id'] = trim($_POST['member_id']);
			$order_goods['goods_type'] = 1;
			$order_goods['commis_rate'] = 0;
			$order_goods['gc_id'] = 0;
			
            $model_order->addOrderGood($order_goods);
			
				//添加商品评分 
                 $insert_array = array();
				 $insert_array['geval_orderid'] = $order_id;
                 $insert_array['geval_orderno'] = '10';
                 $insert_array['geval_ordergoodsid'] = '15';
                 $insert_array['geval_goodsid'] = intval($_POST['goodsid']);
                 $insert_array['geval_goodsname'] = trim($_POST['gname']);
                 $insert_array['geval_goodsprice'] = intval($_POST['goodsprice']);
                 $insert_array['geval_goodsimage'] = trim($_POST['goodsimage']);
                 $insert_array['geval_scores'] = trim($_POST['geval_scores']);
                 $insert_array['geval_content'] = trim($_POST['geval_content']);
                 $insert_array['geval_isanonymous'] = 1;
                 $insert_array['geval_addtime'] = TIMESTAMP;
                 $insert_array['geval_storeid'] = intval($_POST['storeid']);
                 $insert_array['geval_storename'] = trim($_POST['storename']);
                 $insert_array['geval_frommemberid'] = trim($_POST['member_id']);
                 $insert_array['geval_frommembername'] = trim($_POST['member_name']);
                 $insert_array['geval_image'] = '';
                 $insert_array['geval_content_again'] = '';
                 $insert_array['geval_image_again'] = '';
                 $insert_array['geval_explain_again'] = '';
                 $result = $model_evaluate_goods->addEvaluateGoods($insert_array);
				 //添加店铺评分 
				 $evaluate_store_info = array();
                $evaluate_store_info['seval_orderid'] = $order_id;
                $evaluate_store_info['seval_orderno'] = '10';
                $evaluate_store_info['seval_addtime'] = time();
                $evaluate_store_info['seval_storeid'] = intval($_POST['storeid']);
                $evaluate_store_info['seval_storename'] = trim($_POST['storename']);
                $evaluate_store_info['seval_memberid'] = trim($_POST['member_id']);
                $evaluate_store_info['seval_membername'] = trim($_POST['member_name']);
                $evaluate_store_info['seval_desccredit'] = trim($_POST['geval_scores']);
                $evaluate_store_info['seval_servicecredit'] = trim($_POST['geval_scores']);
                $evaluate_store_info['seval_deliverycredit'] = trim($_POST['geval_scores']);
                $model_evaluate_store->addEvaluateStore($evaluate_store_info);
				
			$data = array();
            $data['goods_salenum'] = array('exp','goods_salenum+'.$_POST['goods_num']);
            $result = Model('goods')->editGoodsById($data, $_POST['goodsid']);
							
                 if ($result){
                    $url = array(
                        array(
                            'url'=>'index.php?act=goods&op=add&gid='.$_POST['goodsid'].'',
                            'msg'=>'继续',
                        ),
                        array(
                            'url'=>'index.php?act=goods',
                            'msg'=>'返回',
                        )
                    );
                    $this->log('添加评论'.'['.$_POST['gname'].']',1);
                    showMessage('添加成功',$url);
                }else {
                    showMessage('评论失败');
                }
            }
        }
		$goods_list = Model('goods')->getGoodsInfo(array('goods_id' => $_GET['gid']));
        Tpl::output('goods_list', $goods_list);
                 

			Tpl::setDirquna('system');
            Tpl::showpage('evaluation.add');
        
    }
    /**
     * 违规下架
     */
    public function goods_lockupOp() {
        if (chksubmit()) {
            $commonid = intval($_POST['commonid']);
            if ($commonid <= 0) {
                    showDialog(L('nc_common_op_fail'), 'reload');
            }
            $update = array();
            $update['goods_stateremark'] = trim($_POST['close_reason']);

            $where = array();
            $where['goods_commonid'] = $commonid;

            Model('goods')->editProducesLockUp($update, $where);
            showDialog(L('nc_common_op_succ'), '', 'succ', '$("#flexigrid").flexReload();CUR_DIALOG.close()');
        }
        $common_info = Model('goods')->getGoodsCommonInfoByID($_GET['id']);
        Tpl::output('common_info', $common_info);
						
		Tpl::setDirquna('shop');
        Tpl::showpage('goods.close_remark', 'null_layout');
    }


    /**
     * 删除商品
     */
    public function goods_delOp() {
        $common_id = intval($_GET['id']);
        if ($common_id <= 0) {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
        Model('goods')->delGoodsAll(array('goods_commonid' => $common_id));
        $this->log('删除商品[ID:'.$common_id.']',1);
        exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
    }

    /**
     * 审核商品
     */
    public function goods_verifyOp(){
        if (chksubmit()) {
            $commonid = intval($_POST['commonid']);
            if ($commonid <= 0) {
                    showDialog(L('nc_common_op_fail'), 'reload');
            }
            $update2 = array();
            $update2['goods_verify'] = intval($_POST['verify_state']);

            $update1 = array();
            $update1['goods_verifyremark'] = trim($_POST['verify_reason']);
            $update1 = array_merge($update1, $update2);
            $where = array();
            $where['goods_commonid'] = $commonid;

            $model_goods = Model('goods');
            if (intval($_POST['verify_state']) == 0) {
                $model_goods->editProducesVerifyFail($where, $update1, $update2);
            } else {
                $model_goods->editProduces($where, $update1, $update2);
            }
            showDialog(L('nc_common_op_succ'), '', 'succ', '$("#flexigrid").flexReload();CUR_DIALOG.close();');
        }
        $common_info = Model('goods')->getGoodsCommonInfoByID($_GET['id']);
        Tpl::output('common_info', $common_info);
						
		Tpl::setDirquna('shop');
        Tpl::showpage('goods.verify_remark', 'null_layout');
    }

    /**
     * ajax获取商品列表
     */
    public function get_goods_sku_listOp() {
        $commonid = $_GET['commonid'];
        if ($commonid <= 0) {
            showDialog('参数错误', '', '', 'CUR_DIALOG.close();');
        }
        $model_goods = Model('goods');
        $goodscommon_list = $model_goods->getGoodsCommonInfoByID($commonid, 'spec_name');
        if (empty($goodscommon_list)) {
            showDialog('参数错误', '', '', 'CUR_DIALOG.close();');
        }
        $spec_name = array_values((array)unserialize($goodscommon_list['spec_name']));
        $goods_list = $model_goods->getGoodsList(array('goods_commonid' => $commonid), 'goods_id,goods_spec,store_id,goods_price,goods_serial,goods_storage,goods_image');
        if (empty($goods_list)) {
            showDialog('参数错误', '', '', 'CUR_DIALOG.close();');
        }

        foreach ($goods_list as $key => $val) {
            $goods_spec = array_values((array)unserialize($val['goods_spec']));
            $spec_array = array();
            foreach ($goods_spec as $k => $v) {
                $spec_array[] = '<div class="goods_spec">' . $spec_name[$k] . L('nc_colon') . '<em title="' . $v . '">' . $v .'</em>' . '</div>';
            }
            $goods_list[$key]['goods_image'] = thumb($val, '60');
            $goods_list[$key]['goods_spec'] = implode('', $spec_array);
            $goods_list[$key]['url'] = urlShop('goods', 'index', array('goods_id' => $val['goods_id']));
        }

//         /**
//          * 转码
//          */
//         if (strtoupper(CHARSET) == 'GBK') {
//             Language::getUTF8($goods_list);
//         }
//         echo json_encode($goods_list);
        Tpl::output('goods_list', $goods_list);
						
		Tpl::setDirquna('system');
        Tpl::showpage('goods.sku_list', 'null_layout');
    }
	


  

    /**
     * csv导出
     */
    public function export_csvOp() {
        $model_goods = Model('goods');
        $condition = array();
        $limit = false;
        if ($_GET['id'] != '') {
            $id_array = explode(',', $_GET['id']);
            $condition['goods_commonid'] = array('in', $id_array);
        }
        if ($_GET['goods_name'] != '') {
            $condition['goods_name'] = array('like', '%' . $_GET['goods_name'] . '%');
        }
        if ($_GET['goods_commonid'] != '') {
            $condition['goods_commonid'] = array('like', '%' . $_GET['goods_commonid'] . '%');
        }
        if ($_GET['store_name'] != '') {
            $condition['store_name'] = array('like', '%' . $_GET['store_name'] . '%');
        }
        if ($_GET['brand_name'] != '') {
            $condition['brand_name'] = array('like', '%' . $_GET['brand_name'] . '%');
        }
        if ($_GET['cate_id'] != '') {
            $condition['gc_id'] = $_GET['cate_id'];
        }
        if ($_GET['goods_state'] != '') {
            $condition['goods_state'] = $_GET['goods_state'];
        }
        if ($_GET['goods_verify'] != '') {
            $condition['goods_verify'] = $_GET['goods_verify'];
        }
        if ($_REQUEST['query'] != '') {
            $condition[$_REQUEST['qtype']] = array('like', '%' . $_REQUEST['query'] . '%');
        }
        $order = '';
        $param = array('goods_commonid', 'goods_name', 'goods_price', 'goods_state', 'goods_verify', 'goods_image', 'goods_jingle', 'gc_id'
                , 'gc_name', 'store_id', 'store_name', 'is_own_shop', 'brand_id', 'brand_name', 'goods_addtime', 'goods_marketprice', 'goods_costprice'
                , 'goods_freight', 'is_virtual', 'virtual_indate', 'virtual_invalid_refund', 'is_fcode'
                , 'is_presell', 'presell_deliverdate'
        );
        if (in_array($_REQUEST['sortname'], $param) && in_array($_REQUEST['sortorder'], array('asc', 'desc'))) {
            $order = $_REQUEST['sortname'] . ' ' . $_REQUEST['sortorder'];
        }
        if (!is_numeric($_GET['curpage'])){
            switch ($_GET['type']) {
                // 禁售
                case 'lockup':
                    $count = $model_goods->getGoodsCommonLockUpCount($condition);
                    break;
                    // 等待审核
                case 'waitverify':
                    $count = $model_goods->getGoodsCommonWaitVerifyCount($condition);
                    break;
                    // 全部商品
                default:
                    $count = $model_goods->getGoodsCommonCount($condition);
                    break;
            }
            if ($count > self::EXPORT_SIZE ){   //显示下载链接
                $array = array();
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','index.php?act=goods&op=index');
								
		Tpl::setDirquna('shop');
                Tpl::showpage('export.excel');
                exit();
            }
        } else {
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $limit = $limit1 .','. $limit2;
        }
        switch ($_GET['type']) {
            // 禁售
            case 'lockup':
                $goods_list = $model_goods->getGoodsCommonLockUpList($condition, '*', null, $order, $limit);
                break;
                // 等待审核
            case 'waitverify':
                $goods_list = $model_goods->getGoodsCommonWaitVerifyList($condition, '*', null, $order, $limit);
                break;
                // 全部商品
            default:
                $goods_list = $model_goods->getGoodsCommonList($condition, '*', null, $order, $limit);
                break;
        }
        $this->createCsv($goods_list);
    }

    /**
     * 生成csv文件
     */
    private function createCsv($goods_list) {
        // 库存
        $storage_array = Model('goods')->calculateStorage($goods_list);

        // 商品状态
        $goods_state = $this->getGoodsState();

        // 审核状态
        $verify_state = $this->getGoodsVerify();
        $data = array();
        foreach ($goods_list as $value) {
            $param = array();
            $param['goods_commonid'] = $value['goods_commonid'];
            $param['goods_name'] = $value['goods_name'];
            $param['goods_price'] = ncPriceFormat($value['goods_price']);
            $param['goods_state'] = $goods_state[$value['goods_state']];
            $param['goods_verify'] = $verify_state[$value['goods_verify']];
            $param['goods_image'] = thumb($value,'60');
            $param['goods_jingle'] = htmlspecialchars($value['goods_jingle']);
            $param['gc_id'] = $value['gc_id'];
            $param['gc_name'] = $value['gc_name'];
            $param['store_id'] = $value['store_id'];
            $param['store_name'] = $value['store_name'];
            $param['is_own_shop'] = $value['is_own_shop'] == 1 ? '平台自营' : '入驻商户';
            $param['brand_id'] = $value['brand_id'];
            $param['brand_name'] = $value['brand_name'];
            $param['goods_addtime'] = date('Y-m-d', $value['goods_addtime']);
            $param['goods_marketprice'] = ncPriceFormat($value['goods_marketprice']);
            $param['goods_costprice'] = ncPriceFormat($value['goods_costprice']);
            $param['goods_freight'] = $value['goods_freight'] == 0 ? '免运费' : ncPriceFormat($value['goods_freight']);
            $param['goods_storage'] = $storage_array[$value['goods_commonid']]['sum'];
            $param['is_virtual'] = $value['is_virtual'] ==  '1' ? '是' : '否';
            $param['virtual_indate'] = $value['is_virtual'] == '1' && $value['virtual_indate'] > 0 ? date('Y-m-d', $value['virtual_indate']) : '--';
            $param['virtual_invalid_refund'] = $value['is_virtual'] ==  '1' ? ($value['virtual_invalid_refund'] == 1 ? '是' : '否') : '--';
            $data[$value['goods_commonid']] = $param;
        }

        $header = array(
                'goods_commonid' => 'SPU',
                'goods_name' => '商品名称',
                'goods_price' => '商品价格(元)',
                'goods_state' => '商品状态',
                'goods_verify' => '审核状态',
                'goods_image' => '商品图片',
                'goods_jingle' => '广告词',
                'gc_id' => '分类ID',
                'store_id' => '店铺ID',
                'store_name' => '店铺名称',
                'is_own_shop' => '店铺类型',
                'brand_id' => '品牌ID',
                'brand_name' => '品牌名称',
                'goods_addtime' => '发布时间',
                'goods_marketprice' => '市场价格(元)',
                'goods_costprice' => '成本价格(元)',
                'goods_freight' => '运费(元)',
                'goods_storage' => '库存',
                'is_virtual' => '虚拟商品',
                'virtual_indate' => '有效期',
                'virtual_invalid_refund' => '允许退款'
        );
       array_unshift($data, $header);
		$csv = new Csv();
	    $export_data = $csv->charset($data,CHARSET,'gbk');
	    $csv->filename = $csv->charset('goods_list',CHARSET).$_GET['curpage'] . '-'.date('Y-m-d');
	    $csv->export($data);
    }
}
