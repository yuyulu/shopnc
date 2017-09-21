<?php
/**
 * 商品评价
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class evaluateControl extends SystemControl{
    private $links = array(
            array('url'=>'act=evaluate&op=evalgoods_list','text'=>'来自买家的评价'),
            array('url'=>'act=evaluate&op=evalstore_list','text'=>'店铺动态评价')
    );
    public function __construct() {
        parent::__construct();
        Language::read('evaluate');
        if ($_GET['op'] == 'index') $_GET['op'] = 'evalgoods_list';
        Tpl::output('top_link',$this->sublink($this->links,$_GET['op']));
    }

    public function indexOp() {
        $this->evalgoods_listOp();
    }

    /**
     * 商品来自买家的评价列表
     */
    public function evalgoods_listOp() {
						
		Tpl::setDirquna('shop');
        Tpl::showpage('evalgoods.index');
    }

    /**
     * 商品来自买家的评价列表
     */
    public function get_goods_xmlOp() {
        $model_evaluate_goods = Model('evaluate_goods');
        $condition = array();
        if ($_POST['query'] != '' && in_array($_POST['qtype'],array('geval_frommembername','geval_goodsname','geval_storename'))) {
            $condition[$_POST['qtype']] = array('like',"%{$_POST['query']}%");
        }
        $sort_fields = array('geval_id','geval_frommembername','geval_scores','geval_addtime','geval_orderno','geval_frommemberid','geval_storeid','geval_goodsid');
        if ($_POST['sortorder'] != '' && in_array($_POST['sortname'],$sort_fields)) {
            $order = $_POST['sortname'].' '.$_POST['sortorder'];
        }
        $evalgoods_list = $model_evaluate_goods->getEvaluateGoodsList($condition, $_POST['rp'],$order);
        $data = array();
        $data['now_page'] = $model_evaluate_goods->shownowpage();
        $data['total_num'] = $model_evaluate_goods->gettotalnum();
        foreach ($evalgoods_list as $k => $evalgoods_info) {
            $list = array();
            $list['operation'] = "<a class='btn red' onclick=\"fg_delete({$evalgoods_info['geval_id']})\"><i class=\"fa fa-trash-o\"></i>删除</a>";
            $list['geval_frommembername'] = $evalgoods_info['geval_frommembername'];
            $list['geval_scores'] = "<span class=\"raty\" data-score=\"{$evalgoods_info['geval_scores']}\"></span>";
            $list['geval_content'] = "<span class='fa' title='{$evalgoods_info['geval_content']}'>{$evalgoods_info['geval_content']}</span>";
            if(!empty($evalgoods_info['geval_image'])) {
                $image_array = explode(',', $evalgoods_info['geval_image']);
                foreach ($image_array as $value) {
                    $list['geval_image'] .= "<li><a nctype=\"nyroModal\"  href=\"".snsThumb($value, 1024)."\"> <img src=\"".snsThumb($value)."\"> </a></li>";
                }
                $list['geval_image'] = '<ul class="evaluation-pic-list">'.$list['geval_image'].'</ul>';
            } else {
                $list['geval_image'] = '';
            }
            $list['geval_addtime'] = date('Y-m-d',$evalgoods_info['geval_addtime']);
            $list['geval_goodsname'] = "<a class='open' title='{$evalgoods_info['geval_goodsname']}' href='". urlShop('goods', 'index', array('goods_id' => $evalgoods_info['geval_goodsid'])) ."' target='blank'>{$evalgoods_info['geval_goodsname']}</a>";
            $list['geval_storename'] = "<a class='open' title='{$evalgoods_info['geval_storename']}' href='". urlShop('show_store','index', array('store_id'=>$evalgoods_info['geval_storeid'])) ."' target='blank'>{$evalgoods_info['geval_storename']}</a>";
            $list['geval_orderno'] = $evalgoods_info['geval_orderno'];
            $list['geval_frommemberid'] = $evalgoods_info['geval_frommemberid'];
            $list['geval_storeid'] = $evalgoods_info['geval_storeid'];
            $list['geval_content_again'] = "<span class='fa' title='{$evalgoods_info['geval_content_again']}'>{$evalgoods_info['geval_content_again']}</span>";
            if(!empty($evalgoods_info['geval_image_again'])) {
                $image_array = explode(',', $evalgoods_info['geval_image_again']);
                foreach ($image_array as $value) {
                    $list['geval_image_again'] .= "<li><a nctype=\"nyroModal\"  href=\"".snsThumb($value, 1024)."\"> <img src=\"".snsThumb($value)."\"> </a></li>";
                }
                $list['geval_image_again'] = '<ul class="evaluation-pic-list">'.$list['geval_image_again'].'</ul>';
            } else {
                $list['geval_image_again'] = '';
            }
            $data['list'][$evalgoods_info['geval_id']] = $list;
        }
        exit(Tpl::flexigridXML($data));
    }

    /**
     * 删除商品评价
     */
    public function evalgoods_delOp() {
        if (preg_match('/^[\d,]+$/', $_GET['geval_id'])) {
            $_GET['geval_id'] = explode(',',trim($_GET['geval_id'],','));
            $model_evaluate_goods = Model('evaluate_goods');
            $result = $model_evaluate_goods->delEvaluateGoods(array('geval_id' => array('in', $_GET['geval_id'])));
            if ($result) {
                $this->log('删除商品评价，评价编[ID:'.implode(',',$_GET['geval_id']).']',null);
                exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
            } else {
                exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
            }
        }
    }

    /**
     * 店铺动态评价列表
     */
    public function evalstore_listOp() {
						
		Tpl::setDirquna('shop');
        Tpl::showpage('evalstore.index');
    }

    /**
     *
     */
    public function get_store_xmlOp() {
        $model_evaluate_store = Model('evaluate_store');
        $condition = array();
        if ($_POST['query'] != '' && in_array($_POST['qtype'],array('seval_membername','seval_storename'))) {
            $condition[$_POST['qtype']] = array('like',"%{$_POST['query']}%");
        }
        $sort_fields = array('seval_membername','seval_storename','seval_id','seval_orderno','seval_memberid','seval_storeid','geval_goodsid');
        if ($_POST['sortorder'] != '' && in_array($_POST['sortname'],$sort_fields)) {
            $order = $_POST['sortname'].' '.$_POST['sortorder'];
        }
        $evalstore_list = $model_evaluate_store->getEvaluateStoreList($condition, $_POST['rp'],$order);
        $data = array();
        $data['now_page'] = $model_evaluate_store->shownowpage();
        $data['total_num'] = $model_evaluate_store->gettotalnum();
        foreach ($evalstore_list as $k => $evalstore_info) {
            $list = array();
            $list['operation'] = "<a class='btn red' onclick=\"fg_delete({$evalstore_info['seval_id']})\"><i class=\"fa fa-trash-o\"></i>删除</a>";
            $list['seval_membername'] = $evalstore_info['seval_membername'];
            $list['seval_desccredit'] = "<span class=\"raty\" data-score=\"{$evalstore_info['seval_desccredit']}\"></span>";
            $list['seval_servicecredit'] = "<span class=\"raty\" data-score=\"{$evalstore_info['seval_servicecredit']}\"></span>";
            $list['seval_deliverycredit'] = "<span class=\"raty\" data-score=\"{$evalstore_info['seval_deliverycredit']}\"></span>";
            $list['seval_storename'] = "<a class='open' title='{$evalstore_info['seval_storename']}' href='". urlShop('show_store','index', array('store_id'=>$evalstore_info['seval_storeid'])) ."' target='blank'>{$evalstore_info['seval_storename']}</a>";
            $list['geval_id'] = date('Y-m-d',$evalstore_info['seval_addtime']);
            $list['seval_orderno'] = $evalstore_info['seval_orderno'];
            $list['seval_memberid'] = $evalstore_info['seval_memberid'];
            $list['seval_storeid'] = $evalstore_info['seval_storeid'];
            $data['list'][$evalstore_info['seval_id']] = $list;
        }
        exit(Tpl::flexigridXML($data));
    }

    /**
     * 删除店铺评价
     */
    public function evalstore_delOp() {
        if (preg_match('/^[\d,]+$/', $_GET['seval_id'])) {
            $_GET['seval_id'] = explode(',',trim($_GET['seval_id'],','));
            $model_evaluate_store = Model('evaluate_store');
            $result = $model_evaluate_store->delEvaluateStore(array('seval_id'=>array('in',$_GET['seval_id'])));
            if ($result) {
                $this->log('删除店铺评价，评价编号[ID:'.implode(',', $_GET['seval_id']).']',null);
                exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
            } else {
                exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
            }
       }
    }
}
