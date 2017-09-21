<?php
/**
 * 投诉对话模型
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');
class complain_talkModel extends Model{

    /*
     * 构造条件
     */
    private function getCondition($condition){
        $condition_str = '' ;
        if(!empty($condition['complain_id'])) {
            $condition_str .= " and complain_id = '{$condition['complain_id']}'";
        }
        if(!empty($condition['talk_id'])) {
            $condition_str .= " and talk_id = '{$condition['talk_id']}'";
        }
        return $condition_str;
    }

    /*
     * 增加
     * @param array $param
     * @return bool
     */
    public function saveComplainTalk($param){

        return Db::insert('complain_talk',$param) ;

    }

    /*
     * 更新
     * @param array $update_array
     * @param array $where_array
     * @return bool
     */
    public function updateComplainTalk($update_array, $where_array){

        $where = $this->getCondition($where_array) ;
        return Db::update('complain_talk',$update_array,$where) ;

    }

    /*
     * 删除
     * @param array $param
     * @return bool
     */
    public function dropComplainTalk($param){

        $where = $this->getCondition($param) ;
        return Db::delete('complain_talk', $where) ;

    }

    /*
     *  获得列表
     *  @param array $condition
     *  @param obj $page    //分页对象
     *  @return array
     */
    public function getComplainTalk($condition='',$page='',$field='*'){

        $param = array() ;
        $param['table'] = 'complain_talk' ;
        $param['field'] = $field;
        $param['where'] = $this->getCondition($condition);
        $param['order'] = $condition['order'] ? $condition['order']: ' talk_id desc ';
        return Db::select($param,$page) ;

    }

}
