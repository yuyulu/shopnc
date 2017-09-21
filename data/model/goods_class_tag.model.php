<?php
/**
 * 商品类别TAG模型
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');

class goods_class_tagModel extends Model{
    public function __construct() {
        parent::__construct('goods_class_tag');
    }

    /**
     * 删除TAG分类
     *
     * @param array $condition
     * @return boolean
     */
    public function delGoodsClassTag($condition) {
        return $this->where($condition)->delete();
    }

    

    /**
     * TAG列表
     *
     * @param array $condition 检索条件
     * @param object $page
     * @return array 数组结构的返回结果
     */
    public function getTagList($condition = array() , $page = '', $field='*', $order = 'gc_tag_id asc'){
        return $this->field($field)->where($condition)->page($page)->order($order)->select();
    }

    /**
     * TAG添加
     *
     * @param array $param
     * @return bool
     */
    public function tagAdd($param){
        $class_id_1     = '';
        $class_id_2     = '';
        $class_id_3     = '';
        $class_name_1   = '';
        $class_name_2   = '';
        $class_name_3   = '';
        $class_id       = '';
        $type_id        = '';
        $condition_array  = array();

        if(is_array($param) && !empty($param)){ //一级
            foreach ($param as $value){
                $class_id_1     = $value['gc_id'];
                $class_name_1   = trim($value['gc_name']);
                $class_id       = $value['gc_id'];
                $type_id        = $value['type_id'];
                $class_id_2     = '';
                $class_id_3     = '';
                $class_name_2   = '';
                $class_name_3   = '';

                if(is_array($value['sub_class']) && !empty($value['sub_class'])){   //二级
                    foreach ($value['sub_class'] as $val){
                        $class_id_2     = $val['gc_id'];
                        $class_name_2   = trim($val['gc_name']);
                        $class_id       = $val['gc_id'];
                        $type_id        = $val['type_id'];

                        if(is_array($val['sub_class']) && !empty($val['sub_class'])){   //三级
                            foreach ($val['sub_class'] as $v){
                                $class_id_3     = $v['gc_id'];
                                $class_name_3   = trim($v['gc_name']);
                                $class_id       = $v['gc_id'];
                                $type_id        = $v['type_id'];

                                //合并成sql语句
                                $array = array();
                                $array['gc_id_1'] = $class_id_1;
                                $array['gc_id_2'] = $class_id_2;
                                $array['gc_id_3'] = $class_id_3;
                                $array['gc_tag_name'] = $class_name_1.'&nbsp;&gt;&nbsp;'.$class_name_2.'&nbsp;&gt;&nbsp;'.$class_name_3;
                                $array['gc_tag_value'] = $class_name_1.','.$class_name_2.','.$class_name_3;
                                $array['gc_id'] = $class_id;
                                $array['type_id'] = $type_id;
                                $condition_array[] = $array;
                            }
                        }else{
                            //合并成sql语句
                            $array = array();
                            $array['gc_id_1'] = $class_id_1;
                            $array['gc_id_2'] = $class_id_2;
                            $array['gc_id_3'] = 0;
                            $array['gc_tag_name'] = $class_name_1.'&nbsp;&gt;&nbsp;'.$class_name_2;
                            $array['gc_tag_value'] = $class_name_1.','.$class_name_2;
                            $array['gc_id'] = $class_id;
                            $array['type_id'] = $type_id;
                            $condition_array[] = $array;
                        }

                    }
                }else{
                    //合并成sql语句
                    $array = array();
                    $array['gc_id_1'] = $class_id_1;
                    $array['gc_id_2'] = 0;
                    $array['gc_id_3'] = 0;
                    $array['gc_tag_name'] = $class_name_1;
                    $array['gc_tag_value'] = $class_name_1;
                    $array['gc_id'] = $class_id;
                    $array['type_id'] = $type_id;
                    $condition_array[] = $array;
                }

            }
        }else{
            return false;
        }
        return $this->table('goods_class_tag')->insertAll($condition_array);
    }

    /**
     * TAG清空
     */
    public function clearTag(){
		return Db::query("TRUNCATE TABLE `".DBPRE."goods_class_tag`");
	}

    /**
     * TAG更新
     *
     * @param array $param
     * @return bool
     */
    public function updateTag($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $where = " gc_tag_id = '". $param['gc_tag_id'] ."'";
			$result = Db::update('goods_class_tag',$tmp,$where);
            return $result;
        }else {
            return false;
        }
    }

    /**
     * TAG删除
     *
     * @param string $id
     * @return bool
     */
    public function delTagByIds($id){
        if(!empty($id)) {
			return Db::delete('goods_class_tag',' gc_tag_id in ('.$id.')');
		}else{
            return false;
        }
    }

    /**
     * 根据条件删除
     *
     * @param array $condition
     * @return bool
     */
	public function delByCondition($condition){
		return Db::delete('goods_class_tag', $this->_condition($condition));
	}
	
    /**
     * TAG添加
     */
    public function addOneTag($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
			$result = Db::insert('goods_class_tag',$tmp);
            return $result;
        }else {
            return false;
        }
    }

    /**
     * 构造检索条件
     *
     * @param array $condition
     * @return string 字符串类型的返回结果
     */
    private function _condition($condition = array()){
        $condition_str = '';

        if ($condition['gc_parent_id'] != ''){
            $condition_str .= " and gc_parent_id = '". intval($condition['gc_parent_id']) ."'";
        }
        if ($condition['gc_tag_id'] != ''){
            $condition_str .= " and gc_tag_id = '".$condition['gc_tag_id']."'";
        }
        if ($condition['in_tag_id'] != ''){
            $condition_str .= " and gc_tag_id in (".$condition['in_tag_id'].")";
        }
        if ($condition['gc_tag_value'] != ''){
            $condition_str .= " and gc_tag_value = '".$condition['gc_tag_value']."'";
        }
        if ($condition['gc_condition'] != ''){
            $condition_str .= " and ( gc_id_1='".$condition['gc_condition']."' or gc_id_2='".$condition['gc_condition']."' or gc_id_3='".$condition['gc_condition']."')";
        }
        if($condition['gc_id'] != '') {
            $condition_str .= " and gc_id = '{$condition['gc_id']}'";
        }
        if($condition['gc_id_1'] != '') {
            $condition_str .= " and gc_id_1 = '{$condition['gc_id_1']}'";
        }
        if($condition['gc_id_2'] != '') {
            $condition_str .= " and gc_id_2 = '{$condition['gc_id_2']}'";
        }
        if($condition['gc_id_3'] != '') {
            $condition_str .= " and gc_id_3 = '{$condition['gc_id_3']}'";
        }
        if($condition['type_id'] != '') {
            $condition_str .= " and type_id = '{$condition['type_id']}'";
        }

        return $condition_str;
    }
}
