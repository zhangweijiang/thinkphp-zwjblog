<?php

namespace addons\epay\controller;

use addons\epay\library\Service;
use fast\Random;
use think\addons\Controller;
use Yansongda\Pay\Log;
use Yansongda\Pay\Pay;
use Exception;

/**
 * 微信支付宝插件首页
 *
 * 此控制器仅用于开发展示说明和体验，建议自行添加一个新的控制器进行处理返回和回调事件，同时删除此控制器文件
 *
 * Class Index
 * @package addons\epay\controller
 */
class Index extends Controller
{

    protected $layout = 'default';

    protected $config = [];

    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        $this->view->assign("title", "微信支付宝企业收款插件");
        return $this->view->fetch();
    }

    /**
     * 体验(仅供开发测试体验)
     */
    public function experience()
    {
        $amount = $this->request->request('amount');
        $type = $this->request->request('type');
        $method = $this->request->request('method');

        if (!$amount || $amount < 0) {
            $this->error("支付金额必须大于0");
        }

        if (!$type || !in_array($type, ['alipay', 'wechat'])) {
            $this->error("参数不能为空");
        }

        //订单号
        $out_trade_no = date("YmdHis") . Random::alnum(6);

        //订单标题
        $title = 'FastAdmin企业支付插件测试订单';

        if ($type == 'alipay') {
            //创建支付对象
            $pay = Pay::alipay(Service::getConfig('alipay'));
            //支付宝支付,请根据你的需求,仅选择你所需要的即可
            $params = [
                'out_trade_no' => $out_trade_no,//你的订单号
                'total_amount' => $amount,//单位元
                'subject'      => $title,
                'notify_url'   => $this->request->root(true) . '/addons/epay/index/alipaynotify',
                'return_url'   => $this->request->root(true) . '/addons/epay/index/alipayreturn'
            ];

            switch ($method) {
                case 'web':
                    //电脑支付,跳转
                    return $pay->web($params)->send();
                case 'wap':
                    //手机网页支付,跳转
                    return $pay->wap($params)->send();
                case 'app':
                    //APP支付,直接返回字符串
                    return $pay->app($params)->send();
                case 'scan':
                    //扫码支付,直接返回字符串
                    return $pay->scan($params);
                case 'pos':
                    //刷卡支付,直接返回字符串
                    //刷卡支付必须要有auth_code
                    $params['auth_code'] = '289756915257123456';
                    return $pay->pos($params);
                default:
                    //其它支付类型请参考：https://docs.pay.yansongda.cn/alipay
            }
        } else {
            //创建支付对象
            $pay = Pay::wechat(Service::getConfig('wechat'));
            //微信支付,请根据你的需求,仅选择你所需要的即可
            $params = [
                'out_trade_no' => $out_trade_no,//你的订单号
                'body'         => $title,
                'total_fee'    => $amount * 100, //单位分
                'notify_url'   => $this->request->root(true) . '/addons/epay/index/wechatnofity',
                'return_url'   => $this->request->root(true) . '/addons/epay/index/wechatreturn/out_trade_no/' . $out_trade_no,
            ];

            switch ($method) {
                case 'web':
                    //电脑支付,跳转到自定义展示页面(FastAdmin独有)
                    return $pay->web($params)->send();
                case 'mp':
                    //公众号支付
                    //公众号支付必须有openid
                    $params['openid'] = 'onkVf1FjWS5SBxxxxxxxx';
                    return $pay->mp($params);
                case 'wap':
                    //手机网页支付,跳转
                    return $pay->wap($params)->send();
                case 'app':
                    //APP支付,直接返回字符串
                    return $pay->app($params)->send();
                case 'scan':
                    //扫码支付,直接返回字符串
                    return $pay->scan($params);
                case 'pos':
                    //刷卡支付,直接返回字符串
                    //刷卡支付必须要有auth_code
                    $params['auth_code'] = '289756915257123456';
                    return $pay->pos($params);
                case 'miniapp':
                    //小程序支付,直接返回字符串
                    //小程序支付必须要有openid
                    $params['openid'] = 'onkVf1FjWS5SBxxxxxxxx';
                    return $pay->miniapp($params);
                default:
                    //其它支付类型请参考：https://docs.pay.yansongda.cn/wechat
            }
        }
        $this->error("未找到支付类型[{$type}][{$method}]");
    }

    /**
     * 支付宝异步通知
     */
    public function alipaynotify()
    {
        $alipay = Pay::alipay(Service::getConfig('wechat'));
        try {
            $data = $alipay->verify();
            Log::debug('wechat notify', $data->all());
            if (!in_array($data->trade_status, ['TRADE_SUCCESS', 'TRADE_FINISHED'])) {
                echo "验签失败";
                return;
            }
        } catch (Exception $e) {
            echo "验签失败";
            return;
        }
        //支付宝可以获取到$pay->out_trade_no,$pay->total_amount等信息
        echo $alipay->success();
        return;
    }

    /**
     * 支付宝返回通知
     */
    public function alipayreturn()
    {
        $alipay = Pay::alipay(Service::getConfig('alipay'));
        try {
            $alipay->verify();
        } catch (Exception $e) {
            $this->error("支付失败", "");
            return;
        }

        $this->success("支付成功", "");
        return;
    }

    /**
     * 微信异步通知
     */
    public function wechatnotify()
    {
        $wechat = Pay::wechat(Service::getConfig('wechat'));
        try {
            $data = $wechat->verify();
            Log::debug('wechat notify', $data->all());
        } catch (Exception $e) {
            echo "验签失败";
            return;
        }
        //微信可以获取到$pay->out_trade_no,$pay->total_fee等信息
        echo $wechat->success();
        return;
    }

    /**
     * 微信返回通知
     */
    public function wechatreturn()
    {
        $out_trade_no = $this->request->param('out_trade_no');
        if (!$out_trade_no) {
            $this->error("订单号不正确");
        }
        $wechat = Pay::wechat(Service::getConfig('wechat'));
        $order = [
            'out_trade_no' => $out_trade_no
        ];
        $result = $wechat->find($order);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS' && $result->trade_state == 'SUCCESS') {
            $this->success("支付成功", "");
        } else {
            $this->error("支付失败", "");
        }

        $this->success("请返回网站查看支付结果");
    }

}
