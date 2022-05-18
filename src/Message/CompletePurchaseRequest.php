<?php

declare(strict_types=1);

namespace Omnipay\Shopeepay\Message;


use function GuzzleHttp\json_encode;

class CompletePurchaseRequest extends AbstractRequest
{
    public function getData(): array
    {
        $request = $this->httpRequest->request->all();

        $order = [
            'amount'               => $request['amount'] ? (int)$request['amount'] : null,
            'payment_method'       => $request['payment_method'] ? (int)$request['payment_method'] : null,
            'merchant_ext_id'      => $request['merchant_ext_id'] ?? null,
            'store_ext_id'         => $request['store_ext_id'] ?? null,
            'transaction_sn'       => $request['transaction_sn'] ?? null,
            'transaction_type'     => $request['transaction_type'] ? (int)$request['transaction_type'] : null,
            'transaction_status'   => $request['transaction_status'] ? (int)$request['transaction_status'] : null,
            'reference_id'         => $request['reference_id'] ?? null,
            'user_id_hash'         => $request['user_id_hash'] ?? null,
            'payment_reference_id' => $request['payment_reference_id'] ?? null,
            'payment_status'       => $request['payment_status'] ?? null,
            'terminal_id'          => $request['terminal_id'] ?? null,
        ];

        $order['computed_signature'] = $this->computeSignature(json_encode(array_filter($order)));
        $order['signature'] = $this->httpRequest->headers->get('x-airpay-req-h');
        $order['state'] = $order['transaction_status'];
        $order['payment_method'] = 'SHOPEEPAY';

        return $order;
    }

    public function sendData($data): CompletePurchaseResponse
    {
        return new CompletePurchaseResponse($this, $data);
    }
}