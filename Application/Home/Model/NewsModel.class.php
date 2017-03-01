<?php

/**
 * Created by PhpStorm.
 * User: janke
 * Date: 17-3-1
 * Time: 下午9:07
 */
namespace Home\Model;
use Think\Model;
class NewsModel extends Model
{
    protected $trueTableName = 'news';
    protected $dbName = 'hmt_news';

    protected $fields = array(
        'id',
        'origin',
        'title',
        'href',
        'news_time',
        'last_modify_time',
        'content',
        'pic_urls'
    );
    protected $pk = 'id';
}