<?php

namespace Yansongda\Pay\Gateways\Wechat;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yansongda\Pay\Events;

class WebGateway extends Gateway
{
    /**
     * Pay an order.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param string $endpoint
     * @param array $payload
     *
     * @throws \Yansongda\Pay\Exceptions\InvalidConfigException
     *
     * @return Response
     */
    public function pay($endpoint, array $payload): Response
    {
        $payload['spbill_create_ip'] = Request::createFromGlobals()->server->get('SERVER_ADDR');
        $payload['trade_type'] = $this->getTradeType();

        Events::dispatch(Events::PAY_STARTED, new Events\PayStarted('Wechat', 'Web', $endpoint, $payload));

        $preOrder = $this->preOrder($payload);

        $params = [
            'body'         => $payload['body'],
            'code_url'     => $preOrder['code_url'],
            'out_trade_no' => $payload['out_trade_no'],
            'return_url'   => $payload['return_url'],
            'total_fee'    => $payload['total_fee'],
        ];
        $params['sign'] = md5(implode('', $params) . $payload['appid']);
        $endpoint = addon_url("epay/api/wechat");

        return $this->buildPayHtml($endpoint, $params);
    }

    /**
     * Build Html response.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param string $endpoint
     * @param array $payload
     *
     * @return Response
     */
    protected function buildPayHtml($endpoint, $payload): Response
    {
        $sHtml = "<form id='wechatsubmit' name='wechatsubmit' action='" . $endpoint . "' method='POST'>";
        foreach ($payload as $key => $val) {
            $val = str_replace("'", '&apos;', $val);
            $sHtml .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        }
        $sHtml .= "<input type='submit' value='ok' style='display:none;'></form>";
        $sHtml .= "<script>document.forms['wechatsubmit'].submit();</script>";

        return Response::create($sHtml);
    }

    /**
     * Get trade type config.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @return string
     */
    protected function getTradeType(): string
    {
        return 'NATIVE';
    }
}
