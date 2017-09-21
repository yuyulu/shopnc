<?php
/**
 * 微信支付接口类
 * JSAPI 适用于微信内置浏览器访问WAP时支付
 *
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');

/**
 * @todo
 */
class wxpay_jsapi
{
    const DEBUG = 0;

    protected $config;

    public function __construct()
    {
        $this->config = (object) array(
            'appId' => '',
            'appSecret' => '',
            'partnerId' => '',
            'apiKey' => '',

            'notifyUrl' => MOBILE_SITE_URL . '/api/payment/wxpay_jsapi/notify_url.php',
            'finishedUrl' => WAP_SITE_URL . '/tmpl/member/payment_result.html?_=2&attach=_attach_',
            'undoneUrl' => WAP_SITE_URL . '/tmpl/member/payment_result_failed.html?_=2&attach=_attach_',

            'orderSn' => date('YmdHis'),
            'orderInfo' => 'Test wxpay js api',
            'orderFee' => 1,
            'orderAttach' => '_',
        );
    }

    public function setConfig($name, $value)
    {
        $this->config->$name = $value;
    }

    public function setConfigs(array $params)
    {
        foreach ($params as $name => $value) {
            $this->config->$name = $value;
        }
    }

    public function notify()
    {
        try {
            $data = $this->onNotify();
            $resultXml = $this->arrayToXml(array(
                'return_code' => 'SUCCESS',
            ));

            if (self::DEBUG) {
                file_put_contents(__DIR__ . '/log.txt', var_export($data, true), FILE_APPEND | LOCK_EX);
            }

        } catch (Exception $ex) {

            $data = null;
            $resultXml = $this->arrayToXml(array(
                'return_code' => 'FAIL',
                'return_msg' => $ex->getMessage(),
            ));

            if (self::DEBUG) {
                file_put_contents(__DIR__ . '/log_err.txt', $ex . PHP_EOL, FILE_APPEND | LOCK_EX);
            }

        }

        return array(
            $data,
            $resultXml,
        );
    }

    public function onNotify()
    {
        $d = $this->xmlToArray(file_get_contents('php://input'));

        if (empty($d)) {
            throw new Exception(__METHOD__);
        }

        if ($d['return_code'] != 'SUCCESS') {
            throw new Exception($d['return_msg']);
        }

        if ($d['result_code'] != 'SUCCESS') {
            throw new Exception("[{$d['err_code']}]{$d['err_code_des']}");
        }

        if (!$this->verify($d)) {
            throw new Exception("Invalid signature");
        }

        return $d;
    }

    public function verify(array $d)
    {
        if (empty($d['sign'])) {
            return false;
        }

        $sign = $d['sign'];
        unset($d['sign']);

        return $sign == $this->sign($d);
    }

    protected $control;

    public function paymentHtml($control = null)
    {
        $this->control = $control;
        $prepayId = $this->getPrepayId();
        
        $params = array();
        $params['appId'] = $this->config->appId;
        $params['timeStamp'] = '' . time();
        $params['nonceStr'] = md5(uniqid(mt_rand(), true));
        $params['package'] = 'prepay_id=' . $prepayId;
        $params['signType'] = 'MD5';

        $sign = $this->sign($params);
        $params['paySign'] = $sign;

        // @todo timestamp
        $jsonParams = json_encode($params);

        return <<<EOB
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
<title>微信安全支付</title>
</head>
<body>
正在加载…
<script type="text/javascript">
function jsApiCall() {
    WeixinJSBridge.invoke(
        'getBrandWCPayRequest',
        {$jsonParams},
        function(res) {
            var h;
            if (res && res.err_msg == "get_brand_wcpay_request:ok") {
                // success;
                h = '{$this->config->finishedUrl}';
            } else {
                // fail;
                alert(res && res.err_msg);
                h = '{$this->config->undoneUrl}';
            }
            location.href = h.replace('_attach_', '{$this->config->orderAttach}');
        }
    );
}
window.onload = function() {
    if (typeof WeixinJSBridge == "undefined") {
        if (document.addEventListener) {
            document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
        } else if (document.attachEvent) {
            document.attachEvent('WeixinJSBridgeReady', jsApiCall);
            document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
        }
    } else {
        jsApiCall();
    }
}
</script>
</body>
</html>
EOB;
    }

