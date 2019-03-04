<?php

return array(
    array(
        'name'    => 'wechat',
        'title'   => '微信',
        'type'    => 'array',
        'content' =>
            array(),
        'value'   => [
            'appid'       => 'wxb3fxxxxxxxxxxx', // APP APPID
            'app_id'      => 'wxb3fxxxxxxxxxxx', // 公众号 APPID
            'miniapp_id'  => 'wxb3fxxxxxxxxxxx', // 小程序 APPID
            'mch_id'      => '1482657422', //支付商户ID
            'key'         => 'BcwBqT8sFJUP9KQqzbkXghxSKVxPdc35',
            'notify_url'  => '/addons/epay/api/notify/type/wechat', //请勿修改此配置
            'cert_client' => '/epay/certs/apiclient_cert.pem', // 可选, 退款，红包等情况时需要用到
            'cert_key'    => '/epay/certs/apiclient_key.pem',// 可选, 退款，红包等情况时需要用到
            'log'         => 1,
//            'mode'        => 'dev', // optional,设置此参数，将进入沙箱模式
        ],
        'rule'    => '',
        'msg'     => '',
        'tip'     => '微信参数配置',
        'ok'      => '',
        'extend'  => '',
    ),
    array(
        'name'    => 'alipay',
        'title'   => '支付宝',
        'type'    => 'array',
        'content' =>
            array(),
        'value'   => [
            'app_id'         => '2016082000295641',
            'notify_url'     => '/addons/epay/api/notify/type/alipay', //请勿修改此配置
            'return_url'     => '/addons/epay/api/returnx/type/alipay', //请勿修改此配置
            'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuWJKrQ6SWvS6niI+4vEVZiYfjkCfLQfoFI2nCp9ZLDS42QtiL4Ccyx8scgc3nhVwmVRte8f57TFvGhvJD0upT4O5O/lRxmTjechXAorirVdAODpOu0mFfQV9y/T9o9hHnU+VmO5spoVb3umqpq6D/Pt8p25Yk852/w01VTIczrXC4QlrbOEe3sr1E9auoC7rgYjjCO6lZUIDjX/oBmNXZxhRDrYx4Yf5X7y8FRBFvygIE2FgxV4Yw+SL3QAa2m5MLcbusJpxOml9YVQfP8iSurx41PvvXUMo49JG3BDVernaCYXQCoUJv9fJwbnfZd7J5YByC+5KM4sblJTq7bXZWQIDAQAB',
            // 加密方式： **RSA2**
            'private_key'    => 'MIIEpAIBAAKCAQEAs6+F2leOgOrvj9jTeDhb5q46GewOjqLBlGSs/bVL4Z3fMr3p+Q1Tux/6uogeVi/eHd84xvQdfpZ87A1SfoWnEGH5z15yorccxSOwWUI+q8gz51IWqjgZxhWKe31BxNZ+prnQpyeMBtE25fXp5nQZ/pftgePyUUvUZRcAUisswntobDQKbwx28VCXw5XB2A+lvYEvxmMv/QexYjwKK4M54j435TuC3UctZbnuynSPpOmCu45ZhEYXd4YMsGMdZE5/077ZU1aU7wx/gk07PiHImEOCDkzqsFo0Buc/knGcdOiUDvm2hn2y1XvwjyFOThsqCsQYi4JmwZdRa8kvOf57nwIDAQABAoIBAQCw5QCqln4VTrTvcW+msB1ReX57nJgsNfDLbV2dG8mLYQemBa9833DqDK6iynTLNq69y88ylose33o2TVtEccGp8Dqluv6yUAED14G6LexS43KtrXPgugAtsXE253ZDGUNwUggnN1i0MW2RcMqHdQ9ORDWvJUCeZj/AEafgPN8AyiLrZeL07jJz/uaRfAuNqkImCVIarKUX3HBCjl9TpuoMjcMhz/MsOmQ0agtCatO1eoH1sqv5Odvxb1i59c8Hvq/mGEXyRuoiDo05SE6IyXYXr84/Nf2xvVNHNQA6kTckj8shSi+HGM4mO1Y4Pbb7XcnxNkT0Inn6oJMSiy56P+CpAoGBAO1O+5FE1ZuVGuLb48cY+0lHCD+nhSBd66B5FrxgPYCkFOQWR7pWyfNDBlmO3SSooQ8TQXA25blrkDxzOAEGX57EPiipXr/hy5e+WNoukpy09rsO1TMsvC+v0FXLvZ+TIAkqfnYBgaT56ku7yZ8aFGMwdCPL7WJYAwUIcZX8wZ3dAoGBAMHWplAqhe4bfkGOEEpfs6VvEQxCqYMYVyR65K0rI1LiDZn6Ij8fdVtwMjGKFSZZTspmsqnbbuCE/VTyDzF4NpAxdm3cBtZACv1Lpu2Om+aTzhK2PI6WTDVTKAJBYegXaahBCqVbSxieR62IWtmOMjggTtAKWZ1P5LQcRwdkaB2rAoGAWnAPT318Kp7YcDx8whOzMGnxqtCc24jvk2iSUZgb2Dqv+3zCOTF6JUsV0Guxu5bISoZ8GdfSFKf5gBAo97sGFeuUBMsHYPkcLehM1FmLZk1Q+ljcx3P1A/ds3kWXLolTXCrlpvNMBSN5NwOKAyhdPK/qkvnUrfX8sJ5XK2H4J8ECgYAGIZ0HIiE0Y+g9eJnpUFelXvsCEUW9YNK4065SD/BBGedmPHRC3OLgbo8X5A9BNEf6vP7fwpIiRfKhcjqqzOuk6fueA/yvYD04v+Da2MzzoS8+hkcqF3T3pta4I4tORRdRfCUzD80zTSZlRc/h286Y2eTETd+By1onnFFe2X01mwKBgQDaxo4PBcLL2OyVT5DoXiIdTCJ8KNZL9+kV1aiBuOWxnRgkDjPngslzNa1bK+klGgJNYDbQqohKNn1HeFX3mYNfCUpuSnD2Yag53Dd/1DLO+NxzwvTu4D6DCUnMMMBVaF42ig31Bs0jI3JQZVqeeFzSET8fkoFopJf3G6UXlrIEAQ==',
            'log'            => 1,
            'mode'           => 'dev', // optional,设置此参数，将进入沙箱模式
        ],
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '支付宝参数配置',
        'ok'      => '',
        'extend'  => '',
    ),
    array(

        'name'    => '__tips__',
        'title'   => '温馨提示',
        'type'    => 'array',
        'content' =>
            array(),
        'value'   => '请注意微信支付证书路径位于/addons/epay/certs目录下，请替换成你自己的证书<br>微信:mch_id为微信商户ID,appid为APP的appid,app_id为公众号的appid,miniapp_id为小程序ID,key为微信商户支付的密钥',
        'rule'    => '',
        'msg'     => '',
        'tip'     => '微信参数配置',
        'ok'      => '',
        'extend'  => '',
    )
);
