<?php
/**
 * 结算管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class goods_recommendControl extends SystemControl{

    public function __construct(){
        parent::__construct();
    }

    public function indexOp(){
        $model_rec = Model('goods_recommend');
		    	
		Tpl::setDirquna('shop');
        Tpl::showpage('goods_recommend.index');
    }

    /**
     * 新增
     */
    public function addOp(){
        $model_class = Model('goods_class');
        $gc_list = $model_class->getTreeClassList(1);
        Tpl::output('gc_list', $gc_list);

        $rec_gc_id = intval($_GET['rec_gc_id']);
        $goods_list = array();
        if ($rec_gc_id > 0) {
            $rec_list = Model('goods_recommend')->getGoodsRecommendList(array('rec_gc_id'=>$rec_gc_id),'','','*','','rec_goods_id');
            if (!empty($rec_list)) {
                $goods_list = Model('goods')->getGoodsOnlineList(array('goods_id'=>array('in',array_keys($rec_list))),'goods_name,goods_id,goods_image');
                if (!empty($goods_list)) {
                    foreach ($goods_list as $k => $v) {
                        $goods_list[$k]['goods_image'] = thumb($v,240);
                    }
                }
            }
        }
        Tpl::output('goods_list_json',json_encode($goods_list));
        Tpl::output('goods_list', $goods_list);
        Tpl::output('rec_info', is_array($rec_list) ? current($rec_list) : array());
		    	
		Tpl::setDirquna('shop');
        Tpl::showpage('goods_recommend.add');
    }

    /**
     * 保存
     */
    public function saveOp(){
        $gc_id = intval($_POST['gc_id']);
        if (!chksubmit() || $gc_id <= 0) {
            showMessage('非法提交');
        }
        $model_rec = Model('goods_recommend');
        $del = $model_rec->delGoodsRecommend(array('rec_gc_id' => $gc_id));
        if (!$del) {
            showMessage('保存失败');
        }

        $data = array();
        if (is_array($_POST['goods_id_list'])) {
            foreach ($_POST['goods_id_list'] as $k => $goods_id) {
                $data[$k]['rec_gc_id'] = $_POST['gc_id'];
                $data[$k]['rec_gc_name'] = rtrim($_POST['gc_name'],' >');
                $data[$k]['rec_goods_id'] = $goods_id;
            }
        }
        $insert = $model_rec->addGoodsRecommend($data);
        if ($insert) {
            showMessage('保存成功','index.php?act=goods_recommend&op=index');
        }
    }

    public function get_xmlOp(){
        $model_rec = Model('goods_recommend');
        $condition  = array();
        $sort_fields = array('rec_id');
        if ($_POST['sortorder'] != '' && in_array($_POST['sortname'],$sort_fields)) {
            $order = $_POST['sortname'].' '.$_POST['sortorder'];
        }
        if ($_POST['query'] != '' && in_array($_POST['qtype'],array('rec_gc_name'))) {
            $condition[$_POST['qtype']] = array('like',"%{$_POST['query']}%");
        }
        $total_num = $model_rec->getGoodsRecommendCount($condition,'distinct rec_gc_id');
        $rec_list = $model_rec->getGoodsRecommendList($condition,$_POST['rp'],$order,'count(*) as rec_count,rec_gc_id,min(rec_gc_name) as rec_gc_name,min(rec_id) as rec_id','rec_gc_id','',$total_num);
        $data = array();
        $data['now_page'] = $model_rec->shownowpage();
        $data['total_num'] = $total_num;
        foreach ($rec_list as $v) {
            $list = array();
            $list['operation'] = "<a class='btn red' onclick=\"fg_delete({$v['rec_gc_id']})\"><i class='fa fa-trash-o'></i>删除</a><a class='btn blue' href='index.php?act=goods_recommend&op=add&rec_gc_id={$v['rec_gc_id']}'><i class='fa fa-pencil-square-o'></i>编辑</a>";
            $list['rec_gc_name'] = $v['rec_gc_name'];
            $list['rec_count'] = $v['rec_count'];
            $data['list'][$v['rec_gc_id']] = $list;
        }
        exit(Tpl::flexigridXML($data));
    }

    /**
     * 删除
     */
    public function deleteOp() {
        $model_rec = Model('goods_recommend');
        $condition = array();
        if (preg_match('/^[\d,]+$/', $_GET['del_id'])) {
            $_GET['del_id'] = explode(',',trim($_GET['del_id'],','));
            $condition['rec_gc_id'] = array('in',$_GET['del_id']);
        }
        $del = $model_rec->delGoodsRecommend($condition);
        if (!$del){
            $this->log('删除分类推荐商品失败',0);
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }else{
            $this->log('成功删除分类推荐商品',1);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        }
    }

    public function get_goods_listOp(){
        $model_goods = Model('goods');
        $condition = array();
        $condition['gc_id'] = intval($_GET['gc_id']);
        if (!empty($_GET['goods_name'])) {
            $condition['goods_name'] = array('like',"%{$_GET['goods_name']}%");
        }
        $goods_list = $model_goods->getGoodsOnlineList($condition,'*',8);
        $html = "<ul class=\"dialog-goodslist-s2\">";
        foreach($goods_list as $v) {
            $url = urlShop('goods', 'index', array('goods_id' => $v['goods_id']));
            $img = thumb($v,240);
            $html .= <<<EOB
            <li>
            <div class="goods-pic" onclick="select_recommend_goods({$v['goods_id']});">
            <span class="ac-ico"></span>
            <span class="thumb size-72x72">
            <i></i>
            <img width="72" src="{$img}" goods_name="{$v['goods_name']}" goods_id="{$v['goods_id']}" title="{$v['goods_name']}">
            </span>
            </div>
            <div class="goods-name">
            <a target="_blank" href="{$url}">{$v['goods_name']}</a>
            </div>
            </li>
EOB;
        }
        $admin_tpl_url = ADMIN_TEMPLATES_URL;
        $html .= '<div class="clear"></div></ul><div id="pagination" class="pagination">'.$model_goods->showpage(1).'</div><div class="clear"></div>';
        $html .= <<<EOB
        <script>
        $('#pagination').find('.demo').ajaxContent({
                event:'click',
                loaderType:"img",
                loadingMsg:"{$admin_tpl_url}/images/transparent.gif",
                target:'#show_recommend_goods_list'
            });
        </script>
EOB;
        echo $html;
    }
}
