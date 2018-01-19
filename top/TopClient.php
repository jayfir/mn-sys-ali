<?php

namespace jayfir\ali\top;

use jayfir\ali\top\TopLogger;
use jayfir\ali\top\ResultSet;
use Yii;
use yii\base\Component;

class TopClient extends Component
{

    public $appkey;
    public $secretKey;
    public $gatewayUrl = "http://gw.api.taobao.com/router/rest";
    public $format = "xml";
    public $connectTimeout;
    public $readTimeout;

    /** 是否打开入参check* */
    public $checkRequest = true;
    protected $signMethod = "md5";
    protected $apiVersion = "2.0";
    protected $sdkVersion = "top-sdk-php-20151012";

    public function getAppkey()
    {
        return $this->appkey;
    }

    protected function generateSign($params)
    {
        ksort($params);
        $stringToBeSigned = $this->secretKey;
        foreach ($params as $k => $v) {
            if (is_string($v) && "@" != substr($v, 0, 1)) {
                $stringToBeSigned .= "$k$v";
            }
        }
        unset($k, $v);
        $stringToBeSigned .= $this->secretKey;
        return strtoupper(md5($stringToBeSigned));
    }

    public function curl($url, $postFields = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($this->readTimeout) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->readTimeout);
        }
        if ($this->connectTimeout) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
        }
        curl_setopt($ch, CURLOPT_USERAGENT, "top-sdk-php");
        //https 请求
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        if (is_array($postFields) && 0 < count($postFields)) {
            $postBodyString = "";
            $postMultipart = false;
            foreach ($postFields as $k => $v) {
                if (!is_string($v))
                    continue;

                if ("@" != substr($v, 0, 1)) {//判断是不是文件上传
                    $postBodyString .= "$k=" . urlencode($v) . "&";
                } else {//文件上传用multipart/form-data，否则用www-form-urlencoded
                    $postMultipart = true;
                    if (class_exists('\CURLFile')) {
                        $postFields[$k] = new \CURLFile(substr($v, 1));
                    }
                }
            }
            unset($k, $v);
            curl_setopt($ch, CURLOPT_POST, true);
            if ($postMultipart) {
                if (class_exists('\CURLFile')) {
                    curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
                } else {
                    if (defined('CURLOPT_SAFE_UPLOAD')) {
                        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
                    }
                }
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            } else {
                $header = array("content-type: application/x-www-form-urlencoded; charset=UTF-8");
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString, 0, -1));
            }
        }
        $reponse = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new Exception($reponse, $httpStatusCode);
            }
        }
        curl_close($ch);
        return $reponse;
    }

    public function curl_with_memory_file($url, $postFields = null, $fileFields = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($this->readTimeout) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->readTimeout);
        }
        if ($this->connectTimeout) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
        }
        curl_setopt($ch, CURLOPT_USERAGENT, "top-sdk-php");
        //https 请求
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        //生成分隔符
        $delimiter = '-------------' . uniqid();
        //先将post的普通数据生成主体字符串
        $data = '';
        if ($postFields != null) {
            foreach ($postFields as $name => $content) {
                $data .= "--" . $delimiter . "\r\n";
                $data .= 'Content-Disposition: form-data; name="' . $name . '"';
                //multipart/form-data 不需要urlencode，参见 http:stackoverflow.com/questions/6603928/should-i-url-encode-post-data
                $data .= "\r\n\r\n" . $content . "\r\n";
            }
            unset($name, $content);
        }

        //将上传的文件生成主体字符串
        if ($fileFields != null) {
            foreach ($fileFields as $name => $file) {
                $data .= "--" . $delimiter . "\r\n";
                $data .= 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $file['name'] . "\" \r\n";
                $data .= 'Content-Type: ' . $file['type'] . "\r\n\r\n"; //多了个文档类型

                $data .= $file['content'] . "\r\n";
            }
            unset($name, $file);
        }
        //主体结束的分隔符
        $data .= "--" . $delimiter . "--";

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: multipart/form-data; boundary=' . $delimiter,
            'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $reponse = curl_exec($ch);
        unset($data);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new Exception($reponse, $httpStatusCode);
            }
        }
        curl_close($ch);
        return $reponse;
    }

    protected function logCommunicationError($apiName, $requestUrl, $errorCode, $responseTxt)
    {
        $localIp = isset($_SERVER["SERVER_ADDR"]) ? $_SERVER["SERVER_ADDR"] : "CLI";
        $logger = new TopLogger();
        $logger->conf["log_file"] = Yii::$app->getRuntimePath() . "/ali/logs/top_comm_err_" . $this->appkey . "_" . date("Y-m-d") . ".log";
        $logger->conf["separator"] = "^_^";
        $logData = array(
            date("Y-m-d H:i:s"),
            $apiName,
            $this->appkey,
            $localIp,
            PHP_OS,
            $this->sdkVersion,
            $requestUrl,
            $errorCode,
            str_replace("\n", "", $responseTxt)
        );
        $logger->log($logData);
    }

    public function execute($request, $session = null, $bestUrl = null)
    {
        $result = new ResultSet();
        if ($this->checkRequest) {
            try {
                $request->check();
            } catch (Exception $e) {

                $result->code = $e->getCode();
                $result->msg = $e->getMessage();
                return $result;
            }
        }
        //组装系统参数
        $sysParams["app_key"] = $this->appkey;
        $sysParams["v"] = $this->apiVersion;
        $sysParams["format"] = $this->format;
        $sysParams["sign_method"] = $this->signMethod;
        $sysParams["method"] = $request->getApiMethodName();
        $sysParams["timestamp"] = date("Y-m-d H:i:s");
        if (null != $session) {
            $sysParams["session"] = $session;
        }
        $apiParams = array();
        //获取业务参数
        $apiParams = $request->getApiParas();


        //系统参数放入GET请求串
        if ($bestUrl) {
            $requestUrl = $bestUrl . "?";
            $sysParams["partner_id"] = $this->getClusterTag();
        } else {
            $requestUrl = $this->gatewayUrl . "?";
            $sysParams["partner_id"] = $this->sdkVersion;
        }
        //签名
        $sysParams["sign"] = $this->generateSign(array_merge($apiParams, $sysParams));

        foreach ($sysParams as $sysParamKey => $sysParamValue) {
            // if(strcmp($sysParamKey,"timestamp") != 0)
            $requestUrl .= "$sysParamKey=" . urlencode($sysParamValue) . "&";
        }

        $fileFields = array();
        foreach ($apiParams as $key => $value) {
            if (is_array($value) && array_key_exists('type', $value) && array_key_exists('content', $value)) {
                $value['name'] = $key;
                $fileFields[$key] = $value;
                unset($apiParams[$key]);
            }
        }

        // $requestUrl .= "timestamp=" . urlencode($sysParams["timestamp"]) . "&";
        $requestUrl = substr($requestUrl, 0, -1);

        //发起HTTP请求
        try {
            if (count($fileFields) > 0) {
                $resp = $this->curl_with_memory_file($requestUrl, $apiParams, $fileFields);
            } else {
                $resp = $this->curl($requestUrl, $apiParams);
            }
        } catch (Exception $e) {
            $this->logCommunicationError($sysParams["method"], $requestUrl, "HTTP_ERROR_" . $e->getCode(), $e->getMessage());
            $result->code = $e->getCode();
            $result->msg = $e->getMessage();
            return $result;
        }

        unset($apiParams);
        unset($fileFields);
        //解析TOP返回结果
        $respWellFormed = false;
        if ("json" == $this->format) {
            $respObject = json_decode($resp);
            if (null !== $respObject) {
                $respWellFormed = true;
                foreach ($respObject as $propKey => $propValue) {
                    $respObject = $propValue;
                }
            }
        } else if ("xml" == $this->format) {
            $respObject = @simplexml_load_string($resp);
            if (false !== $respObject) {
                $respWellFormed = true;
            }
        }

        //返回的HTTP文本不是标准JSON或者XML，记下错误日志
        if (false === $respWellFormed) {
            $this->logCommunicationError($sysParams["method"], $requestUrl, "HTTP_RESPONSE_NOT_WELL_FORMED", $resp);
            $result->code = 0;
            $result->msg = "HTTP_RESPONSE_NOT_WELL_FORMED";
            return $result;
        }

        //如果TOP返回了错误码，记录到业务错误日志中
        if (isset($respObject->code)) {
            $logger = new TopLogger();
            $logger->conf["log_file"] = Yii::$app->getRuntimePath() . "/ali/logs/top_biz_err_" . $this->appkey . "_" . date("Y-m-d") . ".log";
            $logger->log(array(
                date("Y-m-d H:i:s"),
                $resp
            ));
        }
        return self::objectToArray($respObject);
    }

    public function exec($paramsArray)
    {
        if (!isset($paramsArray["method"])) {
            trigger_error("No api name passed");
        }
        $inflector = new LtInflector;
        $inflector->conf["separator"] = ".";
        $requestClassName = ucfirst($inflector->camelize(substr($paramsArray["method"], 7))) . "Request";
        if (!class_exists($requestClassName)) {
            trigger_error("No such api: " . $paramsArray["method"]);
        }

        $session = isset($paramsArray["session"]) ? $paramsArray["session"] : null;

        $req = new $requestClassName;
        foreach ($paramsArray as $paraKey => $paraValue) {
            $inflector->conf["separator"] = "_";
            $setterMethodName = $inflector->camelize($paraKey);
            $inflector->conf["separator"] = ".";
            $setterMethodName = "set" . $inflector->camelize($setterMethodName);
            if (method_exists($req, $setterMethodName)) {
                $req->$setterMethodName($paraValue);
            }
        }
        return $this->execute($req, $session);
    }

    private function getClusterTag()
    {
        return substr($this->sdkVersion, 0, 11) . "-cluster" . substr($this->sdkVersion, 11);
    }

    /**
     * 对象转数组
     * @param type $o
     * @return type
     */
    public static function objectToArray($o)
    {
        if (is_object($o))
            $o = get_object_vars($o);
        if (is_array($o))
            foreach ($o as $k => $v)
                $o[$k] = self::ObjectToArray($v);
        return $o;
    }

    /**
     * 获取详情
     * @staticvar array $_HwsMobileTaobaoApi
     * @param type $item_id
     * @return string|array
     */
    public function getItemDetail($item_id)
    {
        static $_HwsMobileTaobaoApi = array();
        if (empty($item_id)) {
            return '';
        }
        if (isset($_HwsMobileTaobaoApi[$item_id])) {
            return $_HwsMobileTaobaoApi[$item_id];
        }
        $html = $this->getTaoBaoHtml("http://hws.m.taobao.com/cache/wdetail/5.0/?id={$item_id}&ttid=2013@taobao_h5_1.0.0&exParams={}");
        if (empty($html)) {
            $html = file_get_contents("http://hws.m.taobao.com/cache/wdetail/5.0/?id={$item_id}&ttid=2013@taobao_h5_1.0.0&exParams={}");
        }
        if (strlen(trim($html))) {
            $json = json_decode($html, true);
            return $json;
        }
        return '';
    }

    /**
     *  抓取详情页数据
     * @param type $url
     * @param type $httpheader
     * @return boolean
     */
    public function getTaoBaoHtml(
    $url, $httpheader = array(
        "User-Agent: {Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0}",
        "Accept: {text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8}",
        "Accept-Language: {zh-cn,zh;q=0.8,en-us;q=0.5,en;q=0.3}",
        "Cookie:cna=Mp87DcqkeAwCATs5682P1/t2; thw=cn; miid=5491147790075499745; ck1=; uc3=nk2=saDLt6w%2B5Gs%3D&id2=UondEpIVVplj&vt3=F8dATkOLPpokm6bGDhE%3D&lg2=W5iHLLyFOGW7aA%3D%3D; unt=%E4%B8%80%E5%8F%B6%E6%A9%99%E5%84%BF%26center; lgc=%5Cu4E00%5Cu53F6%5Cu6A59%5Cu513F; tracknick=%5Cu4E00%5Cu53F6%5Cu6A59%5Cu513F; mt=np=&ci=94_1; _cc_=V32FPkk%2Fhw%3D%3D; tg=0; lzstat_uv=12528993053507599788|3492151@3258512@2043323@3045821@2948565@2805963; ali_ab=121.204.248.172.1422326386252.4; v=0; _m_h5_tk=c9495908692264fd9118e9a136e1f7ce_1422594765215; _m_h5_tk_enc=30502dd9640fd62e29295ffd6cca9a9c; _tb_token_=OETIHpEHeXJG; uc1=cookie14=UoW1FqnZMKesHg%3D%3D; cookie2=1c9524764994b6a44b7acd1176ba8e6b; t=13a7987269b30f6d82e95e6a095b4fa8; supportWebp=false; isg=999825D4169DBF6CF5C2959B358C661E",
    )
    )
    {
        if (empty($url)) {
            return false;
        }
        $ch = curl_init();
        // 设置 url
        curl_setopt($ch, CURLOPT_URL, $url);
        //构造IP
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:127.0.0.1', 'CLIENT-IP:127.0.0.0'));
        //构造来路
        curl_setopt($ch, CURLOPT_REFERER, "http://www.taobao.com/ ");
        // 设置浏览器的特定header
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
        // 页面内容我们并不需要
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        // 只需返回HTTP header
        curl_setopt($ch, CURLOPT_HEADER, 0);
        // 返回结果，而不是输出它
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        ob_start();
        curl_exec($ch);
        $html = ob_get_contents();
        ob_end_clean();
        curl_close($ch);
        return $html;
    }

}
