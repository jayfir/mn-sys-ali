<?php

namespace jayfir\ali\top\domain;

/**
 * 文本内容
 * @author auto create
 */
class Text extends \yii\base\Model
{

    /**
     * 文本内容
     * */
    public $content;

    /**
     * 业务处理ID
     * */
    public $id;

    /**
     * 文本类型1-评论 2-订单留言 9-其他
     * */
    public $type;

}

?>