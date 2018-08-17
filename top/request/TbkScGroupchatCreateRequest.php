<?php

namespace jayfir\ali\top\request;

use jayfir\ali\top\RequestCheckUtil;

/**
 * TOP API: taobao.tbk.sc.groupchat.create request
 * 
 * @author auto create
 * @since 1.0, 2018.07.25
 */
class TbkScGroupchatCreateRequest
{

    /**
     * 目前仅支持10，对应5000人群，之后有可能放开限制
     * */
    private $subGroupNum;

    /**
     * 群组名称
     * */
    private $title;
    private $apiParas = array();

    public function setSubGroupNum($subGroupNum)
    {
        $this->subGroupNum = $subGroupNum;
        $this->apiParas["sub_group_num"] = $subGroupNum;
    }

    public function getSubGroupNum()
    {
        return $this->subGroupNum;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        $this->apiParas["title"] = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getApiMethodName()
    {
        return "taobao.tbk.sc.groupchat.create";
    }

    public function getApiParas()
    {
        return $this->apiParas;
    }

    public function check()
    {

        RequestCheckUtil::checkNotNull($this->title, "title");
    }

    public function putOtherTextParam($key, $value)
    {
        $this->apiParas[$key] = $value;
        $this->$key = $value;
    }

}
