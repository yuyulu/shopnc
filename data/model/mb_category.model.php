<?php
/**
 * 手机端广告
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */

defined('In33hao') or exit('Access Invalid!');

class mb_categoryModel extends Model {
    /**
     * 列表
     *
     * @param array $condition 检索条件
     * @param obj $page 分页
     * @return array 数组结构的返回结果
     */
    public function getLinkList($condition,$page=''){
        $param = array();
        $param['table'] = 'mb_category';
//      $param['where'] = $condition_str;
        $param['order'] = $condition['order'] ? $condition['order'] : 'gc_id';
        $result = Db::select($param,$page);
        return $result;
    }

    /**
     * 取单个内容
     *
     * @param int $id ID
     * @return array 数组类型的返回结果
     */
    public function getOneLink($id){
        if (intval($id) > 0){
            $param = array();
            $param['table'] = 'mb_category';
            $param['field'] = 'gc_id';
            $param['value'] = intval($id);
            $result = Db::getRow($param);
            return $result;
        }else {
            return false;
        }
    }
    /**
     * 取单个内容
     *
     * @param int $id ID
     * @return array 数组类型的返回结果
     */
    public function getCount(){
        return $this->getCount1('mb_category');
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
            $result = Db::insert('mb_category',$tmp);
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
            $where = " gc_id = '". $param['gc_id'] ."'";
            $result = Db::update('mb_category',$tmp,$where);
            return $result;
        }else {
            return false;
        }
    }

    /**
     * 删除
     *
     * @param int $id 记录ID
     * @return bool 布尔类型的返回结果
     */
    public function del($id){
        if (intval($id) > 0){
            $where = " gc_id = '". intval($id) ."'";
            $result = Db::delete('mb_category',$where);
            return $result;
        }else {
            return false;
        }
    }
}
