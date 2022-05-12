<?php

declare(strict_types=1);

namespace Omnipay\Shopeepay\Message;

use Omnipay\Shopeepay\Message\CompletePurchaseResponse;

class CompletePurchaseRequest extends AbstractRequest
{
    public function getData(): array
    {
        $request = $this->httpRequest->request->all();

        $order = [
            'amount'               => $request['amount'] ? (int)$request['amount'] / 100 : null,
            'merchant_ext_id'      => $request['merchant_ext_id'] ?? null,
            'payment_reference_id' => $request['payment_reference_id'] ?? null,
            'payment_method'       => $request['payment_method'] ?? null,
            'payment_status'       => $request['payment_status'] ?? null,
            'reference_id'         => $request['reference_id'] ?? null,
            'store_ext_id'         => $request['store_ext_id'] ?? null,
            'terminal_id'          => $request['terminal_id'] ?? null,
            'transaction_sn'       => $request['transaction_sn'] ?? null,
            'transaction_status'   => $request['transaction_status'] ?? null,
            'user_id_hash'         => $request['user_id_hash'] ?? null,
            'state'                => $request['transaction_status'],
        ];

        $order['computed_signature'] = $this->computeSignature(
            implode('', array_values($order))
        );

        return $order;
    }

    public function sendData($data): CompletePurchaseResponse
    {
        return new CompletePurchaseResponse($this, $data);
    }
}