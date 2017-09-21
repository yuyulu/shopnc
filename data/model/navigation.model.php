<?php
/**
 * 文件的简短描述
 *
 * 文件的详细描述
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');

class navigationModel extends Model {
    /**
     * 列表
     *
     * @param array $condition 检索条件
     * @param obj $page 分页
     * @return array 数组结构的返回结果
     */
    public function getNavigationList($condition,$page = ''){
        $condition_str = $this->_condition($condition);
        $param = array();
        $param['table'] = 'navigation';
        $param['where'] = $condition_str;
        $param['order'] = $condition['order'] ? $condition['order'] : 'nav_id';
        $result = Db::select($param,$page);
        return $result;
    }

    /**
     * 构造检索条件
     *
     * @param int $id 记录ID
     * @return string 字符串类型的返回结果
     */
    private function _condition($condition){
        $condition_str = '';

        if ($condition['like_nav_title'] != ''){
            $condition_str .= " and nav_title like '%". $condition['like_nav_title'] ."%'";
        }
        if ($condition['nav_location'] != ''){
            $condition_str .= " and nav_location = '". $condition['nav_location'] ."'";
        }

        return $condition_str;
    }

    /**
     * 取单个内容
     *
     * @param int $id ID
     * @return array 数组类型的返回结果
     */
    public function getOneNavigation($id){
        if (intval($id) > 0){
            $param = array();
            $param['table'] = 'navigation';
            $param['field'] = 'nav_id';
            $param['value'] = intval($id);
            $result = Db::getRow($param);
            return $result;
        }else {
            return false;
        }
    }

    /**
     * 新增
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function add($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $result = Db::insert('navigation',$tmp);
            return $result;
        }else {
            return false;
        }
    }

    /**
     * 更新信息
     *
     * @param array $param 更新数据
     * @return bool 布尔类型的返回结果
     */
    public function updates($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $where = " nav_id = '". $param['nav_id'] ."'";
            $result = Db::update('navigation',$tmp,$where);
            return $result;
        }else {
            return false;
        }
    }

    /**
     * 删除
     *
     * @return bool 布尔类型的返回结果
     */
    public function del($condition = array()){
        return $this->table('navigation')->where($condition)->delete();
    }
}
