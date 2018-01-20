<?php

namespace jayfir\ali\top\domain;

/**
 * 返回结构
 * @author auto create
 */
class TribeMessageResult extends \yii\base\Model
{

    /**
     * 消息列表
     * */
    public $messages;

    /**
     * 迭代key
     * */
    public $next_key;

}

?>