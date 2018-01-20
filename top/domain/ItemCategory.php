<?php

namespace jayfir\ali\top\domain;

/**
 * 商品查询分类结果
 * @author auto create
 */
class ItemCategory extends \yii\base\Model
{ 

    /**
     * 分类ID
     * */
    public $category_id;

    /**
     * 分类名称
     * */
    public $category_name;

    /**
     * 商品数量
     * */
    public $count;

}

?>