<?php

declare(strict_types=1);

namespace Omnipay\Shopeepay\Message;

use Omnipay\Common\Exception\InvalidRequestException;

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
            'request_id'       => $this->getTransactionId(),
            'reference_id'     => $this->getTransactionId(),
            'transaction_type' => $this->getTransactionType(),
            'merchant_ext_id'  => $this->getMerchantExtId(),
            'store_ext_id'     => $this->getStoreExtId(),
            'amount'           => $this->getAmount() * 100,
        ];

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
                'X-Airpay-Req-H'    => $this->computeSignature($payload),
            ],
            $payload
        )->getBody();

        $result = json_decode($response->getContents(), true);

        return $this->response = new QueryResponse($this, $result);
    }
}