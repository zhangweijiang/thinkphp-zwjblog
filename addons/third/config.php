<?php

return array(
    array(
        'name'    => 'qq',
        'title'   => 'QQ',
        'type'    => 'array',
        'content' =>
            array(
                'app_id'     => '',
                'app_secret' => '',
                'scope'      => 'get_user_info',
            ),
        'value'   =>
            array(
                'app_id'     => '100000000',
                'app_secret' => '123456',
                'scope'      => 'get_user_info',
            ),
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),
    array(
        'name'    => 'wechat',
        'title'   => '微信',
        'type'    => 'array',
        'content' =>
            array(
                'app_id'     => '',
                'app_secret' => '',
                'callback'   => '',
                'scope'      => 'snsapi_base',
            ),
        'value'   =>
            array(
                'app_id'     => '100000000',
                'app_secret' => '123456',
                'scope'      => 'snsapi_base',
            ),
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),
    array(
        'name'    => 'weibo',
        'title'   => '微博',
        'type'    => 'array',
        'content' =>
            array(
                'app_id'     => '',
                'app_secret' => '',
                'scope'      => 'get_user_info',
            ),
        'value'   =>
            array(
                'app_id'     => '100000000',
                'app_secret' => '123456',
                'scope'      => 'get_user_info',
            ),
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),
    array(
        'name'    => 'rewrite',
        'title'   => '伪静态',
        'type'    => 'array',
        'content' =>
            array(),
        'value'   =>
            array(
                'index/index'    => '/third$',
                'index/connect'  => '/third/connect/[:platform]',
                'index/callback' => '/third/callback/[:platform]',
            ),
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '',
        'ok'      => '',
        'extend'  => '',
    ),
);
