<?php

namespace jayfir\ali\top\domain;

/**
 * Open Account申请token的结果
 * @author auto create
 */
class OpenAccountTokenApplyResult extends \yii\base\Model
{

    /**
     * 错误码
     * */
    public $code;

    /**
     * token
     * */
    public $data;

    /**
     * 错误信息
     * */
    public $message;

    /**
     * 是否成功
     * */
    public $successful;

}

?>