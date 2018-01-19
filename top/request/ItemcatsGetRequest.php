<?php

namespace jayfir\ali\top\request;

use jayfir\ali\top\RequestCheckUtil;

/**
 * @filename ItemcatsGetRequest.php
 * 
 * @encoding UTF-8
 * @author jinhuiZhang <chinhui@coderfarmer.com>
 * @datetime 2018-1-19 14:11:34
 */
class ItemcatsGetRequest
{

    /**
     * 获取ID
     * @var type 
     */
    private $cids;

    /**
     * cid,parent_cid,name,is_parent
     * @var type 
     */
    private $fields;
    /*
     * 类目父级ID
     */
    private $parent_cid;
    private $apiParas = array();

    public function setCids($cids)
    {
        $this->cids = $cids;
    }

    public function getCids()
    {
        return $this->cids;
    }

    public function setParentCid($parent_cid)
    {
        $this->parent_cid = $parent_cid;
    }

    public function getParentCid()
    {
        return $this->parent_cid;
    }

    public function getApiMethodName()
    {
        return "taobao.itemcats.get";
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
        $this->apiParas["fields"] = $fields;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getApiParas()
    {
        return $this->apiParas;
    }

    public function check()
    {
        RequestCheckUtil::checkNotNull($this->fields, "fields");
    }

    public function putOtherTextParam($key, $value)
    {
        $this->apiParas[$key] = $value;
        $this->$key = $value;
    }

}
