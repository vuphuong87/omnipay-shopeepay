<?php

declare(strict_types=1);

namespace Omnipay\ShopeePay;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\ShopeePay\Message\PurchaseRequest;
use Omnipay\ShopeePay\Message\QueryRequest;
use Omnipay\ShopeePay\Message\CompletePurchaseRequest;

class Gateway extends AbstractGateway
{
    public function getName(): string
    {
        return 'ShopeePay';
    }

    public function getDefaultParameters(): array
    {
        return [
            'merchant_ext_id' => '',
            'store_ext_id' => '',
            'client_id' => '',
            'secret_key' => '',
        ];
    }

    public function getMerchantExId(): string
    {
        return $this->getParameter('merchant_ext_id');
    }

    public function setMerchantExId(string $merchant_ext_id): self
    {
        return $this->setParameter('merchant_ext_id', $merchant_ext_id);
    }

    public function getStoreExtId(): string
    {
        return $this->getParameter('store_ext_id');
    }

    public function setStoreExtId(string $store_ext_id): self
    {
        return $this->setParameter('store_ext_id', $store_ext_id);
    }

    public function getClientId(): string
    {
        return $this->getParameter('client_id');
    }

    public function setClientId(string $client_id): self
    {
        return $this->setParameter('client_id', $client_id);
    }

    public function getSecretKey(): string
    {
        return $this->getParameter('secret_key');
    }

    public function setSecretKey(string $secret_key): self
    {
        return $this->setParameter('secret_key', $secret_key);
    }


    public function purchase(array $parameters = []): AbstractRequest
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    public function query(array $parameters = []): AbstractRequest
    {
        return $this->createRequest(QueryRequest::class, $parameters);
    }

    public function completePurchase(array $parameters = []): AbstractRequest
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }

}