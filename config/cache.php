<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 缓存设置
// +----------------------------------------------------------------------

return [
    // 缓存配置为复合类型
    'type' => 'complex',
    'default' => [
        'type' => 'file',
        // 全局缓存有效期（0为永久有效）
        'expire' => 0,
        // 缓存前缀
        'prefix' => '',
        // 缓存目录
        'path' => '../runtime/cache/',
    ],
    'redis' => [
        'type' => 'redis',
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'port' => env('REDIS_PORT', 6379),
        'password' => env('REDIS_PASSWORD', null),
        // 全局缓存有效期（0为永久有效）
        'expire' => 0,
        // 缓存前缀
        'prefix' => 'lea',
    ],
];
