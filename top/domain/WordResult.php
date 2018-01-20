<?php

namespace jayfir\ali\top\domain;

/**
 * 词法分析返回结果topResult和处理状态topStatus
 * @author auto create
 */
class WordResult extends \yii\base\Model
{

    /**
     * 返回文本处理内容
     * */
    public $top_result;

    /**
     * 返回结果为true则运行成功，为false则运行失败
     * */
    public $top_status;

}

?>