<?php
/**
 * 任务计划 - 分钟执行的任务
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */
defined('In33hao') or exit('Access Invalid!');

class minutesControl extends BaseCronControl {

    /**
     * 默认方法
     */
    public function indexOp() {
        $this->_cron_common();
        $this->_web_index_update();
        $this->_cron_mail_send();
    }

    /**
     * 更新首页的商品价格信息
     */
    private function _web_index_update(){
         Model('web_config')->updateWebGoods();
    }

    /**
     * 发送邮件消息
     */
    private function _cron_mail_send() {
        //每次发送数量
        $_num = 50;
        $model_storemsgcron = Model('mail_cron');
        $cron_array = $model_storemsgcron->getMailCronList(array(), $_num);
        if (!empty($cron_array)) {
            $email = new Email();
            $mail_array = array();
            foreach ($cron_array as $val) {
                $return = $email->send_sys_email($val['mail'],$val['subject'],$val['contnet']);
                if ($return) {
                    // 记录需要删除的id
                    $mail_array[] = $val['mail_id'];
                }
            }
            // 删除已发送的记录
            $model_storemsgcron->delMailCron(array('mail_id' => array('in', $mail_array)));
        }
    }

    /**
     * 执行通用任务
     */
    private function _cron_common(){

        //查找待执行任务
        $model_cron = Model('cron');
        $cron = $model_cron->getCronList(array('exetime'=>array('elt',TIMESTAMP)));
        if (!is_array($cron)) return ;
        $cron_array = array(); $cronid = array();
        foreach ($cron as $v) {
            $cron_array[$v['type']][$v['exeid']] = $v;
        }
        foreach ($cron_array as $k=>$v) {
            // 如果方法不存是，直接删除id
            if (!method_exists($this,'_cron_'.$k)) {
                $tmp = current($v);
                $cronid[] = $tmp['id'];continue;
            }
            $result = call_user_func_array(array($this,'_cron_'.$k),array($v));
            if (is_array($result)){
                $cronid = array_merge($cronid,$result);
            }
        }
        //删除执行完成的cron信息
        if (!empty($cronid) && is_array($cronid)){
            $model_cron->delCron(array('id'=>array('in',$cronid)));
        }
    }

    /**
     * 上架
     *
     * @param array $cron
     */
    private function _cron_1($cron = array()){
        $condition = array('goods_commonid' => array('in',array_keys($cron)));
        $update = Model('goods')->editProducesOnline($condition);
        if ($update){
            //返回执行成功的cronid
            $cronid = array();
            foreach ($cron as $v) {
                $cronid[] = $v['id'];
            }
        }else{
            return false;
        }
        return $cronid;
    }

    /**
     * 根据商品id更新商品促销价格
     *
     * @param array $cron
     */
    private function _cron_2($cron = array()){
        $condition = array('goods_id' => array('in',array_keys($cron)));
        $update = Model('goods')->editGoodsPromotionPrice($condition);
        if ($update){
            //返回执行成功的cronid
            $cronid = array();
            foreach ($cron as $v) {
                $cronid[] = $v['id'];
            }
        }else{
            return false;
        }
        return $cronid;
    }

    /**
     * 优惠套装过期
     *
     * @param array $cron
     */
    private function _cron_3($cron = array()) {
        $condition = array('store_id' => array('in', array_keys($cron)));
        $update = Model('p_bundling')->editBundlingQuotaClose($condition);
        if ($update) {
            //返回执行成功的cronid
            $cronid = array();
            foreach ($cron as $v) {
                $cronid[] = $v['id'];
            }
        } else {
            return false;
        }
        return $cronid;
    }

    /**
     * 推荐展位过期
     *
     * @param array $cron
     */
    private function _cron_4($cron = array()) {
        $condition = array('store_id' => array('in', array_keys($cron)));
        $update = Model('p_booth')->editBoothClose($condition);
        if ($update) {
            //返回执行成功的cronid
            $cronid = array();
            foreach ($cron as $v) {
                $cronid[] = $v['id'];
            }
        } else {
            return false;
        }
        return $cronid;
    }

    /**
     * 抢购开始更新商品促销价格
     *
     * @param array $cron
     */
    private function _cron_5($cron = array()) {
        $condition = array();
        $condition['goods_commonid'] = array('in', array_keys($cron));
        $condition['start_time'] = array('lt', TIMESTAMP);
        $condition['end_time'] = array('gt', TIMESTAMP);
        $groupbuy = Model('groupbuy')->getGroupbuyList($condition);
        foreach ($groupbuy as $val) {
            Model('goods')->editGoods(array('goods_promotion_price' => $val['groupbuy_price'], 'goods_promotion_type' => 1), array('goods_commonid' => $val['goods_commonid']));
        }
        //返回执行成功的cronid
        $cronid = array();
        foreach ($cron as $v) {
            $cronid[] = $v['id'];
        }
        return $cronid;
    }

