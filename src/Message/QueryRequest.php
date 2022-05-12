<?php

declare(strict_types=1);

namespace Omnipay\Shopeepay\Message;

use Omnipay\Common\Exception\InvalidRequestException;

use Omnipay\Shopeepay\Message\QueryResponse;

use function GuzzleHttp\json_encode;
use function GuzzleHttp\json_decode;

class QueryRequest extends AbstractRequest
{
    public const TRANSACTION_URI = '/v3/merchant-host/transaction/check';

    /**
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate(
            'merchant_ext_id',
            'store_ext_id',
            'transactionId',
            'amount',
        );

        $order = [
            'merchant_ext_id'  => $this->getMerchantExId(),
            'store_ext_id'     => $this->getStoreExtId(),
            'request_id'       => $this->getTransactionId(),
            'reference_id'     => $this->getTransactionId(),
            'transaction_type' => $this->getTransactionType(),
            'amount'           => $this->getAmount(),
        ];

        $order['signature'] = $this->computeSignature(
            implode('', array_values($order))
        );

        return [
            'order' => $order,
        ];
    }

    public function sendData($data): QueryResponse
    {
        $order = $this->buildOrder($data['order']);

        $payload = json_encode($order);
        $response = $this->httpClient->request(
            'POST',
            $this->getEndpoint().self::TRANSACTION_URI,
            [
                'Content-Type'      => 'application/json',
                'X-Airpay-ClientId' => $this->getClientId(),
                'X-Airpay-Req-H'    => $this->computeSignature(implode('', array_values($order))),
            ],
            $payload
        )->getBody();

        $result = json_decode($response->getContents(), true);

        return $this->response = new QueryResponse($this, $result);
    }
}