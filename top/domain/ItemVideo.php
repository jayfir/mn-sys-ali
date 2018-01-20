<?php

namespace jayfir\ali\top\domain;

/**
 * 商品视频
 * @author auto create
 */
class ItemVideo extends \yii\base\Model
{

    /**
     * 创建时间，格式:yyyy-MM-dd HH:mm:ss
     * */
    public $created;

    /**
     * 商品ID
     * */
    public $item_id;

    /**
     * 修改时间，格式:yyyy-MM-dd HH:mm:ss
     * */
    public $modified;

    /**
     * ItemVideo对应的Item的数字id
     * */
    public $num_iid;

    /**
     * 视频ID
     * */
    public $video_id;

}

?>