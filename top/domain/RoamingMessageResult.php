<?php

namespace jayfir\ali\top\domain;

/**
 * 消息查询结果
 * @author auto create
 */
class RoamingMessageResult extends \yii\base\Model
{

    /**
     * 消息列表
     * */
    public $messages;

    /**
     * 下次迭代key
     * */
    public $next_key;

}

?>