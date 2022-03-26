<?php
/**
 * +----------------------------------------------------------------------
 * | uiadmin [ 渐进式快速开发接口后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2020 http://jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
 * | 
*/
namespace demo\blog\model;

use think\Model;
use think\model\concern\SoftDelete;

class Post extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table =  'xy_demo_blog_post';

    // 数据转换为驼峰命名
    protected $convertNameToCamel = true;

    public static function init()
    {
        parent::init();
    }

    // 软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;
}
