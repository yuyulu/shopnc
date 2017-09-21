<?php
/**
 * 验证码
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */
defined('In33hao') or exit('Access Invalid!');

class apiseccodeModel extends Model {
    public function __construct() {
        parent::__construct('apiseccode');
    }
    public function addApiSeccode($key,$seccode) {
        /*if (C('cache_open')) {
            return $this->addApiSeccodeToCache($key,$seccode);
        }else{
            return $this->addApiSeccodeToData($key,$seccode);
        }*/
        return $this->addApiSeccodeToData($key,$seccode);
    }
    /**
     * 添加验证码信息至数据库
     * @param $key 验证码标识
     * @param $seccode 验证码值
     * @return boolean
     */
    private function addApiSeccodeToData($key,$seccode) {
        if(!($key && $seccode)){
            return false;
        }
        $insert_arr = array();
        $insert_arr['sec_key'] = $key;
        $insert_arr['sec_val'] = encrypt(strtoupper($seccode),MD5_KEY);
        $insert_arr['sec_addtime'] = time();
        return $this->table('apiseccode')->insert($insert_arr);
    }
    /**
     * 添加验证码信息至缓存
     * @param $key 验证码标识
     * @param $seccode 验证码值
     * @return boolean
     */
    /*private function addApiSeccodeToCache($key,$seccode) {
        if(!($key && $seccode)){
            return false;
        }
        wcache($key,array('sec_val'=>encrypt(strtoupper($seccode),MD5_KEY),'sec_addtime'=>time()),'apiseccode');
        return true;
    }*/
    /**
     * 验证验证码
     *
     * @param string $key 验证码标识
     * @param string $value 待验证值
     * @param boolean $is_runout 是否无论成功与否都失效
     * @return boolean
     */
    public function checkApiSeccode($key,$value,$is_runout=true){
        /*if (C('cache_open')) {
            return $this->checkApiSeccodeByCache($key,$value,$is_runout);
        }else{
            return $this->checkApiSeccodeByData($key,$value,$is_runout);
        }*/
        return $this->checkApiSeccodeByData($key,$value,$is_runout);
    }
    /**
     * 验证验证码
     *
     * @param string $key 验证码标识
     * @param string $value 待验证值
     * @param boolean $is_runout 是否无论成功与否都失效
     * @return boolean
     */
    private function checkApiSeccodeByData($key,$value,$is_runout=true){
        //删除过期验证码
        $this->dropByKey(array('sec_addtime'=>array('elt',time()-3600)));

        //查询验证码
        $info = $this->getInfoByKey($key);
        if (!$info) {
            return false;
        }
        //超时失效
        /*if (time() - $info['sec_addtime'] > 3600) {
            $this->dropByKey(array('sec_key'=>$key));
            return false;
        }*/
        //验证码是否正确
        $checkvalue = decrypt($info['sec_val'],MD5_KEY);
        $return = $checkvalue == strtoupper($value);
        if ($is_runout) {//无论成功与否都失效
            $this->dropByKey(array('sec_key'=>$key));
        }else{//当验证码验证失败失效
            if (!$return) $this->dropByKey(array('sec_key'=>$key));
        }
        return $return;
    }
    /**
     * 验证验证码
     *
     * @param string $key 验证码标识
     * @param string $value 待验证值
     * @param boolean $is_runout 是否无论成功与否都失效
     * @return boolean
     */
    /*private function checkApiSeccodeByCache($key,$value,$is_runout=true){
        $info = rcache($key, 'apiseccode');
        if (!$info) {
            return false;
        }
        //超时失效
        if (time() - $info['sec_addtime'] > 3600) {
            dcache($key, 'apiseccode');
            return false;
        }
        //验证码是否正确
        $checkvalue = decrypt($info['sec_val'],MD5_KEY);
        $return = $checkvalue == strtoupper($value);
        if ($is_runout) {//无论成功与否都失效
            dcache($key, 'apiseccode');
        }else{//当验证码验证失败失效
            if (!$return) dcache($key, 'apiseccode');
        }
        return $return;
    }*/
    /**
     * 获得验证码详情
     * @param string $key 验证码标识
     * @return boolean
     */
    public function getInfoByKey($key){
        if (!$key) {
            return false;
        }
        return $this->table('apiseccode')->where(array('sec_key'=>$key))->field('*')->find();
    }
    /**
     * 删除验证码
     * @param string $key 验证码标识
     * @return boolean
     */
    public function dropByKey($where){
        if (!$where) {
            return false;
        }
        return $this->table('apiseccode')->where($where)->delete();
    }
}