    protected function getOpenId()
    {
        if ($c = $this->control) {
            $openId = $c->getOpenId();
            if ($openId) {
                return $openId;
            }

            // through multiple requests
            $openId = $this->getOpenIdThroughMultipleRequests();
            $c->setOpenId($openId);
            return $openId;
        }
        return $this->getOpenIdThroughMultipleRequests();
    }

    public function getPrepayId()
    {
        // ...
        $openId = $this->getOpenId();

        $data = array();
        $data['appid'] = $this->config->appId;
        $data['mch_id'] = $this->config->partnerId;
        $data['nonce_str'] = md5(uniqid(mt_rand(), true));
        $data['body'] = $this->config->orderInfo;
        $data['attach'] = $this->config->orderAttach;
        $data['out_trade_no'] = $this->config->orderSn;
        $data['total_fee'] = $this->config->orderFee;
        $data['spbill_create_ip'] = $_SERVER['REMOTE_ADDR'];
        $data['notify_url'] = $this->config->notifyUrl;
        $data['trade_type'] = 'JSAPI';
        $data['openid'] = $openId;

        $sign = $this->sign($data);
        $data['sign'] = $sign;

        $result = $this->postXml('https://api.mch.weixin.qq.com/pay/unifiedorder', $data);

        if ($result['return_code'] != 'SUCCESS') {
            throw new Exception($result['return_msg']);
        }

        if ($result['result_code'] != 'SUCCESS') {
            throw new Exception("[{$result['err_code']}]{$result['err_code_des']}");
        }

        return $result['prepay_id'];
    }

    public function getOpenIdThroughMultipleRequests()
    {
        if (empty($_GET['code'])) {
            if (isset($_GET['state']) && $_GET['state'] == 'redirected') {
                throw new Exception('Auth failed');
            }
            $url = sprintf(
                'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=redirected#wechat_redirect',
                $this->config->appId,
                urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])
            );
            header('Location: ' . $url);
            exit;
        }
		$rurl = sprintf(
            'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code',
            $this->config->appId,
            $this->config->appSecret,
            $_GET['code']
        );
        //$d = json_decode(file_get_contents($rurl), true);
		$res = $this->wxcurl_get($rurl);
		$d = json_decode($res, true);
        if (empty($d)) {
            throw new Exception(__METHOD__);
        }

        if (empty($d['errcode']) && isset($d['openid'])) {
            return $d['openid'];
        }

        throw new Exception(var_export($d, true));
    }

	public function wxcurl_get($url){
		$chs = curl_init();
        curl_setopt($chs, CURLOPT_TIMEOUT, 30);
        curl_setopt($chs, CURLOPT_URL, $url);
        curl_setopt($chs, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($chs, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($chs, CURLOPT_HEADER, FALSE);
        curl_setopt($chs, CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($chs);
		if (!$response) {
            throw new Exception('CURL Error: ' . curl_errno($ch));
        }
        curl_close($ch);
		return $response;
	}

    public function sign(array $data)
    {
        ksort($data);

        $a = array();
        foreach ($data as $k => $v) {
            if ((string) $v === '') {
                continue;
            }
            $a[] = "{$k}={$v}";
        }

        $a = implode('&', $a);
        $a .= '&key=' . $this->config->apiKey;

        return strtoupper(md5($a));
    }

    public function postXml($url, array $data)
    {
        // pack xml
        $xml = $this->arrayToXml($data);

        // curl post
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $response = curl_exec($ch);
        if (!$response) {
            throw new Exception('CURL Error: ' . curl_errno($ch));
        }
        curl_close($ch);

        // unpack xml
        return $this->xmlToArray($response);
    }

    public function arrayToXml(array $data)
    {
        $xml = "<xml>";
        foreach ($data as $k => $v) {
            if (is_numeric($v)) {
                $xml .= "<{$k}>{$v}</{$k}>";
            } else {
                $xml .= "<{$k}><![CDATA[{$v}]]></{$k}>";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    public function xmlToArray($xml)
    {
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

}
