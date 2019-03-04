<?php

namespace addons\epay\library;

use Exception;
use Yansongda\Pay\Log;
use Yansongda\Pay\Pay;

/**
 * 订单服务类
 *
 * @package addons\epay\library
 */
class Service
{

    /**
     * 创建支付对象
     * @param string $type 支付类型
     * @param array $config 配置信息
     * @return bool|\Yansongda\Pay\Gateways\Alipay|\Yansongda\Pay\Gateways\Wechat
     */
    public static function createPay($type, $config = [])
    {
        $type = strtolower($type);
        if (!in_array($type, ['wechat', 'alipay'])) {
            return false;
        }
        $pay = Pay::$type(array_merge(self::getConfig($type), $config));
        return $pay;
    }

    /**
     * 验证回调是否成功
     * @param string $type 支付类型
     * @param array $config 配置信息
     * @return bool|\Yansongda\Pay\Gateways\Alipay|\Yansongda\Pay\Gateways\Wechat
     */
    public static function checkNotify($type, $config = [])
    {
        $type = strtolower($type);
        if (!in_array($type, ['wechat', 'alipay'])) {
            return false;
        }
        try {
            $pay = Pay::$type(array_merge(self::getConfig($type), $config));
            $data = $pay->verify();
            Log::debug($type . ' notify', $data->all());

            if ($type == 'alipay') {
                if (in_array($data->trade_status, ['TRADE_SUCCESS', 'TRADE_FINISHED'])) {
                    return $pay;
                }
            } else {
                return $pay;
            }
        } catch (Exception $e) {
            return false;
        }

        return $pay;
    }

    /**
     * 验证返回是否成功
     * @param string $type 支付类型
     * @param array $config 配置信息
     * @return bool|\Yansongda\Pay\Gateways\Alipay|\Yansongda\Pay\Gateways\Wechat
     */
    public static function checkReturn($type, $config = [])
    {
        $type = strtolower($type);
        if (!in_array($type, ['wechat', 'alipay'])) {
            return false;
        }
        try {
            $pay = Pay::$type(array_merge(self::getConfig($type), $config))->verify();
        } catch (Exception $e) {
            return false;
        }

        return $pay;
    }

    /**
     * 获取配置
     * @param string $type 支付类型
     * @return array|mixed
     */
    public static function getConfig($type = 'wechat')
    {
        $config = get_addon_config('epay');
        $config = isset($config[$type]) ? $config[$type] : $config['wechat'];
        if ($config['log']) {
            $config['log'] = [
                'file'  => LOG_PATH . '/epaylogs/' . $type . '-' . date("Y-m-d") . '.log',
                'level' => 'debug'
            ];
        }

        $config['notify_url'] = empty($config['notify_url']) ? addon_url('epay/api/notify', [], false) . '/type/' . $type : $config['notify_url'];
        $config['notify_url'] = !preg_match("/^(http:\/\/|https:\/\/)/i", $config['notify_url']) ? request()->root(true) . $config['notify_url'] : $config['notify_url'];
        //只有支付宝才配置return_url
        if ($type == 'alipay') {
            $config['return_url'] = empty($config['return_url']) ? addon_url('epay/api/returnx', [], false) . '/type/' . $type : $config['return_url'];
            $config['return_url'] = !preg_match("/^(http:\/\/|https:\/\/)/i", $config['return_url']) ? request()->root(true) . $config['return_url'] : $config['return_url'];
        }
        return $config;
    }

}