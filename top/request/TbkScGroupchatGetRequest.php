<?php

namespace jayfir\ali\top\request;

use jayfir\ali\top\RequestCheckUtil;

/**
 * TOP API: taobao.tbk.sc.groupchat.get request
 * 
 * @author auto create
 * @since 1.0, 2018.07.25
 */
class TbkScGroupchatGetRequest
{

    private $apiParas = array();

    public function getApiMethodName()
    {
        return "taobao.tbk.sc.groupchat.get";
    }

    public function getApiParas()
    {
        return $this->apiParas;
    }

    public function check()
    {
        
    }

    public function putOtherTextParam($key, $value)
    {
        $this->apiParas[$key] = $value;
        $this->$key = $value;
    }

}
