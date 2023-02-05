<?php

namespace uiadmin\core;

use think\Route;
use think\Validate;

class Service extends \think\Service
{
    public function register()
    {
        // $this->app->middleware->add(Router::class);

        if (env('uiadmin.install')) {
            $service_list = get_ext_services();
            foreach ($service_list as $key => $value) {
                $this->app->register($value);
            }
        }
    }

    public function boot()
    {
        $this->registerRoutes(function (Route $route) {
            // 分组
            $route->redirect('/' . config("uiadmin.xyadmin.entry") . '$', request()->url(true) . '/');
            $route->get('/' . config("uiadmin.xyadmin.entry") . '/$', function() {
                $secondsToCache = 3600;
                $ts = gmdate("D, d M Y H:i:s", time() + $secondsToCache) . " GMT";
                $ch= curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://uiadmin.jiangruyi.com/xyadmin/?version=' . get_config('xyadmin.version'));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 表示不检查证书
                $xyadminIndex = curl_exec($ch);
                curl_close($ch);
                // 替换处理
                $xyadminIndex = preg_replace('#https\://cdn\.jiangruyi\.com\/npm/element-ui@2.15.5\/#',
                    request()->root(true) . '/npm/element-ui@2.15.5/', $xyadminIndex);
                return $xyadminIndex;
            });

            // 根接口
            $route->get('/$', "uiadmin\\core\\controller\\Core@index");
            $route->get('/admin/api$', "uiadmin\\core\\admin\\Index@api");
            $route->put(config("uiadmin.site.apiPrefix") . '/v1/admin/core/index/editField', "uiadmin\\core\\admin\\Index@editField");
            $route->post(config("uiadmin.site.apiPrefix") . '/v1/admin/core/user/login', "uiadmin\\core\\admin\\User@login");
            $route->get(config("uiadmin.site.apiPrefix") . '/v1/admin/core/index/index$', "uiadmin\\core\\admin\\Index@index");
            $route->get(config("uiadmin.site.apiPrefix") . '/v1/admin/core/menu/trees$', "uiadmin\\core\\admin\\Menu@trees");
            $route->get(config("uiadmin.site.apiPrefix") . '/v1/core/user/info$', "uiadmin\\core\\controller\\User@info");
            $route->post(config("uiadmin.site.apiPrefix") . '/v1/core/upload/upload$', "uiadmin\\core\\controller\\Upload@upload");
            $route->delete(config("uiadmin.site.apiPrefix") . '/v1/core/user/logout', "uiadmin\\core\\controller\\User@logout");
        });

        // 注册命令
        $this->commands([
            'uiadmin:publish' => \uiadmin\core\command\Publish::class,
            'uiadmin:install' => \uiadmin\core\command\Install::class
        ]);
    }
}