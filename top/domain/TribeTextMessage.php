<?php

namespace jayfir\ali\top\domain;

/**
 * 消息列表
 * @author auto create
 */
class TribeTextMessage extends \yii\base\Model
{

    /**
     * 发送方userid。必须为本app已导入的账号
     * */
    public $from_id;

    /**
     * 消息
     * */
    public $message;

    /**
     * 消息时间。UTC时间，精确到秒。时间范围必须在当前时间30天内
     * */
    public $time;

}

?>