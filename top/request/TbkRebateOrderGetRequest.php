<?php

namespace jayfir\ali\top\request;

use jayfir\ali\top\RequestCheckUtil;

/**
 * @filename TbkRebateOrderGetRequest.php
 * 
 * @encoding UTF-8
 * @author jinhuiZhang <chinhui@coderfarmer.com>
 * @datetime 2018-2-9 15:20:49
 */
class TbkRebateOrderGetRequest
{

    /**
     * 需返回的字段列表
     *  tb_trade_parent_id,tb_trade_id,num_iid,item_title,item_num,price,pay_price,seller_nick,seller_shop_title,commission,commission_rate,unid,create_time,earning_time
     * */
    private $fields;

    /**
     * 订单结算开始时间
     * @var type 
     */
    private $startTime;

    /**
     * 订单查询时间范围,单位:秒,最小60,最大600,默认60
     * @var type 
     */
    private $span;

    /**
     * 第几页，默认1，1~100
     * @var type 
     */
    private $pageNo;

    /**
     * 页大小，默认20，1~100
     * @var type 
     */
    private $pageSize;
    private $apiParas = array();

    public function setFields($fields)
    {
        $this->fields = $fields;
        $this->apiParas["fields"] = $fields;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getApiMethodName()
    {
        return "taobao.tbk.rebate.order.get";
    }

    public function getApiParas()
    {
        return $this->apiParas;
    }

    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
        $this->apiParas["start_time"] = $startTime;
    }

    public function getStartTime()
    {
        return $this->startTime;
    }

    public function setSpan($span)
    {
        $this->span = $span;
        $this->apiParas["span"] = $span;
    }

    public function getSpan()
    {
        return $this->span;
    }

    public function setPageNo($pageNo)
    {
        $this->pageNo = $pageNo;
        $this->apiParas["page_no"] = $pageNo;
    }

    public function getPageNo()
    {
        return $this->pageNo;
    }

    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
        $this->apiParas["page_size"] = $pageSize;
    }

    public function check()
    {
        RequestCheckUtil::checkNotNull($this->fields, "fields");
        RequestCheckUtil::checkNotNull($this->startTime, "startTime");
        RequestCheckUtil::checkNotNull($this->span, "span");
    }

    public function putOtherTextParam($key, $value)
    {
        $this->apiParas[$key] = $value;
        $this->$key = $value;
    }

}
