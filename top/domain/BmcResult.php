<?php

namespace jayfir\ali\top\domain;

/**
 * 通用结果
 * @author auto create
 */ 
class BmcResult extends \yii\base\Model
{

    /**
     * 结果code
     * */
    public $code;

    /**
     * 返回数据
     * */
    public $data;

    /**
     * 发送结果
     * */
    public $datas;

    /**
     * 信息
     * */
    public $message;

    /**
     * 是否成功
     * */
    public $successful;

}

?>