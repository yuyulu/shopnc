<?php
/**
 * 手机端令牌模型
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */

defined('In33hao') or exit('Access Invalid!');

class mb_user_tokenModel extends Model{
    public function __construct(){
        parent::__construct('mb_user_token');
    }

    /**
     * 查询
     *
     * @param array $condition 查询条件
     * @return array
     */
    public function getMbUserTokenInfo($condition) {
        return $this->where($condition)->find();
    }

    public function getMbUserTokenInfoByToken($token) {
        if(empty($token)) {
            return null;
        }
        return $this->getMbUserTokenInfo(array('token' => $token));
    }

    public function updateMemberOpenId($token, $openId)
    {
        return $this->where(array(
            'token' => $token,
        ))->update(array(
            'openid' => $openId,
        ));
    }

    /**
     * 新增
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function addMbUserToken($param){
        return $this->insert($param);
    }

    /**
     * 删除
     *
     * @param int $condition 条件
     * @return bool 布尔类型的返回结果
     */
    public function delMbUserToken($condition){
        return $this->where($condition)->delete();
    }
}
