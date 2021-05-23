<?php
/**
 * +----------------------------------------------------------------------
 * | uniadmin [ 渐进式快速开发接口后台 ]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2018-2021 http://uniadmin.jiangruyi.com All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: jry <598821125@qq.com>
 * +----------------------------------------------------------------------
*/

namespace uniadmin\core\controller;

use think\Request;

/**
 * 核心控制器
 *
 * @author jry <ijry@qq.com>
 */
class Core
{

    /**
     * API对接
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function api()
    {
        // 获取接口信息
        $apiBase = request()->scheme() . '://' . $_SERVER['HTTP_HOST']  . request()->rootUrl();

        // 返回
        return json_encode(['code' => 200, 'msg' => '成功', 'data' => [
            'lang' => 'php',
            'framework' => 'thinkphp6.0',
            'name' => config('app.app_name'),
            'title' => config("uniadmin.name"),
            'stype' => '应用', // 菜单分组类型
            'version' => '1.2.0',
            // 'domainRoot' => request()->scheme() . '://' . $_SERVER['HTTP_HOST'] . request()->rootUrl(), // 主要给远程组件和iframe用
            'api' => [
                'apiBase' => $apiBase,
                'apiPrefix' => config("uniadmin.apiPrefix"),
                'apiLogin' => config("uniadmin.apiPrefix") . '/v1/admin/core/user/login',
                'apiAdmin' => config("uniadmin.apiPrefix") . '/v1/admin/core/index/index',
                'apiMenuTrees' => config("uniadmin.apiPrefix") . '/v1/admin/core/menu/trees',
                'apiConfig' => config("uniadmin.apiPrefix") . '/v1/core/index/siteInfo',
                'apiUserInfo' => config("uniadmin.apiPrefix") . '/v1/core/user/info'
            ],
            'siteInfo' => [
                'title' => config("uniadmin.name"),
                'logo' => config("uniadmin.logo"),
                'logoTitle' => config("uniadmin.logoTitle")
            ]
        ]], true);
    }

    /**
     * 获取站点信息
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function info()
    {
        // 返回数据
        return json_encode([
            'code' => 200,
            'msg'  => '成功',
            'data' => [
                'siteInfo' => [
                    'title' => config("uniadmin.name"),
                    'logo' => config("uniadmin.logo"),
                    'logoTitle' => config("uniadmin.logoTitle")
                ]
            ]
        ]);
    }

    /**
     * 后台首页
     *
     * @return \think\Response
     * @author jry <ijry@qq.com>
     */
    public function index()
    {
        // 系统信心
        $server_software = explode(' ', $_SERVER['SERVER_SOFTWARE']);
        $mysql_info = \think\facade\Db::query('SELECT VERSION() as mysql_version');

        // 首页自定义
        $dataList = [
            [
                'span' => 24,
                'type' => 'count',
                'content' => [
                    [
                        'item' => ['icon' => 'ivu-icon ivu-icon-md-contacts', 'bgColor' => '#2db7f5', 'title' => ''],
                        'current' => ['value' => $user_total, 'suffix' => ''],
                        'content' => ['value' => '注册用户']
                    ],
                    [
                        'item' => ['icon' => 'ivu-icon ivu-icon-md-person-add', 'bgColor' => '#19be6b', 'title' => ''],
                        'current' => ['value' => $user_today_count, 'suffix' => ''],
                        'content' => ['value' => '今日新增']
                    ],
                    [
                        'item' => ['icon' => 'ivu-icon ivu-icon-md-clock', 'bgColor' => '#ff9900', 'title' => ''],
                        'current' => ['value' => isset($pay_total) ? $pay_total : $user_week_count, 'suffix' => ''],
                        'content' => ['value' => isset($pay_total) ? '总消费' : '本周新增']
                    ],
                    [
                        'item' => ['icon' => 'ivu-icon ivu-icon-ios-paper-plane', 'bgColor' => '#ed4014', 'title' => ''],
                        'current' => ['value' => isset($pay_today_count) ? $pay_today_count : $user_month_count, 'suffix' => ''],
                        'content' => ['value' => isset($pay_today_count) ? '今日消费' : '本月新增']
                    ]
                ]
            ],
            [
                'span' => 12,
                'type' => 'card',
                'title' => '系统信息',
                'content' => [
                    [
                        'type'  => 'text',
                        'title' => '服务器IP',
                        'value' => $_SERVER['SERVER_ADDR']
                    ],
                    [
                        'type'  => 'text',
                        'title' => 'WEB服务器',
                        'value' => php_uname('s').php_uname('r') . '(' .$server_software[0] . ')'
                    ],
                    [
                        'type'  => 'text',
                        'title' => 'PHP版本信息',
                        'value' => PHP_VERSION . ' 上传限制：' . ini_get('upload_max_filesize')
                    ],
                    [
                        'type'  => 'text',
                        'title' => '数据库信息',
                        'value' => config('database.type') . ' ' .$mysql_info[0]['mysql_version']
                    ],
                    [
                        'type'  => 'text',
                        'title' => '服务器时间',
                        'value' => date("Y-m-d G:i:s")
                    ],
                    // [
                    //     'type'  => 'divider',
                    //     'title' => '开发框架'
                    // ],
                    [
                        'type'  => 'text',
                        'title' => '接口框架',
                        'value' => config('app.app_name') . ' (v' . config('app.app_version') . ')'
                    ],
                    [
                        'type'  => 'text',
                        'title' => '后台框架',
                        'value' => 'cloud-admin (v' . config('app.admin_version') . ')'
                    ],
                    [
                        'type'  => 'text',
                        'title' => '官方网站',
                        'value' => 'https://jiangruyi.com(ijry@qq.com)'
                    ]
                ]
            ],
            [
                'span' => 12,
                'type' => 'card',
                'title' => '项目信息',
                'content' => [
                    [
                        'type'  => 'text',
                        'title' => '项目名称',
                        'value' => $config_core['title']
                    ],
                    [
                        'type'  => 'text',
                        'title' => '项目口号',
                        'value' => $config_core['slogan']
                    ],
                    [
                        'type'  => 'text',
                        'title' => '项目简介',
                        'value' => $config_core['description']
                    ],
                    [
                        'type'  => 'text',
                        'title' => 'ICP备案号',
                        'value' => $config_core['icp']
                    ]
                ]
            ]
        ];

        // 返回数据
        return $this->return(['code' => 200, 'msg' => '成功', 'data' => [
            'dataList' => $dataList
        ]]);
    }
}