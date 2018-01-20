<?php

namespace jayfir\ali\top\domain;

/**
 * 用户信息
 * @author auto create
 */
class OpenImUser extends \yii\base\Model
{

    /**
     * 账户appkey
     * */
    public $app_key;

    /**
     * 是否为淘宝账号
     * */
    public $taobao_account;

    /**
     * 用户id
     * */
    public $uid;

}

?>