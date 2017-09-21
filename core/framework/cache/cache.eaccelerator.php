<?php
/**
 * eAccelerator 缓存
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */
defined('In33hao') or exit('Access Invalid!');

class CacheEaccelerator extends Cache{

	private $prefix;
	private $type;

	public function __construct(){
        if ( !extension_loaded('eAccelerator') ) {
            throw_exception('eAccelerator failed to load');
        }
        $this->prefix= $this->config['prefix'] ? $this->config['prefix'] : substr(md5($_SERVER['HTTP_HOST']), 0, 6).'_';
	}

    public function get($key, $type='') {
		$this->type = $type;
        return eaccelerator_get($this->_key($key));
    }

 	public function set($key, $value, $type='', $ttl = SESSION_EXPIRE){
		$this->type = $type;
		$name = $this->_key($key);
        eaccelerator_lock($name);
        if(eaccelerator_put($name, $value, $ttl)) {
            return true;
        }
        return false;
	}

	public function rm($key, $type=''){
		$this->type = $type;
		return eaccelerator_rm($this->_key($key));
	}

   	private function _key($str) {
		return $this->prefix.$this->type.$str;
	}
}
?>