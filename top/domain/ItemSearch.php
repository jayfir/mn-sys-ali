<?php

namespace jayfir\ali\top\domain;

/**
 * 商品搜索结果信息
 * @author auto create
 */
class ItemSearch extends \yii\base\Model
{

    /**
     * 商品搜索分类
     * */
    public $item_categories;

    /**
     * 商品列表
     * */
    public $items;

}

?>