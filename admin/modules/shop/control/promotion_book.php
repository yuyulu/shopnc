<?php
/**
 * 预售活动管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class promotion_bookControl extends SystemControl{
    private $links = array(
        array('url'=>'act=promotion_book&op=index','text'=>'定金预售'),
        array('url'=>'act=promotion_book&op=presell_goods_list', 'text'=>'全款预售'),
        array('url'=>'act=promotion_book&op=book_quota_list','text'=>'套餐列表'),
        array('url'=>'act=promotion_book&op=book_setting','text'=>'设置')
    );
    public function __construct(){
        parent::__construct();
        //检查审核功能是否开启
        if (intval($_GET['promotion_allow']) !== 1 && intval(C('promotion_allow')) !== 1){
            $url = array(
                array(
                    'url'=>'index.php?act=setting',
                    'msg'=>L('close'),
                ),
                array(
                    'url'=>'index.php?act=promotion_book&promotion_allow=1',
                    'msg'=>L('open'),
                )
            );
            showMessage('商品促销功能尚未开启', $url, 'html', 'succ', 1, 6000);
        }
    }

    /**
     * 默认Op
     */
    public function indexOp() {
        //自动开启优惠套装
        if (intval($_GET['promotion_allow']) === 1){
            $model_setting = Model('setting');
            $update_array = array();
            $update_array['promotion_allow'] = 1;
            $model_setting->updateSetting($update_array);
        }
        $this->book_goods_listOp();
    }

    /**
     * 活动商品列表
     */
    public function book_goods_listOp() {
        Tpl::output('top_link',$this->sublink($this->links,'index'));
		Tpl::setDirquna('shop');
        Tpl::showpage('promotion_book.book_goods');
    }

    /**
     * 活动商品管理XML
     */
    public function get_book_goods_xmlOp() {
        $model_book = Model('p_book');
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('goods_id', 'goods_name', 'book_down_payment', 'book_final_payment', 'book_down_time', 'goods_price', 'store_id', 'store_name', 'is_own_shop');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $goods_list = $model_book->getBookGoodsList($condition, '*', $page, $order);

        $flippedOwnShopIds = array_flip(Model('store')->getOwnShopIds());

        $data = array();
        $data['now_page'] = $model_book->shownowpage();
        $data['total_num'] = $model_book->gettotalnum();
        foreach ($goods_list as $value) {
            $param = array();
            $operation = "<a class='btn red' href='javascript:void(0);' onclick=\"fg_del('". $value['goods_id'] ."')\"><i class='fa fa-trash-o'></i>删除</a><a class='btn green' href='" . urlShop('goods', 'index', array('goods_id' => $value['goods_id'])) . "' target='_blank'><i class='fa fa-list-alt'></i>查看</a>";
            $param['operation'] = $operation;
            $param['goods_id'] = $value['goods_id'];
            $param['goods_name'] = $value['goods_name'];
            $param['book_down_payment'] = $value['book_down_payment'];
            $param['book_final_payment'] = $value['book_final_payment'];
            $param['book_total_payment'] = ncPriceFormat($value['book_down_payment'] + $value['book_final_payment']);
            $param['book_down_time'] = date('Y-m-d', $value['book_down_time']);
            $param['goods_price'] = $value['goods_price'];
            $param['store_id'] = $value['store_id'];
            $param['store_name'] = '<a target="_blank" href="' . urlShop('show_store', 'index', array('store_id'=>$value['store_id'])) . '">' .$value['store_name'] . '</a>';
            if (isset($flippedOwnShopIds[$value['store_id']])) {
                $param['store_name'] .= '<span class="ownshop">[自营]</span>';
            }
            $data['list'][$value['goods_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 删除订金预售商品活动
     */
    public function del_book_goodsOp() {
        $id = intval($_GET['id']);
        if ($id > 0) {
            $state = Model('p_book')->delBookGoodsByGoodsId($id);
            $this->log('删除定金预售商品活动，商品ID'.$id);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * 活动商品列表
     */
    public function presell_goods_listOp() {
        Tpl::output('top_link',$this->sublink($this->links,'presell_goods_list'));
		Tpl::setDirquna('shop');
        Tpl::showpage('promotion_book.presell_goods');
    }

    /**
     * 活动商品管理XML
     */
    public function get_presell_goods_xmlOp() {
        $model_book = Model('p_book');
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('goods_id', 'goods_name', 'presell_deliverdate', 'goods_price', 'store_id', 'store_name', 'is_own_shop');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $goods_list = $model_book->getPersellGoodsList($condition, '*', $page, $order);

        $flippedOwnShopIds = array_flip(Model('store')->getOwnShopIds());

        $data = array();
        $data['now_page'] = $model_book->shownowpage();
        $data['total_num'] = $model_book->gettotalnum();
        foreach ($goods_list as $value) {
            $param = array();
            $operation = "<a class='btn red' href='javascript:void(0);' onclick=\"fg_del('". $value['goods_id'] ."')\"><i class='fa fa-trash-o'></i>删除</a><a class='btn green' href='" . urlShop('goods', 'index', array('goods_id' => $value['goods_id'])) . "' target='_blank'><i class='fa fa-list-alt'></i>查看</a>";
            $param['operation'] = $operation;
            $param['goods_id'] = $value['goods_id'];
            $param['goods_name'] = $value['goods_name'];
            $param['presell_deliverdate'] = date('Y-m-d', $value['presell_deliverdate']);
            $param['goods_price'] = $value['goods_price'];
            $param['store_id'] = $value['store_id'];
            $param['store_name'] = '<a target="_blank" href="' . urlShop('show_store', 'index', array('store_id'=>$value['store_id'])) . '">' .$value['store_name'] . '</a>';
            if (isset($flippedOwnShopIds[$value['store_id']])) {
                $param['store_name'] .= '<span class="ownshop">[自营]</span>';
            }
            $data['list'][$value['goods_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 删除订金预售商品活动
     */
    public function del_presell_goodsOp() {
        $id = intval($_GET['id']);
        if ($id > 0) {
            $state = Model('p_book')->delPresellGoodsByGoodsId($id);
            $this->log('删除全款预售商品活动，商品ID'.$id);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * 套餐列表
     */
    public function book_quota_listOp() {
        Tpl::output('top_link',$this->sublink($this->links,'book_quota_list'));
		Tpl::setDirquna('shop');
        Tpl::showpage('promotion_book.quota');
    }

    /**
     * 套餐列表XML
     */
    public function get_quota_xmlOp() {
        $model_book = Model('p_book');
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('store_id', 'store_name', 'bkq_starttime', 'bkq_endtime');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $quota_list = $model_book->getBookQuotaList($condition, '*', $page, $order);

        $data = array();
        $data['now_page'] = $model_book->shownowpage();
        $data['total_num'] = $model_book->gettotalnum();
        foreach ($quota_list as $value) {
            $param = array();
            $param['operation'] = '--';
            $param['store_id'] = $value['store_id'];
            $param['store_name'] = $value['store_name'];
            $param['bkq_starttime'] = date('Y-m-d H:i:s', $value['bkq_starttime']);
            $param['bkq_endtime'] = date('Y-m-d H:i:s', $value['bkq_endtime']);
            $data['list'][$value['bkq_id']] = $param;
        }
        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 设置
     */
    public function book_settingOp() {
        // 实例化模型
        $model_setting = Model('setting');

        if (chksubmit()){
            // 验证
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["promotion_book_price"], "require"=>"true", 'validator'=>'Number', "message"=>'请填写展位价格'),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }

            $data['promotion_book_price'] = intval($_POST['promotion_book_price']);

            $return = $model_setting->updateSetting($data);
            if($return){
                $this->log(L('nc_set').' 推荐展位');
                showMessage(L('nc_common_op_succ'));
            }else{
                showMessage(L('nc_common_op_fail'));
            }
        }

        // 查询setting列表
        $setting = $model_setting->GetListSetting();
        Tpl::output('setting',$setting);

        Tpl::output('top_link',$this->sublink($this->links,'book_setting'));
		Tpl::setDirquna('shop');
        Tpl::showpage('promotion_book.setting');
    }
}
