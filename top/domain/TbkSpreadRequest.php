<?php

namespace jayfir\ali\top\domain;

/**
 * 请求列表，内部包含多个url
 * @author auto create
 */ 
class TbkSpreadRequest  extends \yii\base\Model
{

    /**
     * 原始url, 只支持uland.taobao.com，s.click.taobao.com， ai.taobao.com，temai.taobao.com的域名转换，否则判错
     * */
    public $url;

}

?>