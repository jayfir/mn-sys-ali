<?php

namespace jayfir\ali\top\domain;

/**
 * 消息序列
 * @author auto create
 */
class TextMessage extends \yii\base\Model
{

    /**
     * 发送方userid
     * */
    public $from_id;

    /**
     * 消息
     * */
    public $message;

    /**
     * 消息时间。UTC时间，精确到秒。必须在一个月内
     * */
    public $time;

    /**
     * 接受方userid
     * */
    public $to_id;

}

?>