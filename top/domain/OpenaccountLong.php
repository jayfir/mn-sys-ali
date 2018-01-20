<?php

namespace jayfir\ali\top\domain;

/**
 * Open Account模型
 * @author auto create
 */
class OpenaccountLong extends \yii\base\Model
{

    /**
     * 返回码
     * */
    public $code;

    /**
     * 返回数据
     * */
    public $data;

    /**
     * 返回信息
     * */
    public $message;

    /**
     * 是否成功
     * */
    public $successful;

}

?>