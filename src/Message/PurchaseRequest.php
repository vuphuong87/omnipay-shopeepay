<?php

declare(strict_types=1);

namespace Omnipay\ShopeePay\Message;

use DateTime;
use DateTimeZone;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\ShopeePay\Message\PurchaseResponse;

use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;

class PurchaseRequest extends AbstractRequest
{
    public const CHECKOUT_URI = '/v3/merchant-host/order/create';

    public const TIMEZONE = 'Asia/Ho_Chi_Minh';

    /**
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate(
            'merchant_ext_id',
            'store_ext_id',
            'transactionId', // order_id
            'returnUrl',
            'amount',
            'validityTime'
        );

        $validityTime = $this->getValidityTime() ?: new DateTime('+5 minutes');
        $validityTime->setTimezone(new DateTimeZone(self::TIMEZONE));
        $validityTime = $validityTime->format('dmY His');

        $order = [
            'merchant_ext_id'      => $this->getMerchantExId(),
            'store_ext_id'         => $this->getStoreExtId(),
            'request_id'           => $this->getTransactionId(),
            'payment_reference_id' => $this->getTransactionId(),
            'amount'               => $this->getAmount(),
            'currency'             => $this->getCurrency(),
            'expiry_time'          => $validityTime,
            'return_url'           => $this->getReturnUrl(),
            'platform_type'        => $this->getPlatformType(),
        ];

        $order['signature'] = $this->computeSignature(
            implode('', array_values($order))
        );

        return [
            'order' => $order,
        ];
    }

    public function sendData($data): PurchaseResponse
    {
        $order = $this->buildOrder($data['order']);

        $payload = json_encode($order);
        $response = $this->httpClient->request(
            'POST',
            $this->getEndpoint().self::CHECKOUT_URI,
            [
                'Content-Type'      => 'application/json',
                'X-Airpay-ClientId' => $this->getClientId(),
                'X-Airpay-Req-H'    => $this->computeSignature(implode('', array_values($order))),
            ],
            $payload
        )->getBody();

        $result = json_decode($response->getContents(), true);
        $result['redirectUrl'] = $result['redirect_url_http'];

        return $this->response = new PurchaseResponse($this, $result);
    }
}