    /**
     * 抢购过期
     *
     * @param array $cron
     */
    private function _cron_6($cron = array()) {
        $condition = array('goods_commonid' => array('in', array_keys($cron)));
        //抢购活动过期
        $update = Model('groupbuy')->editExpireGroupbuy($condition);
        if ($update){
            //返回执行成功的cronid
            $cronid = array();
            foreach ($cron as $v) {
                $cronid[] = $v['id'];
            }
        }else{
            return false;
        }
        return $cronid;
    }

    /**
     * 限时折扣过期
     *
     * @param array $cron
     */
    private function _cron_7($cron = array()) {
        $condition = array('xianshi_id' => array('in', array_keys($cron)));
        //限时折扣过期
        $update = Model('p_xianshi')->editExpireXianshi($condition);
        if ($update){
            //返回执行成功的cronid
            $cronid = array();
            foreach ($cron as $v) {
                $cronid[] = $v['id'];
            }
        }else{
            return false;
        }
        return $cronid;
    }

    /**
     * 加价购过期
     *
     * @param array $cron
     */
    private function _cron_8($cron = array()) {
        $condition = array('id' => array('in', array_keys($cron)));
        // 过期
        $update = Model('p_cou')->editExpireCou($condition);
        if ($update){
            // 返回执行成功的cronid
            $cronid = array();
            foreach ($cron as $v) {
                $cronid[] = $v['id'];
            }
        } else {
            return false;
        }
        return $cronid;
    }

    /**
     * 更新店铺（新增）商品消费者保障服务开启状态（如果商品在店铺开启保障服务之后增加则需要执行该任务更新其服务状态）
     * @param array $cron
     */
    private function _cron_9($cron = array()) {
        //查询商品详情
        $model_goods = Model('goods');
        $where = array();
        $where['goods_commonid'] =  array('in', array_keys($cron));
        $goods_list = $model_goods->getGoodsList($where, 'goods_id,goods_commonid,store_id');
        $cronid = array();
		if (!$goods_list) {
            // 返回执行成功的cronid
            foreach ($cron as $k=>$v) {
                $cronid[] = $v['id'];
            }
            return $cronid;
        }
        $store_goods_list = array();
        foreach($goods_list as $k=>$v){
            $store_goods_list[$v['store_id']][$v['goods_id']] = $v;
        }
        //查询店铺的保障服务
        $where = array();
        $where['ct_storeid'] = array('in', array_keys($store_goods_list));
        $model_contract = Model('contract');
        $c_list = $model_contract->getContractList($where);
		if (!$c_list) {
            foreach ($cron as $k=>$v) {
				$cronid[] = $v['id'];
            }
            return $cronid;
        }
        $goods_contractstate_arr = $model_contract->getGoodsContractState();
        $c_list_tmp = array();
        foreach ($c_list as $k=>$v) {
            if ($v['ct_joinstate_key'] == 'added' && $v['ct_closestate_key'] == 'open') {
                $c_list_tmp[$v['ct_storeid']][$v['ct_itemid']] = $goods_contractstate_arr['open']['sign'];
            }else{
                $c_list_tmp[$v['ct_storeid']][$v['ct_itemid']] = $goods_contractstate_arr['close']['sign'];
            }
        }

        //整理更新数据
        $goods_commonidarr = array();
        foreach ($c_list_tmp as $s_k=>$s_v) {
            $update_arr = array();
            foreach ($s_v as $item_k=>$item_v) {
                $update_arr["contract_$item_k"] = $item_v;
            }
            $result = $model_goods->editGoodsById($update_arr, array_keys($store_goods_list[$s_k]));
            if ($result){
                foreach ($store_goods_list[$s_k] as $g_k=>$g_v) {
                    $goods_commonidarr[] = $g_v['goods_commonid'];
                }
                array_unique($goods_commonidarr);
            }
        }

        if ($goods_commonidarr){
            // 返回执行成功的cronid
            foreach ($cron as $k=>$v) {
                if (in_array($k, $goods_commonidarr)) {
                    $cronid[] = $v['id'];
                }
            }
        }
        if ($cronid){
            // 返回执行成功的cronid
            return $cronid;
        } else {
            return false;
        }
    }

    /**
     * 手机专享过期
     *
     * @param array $cron
     */
    private function _cron_10($cron = array()) {
        $condition = array('store_id' => array('in', array_keys($cron)));
        $update = Model('p_sole')->editSoleClose($condition);
        if ($update){
            //返回执行成功的cronid
            $cronid = array();
            foreach ($cron as $v) {
                $cronid[] = $v['id'];
            }
        }else{
            return false;
        }
        return $cronid;
    }
}
