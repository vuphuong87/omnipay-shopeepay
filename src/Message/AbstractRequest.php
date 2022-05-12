<?php

declare(strict_types=1);

namespace Omnipay\ShopeePay\Message;

use DateTimeInterface;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

use function Symfony\Component\String\s;

abstract class AbstractRequest extends BaseAbstractRequest
{
    public function getCurrency(): string
    {
        return 'VND';
    }

    public function getMerchantExId(): string
    {
        return $this->getParameter('merchant_ext_id');
    }

    public function setMerchantExId(string $merchantExtId): self
    {
        return $this->setParameter('merchant_ext_id', $merchantExtId);
    }

    public function getStoreExtId(): string
    {
        return $this->getParameter('store_ext_id');
    }

    public function setStoreExtId(string $storeExtId): self
    {
        return $this->setParameter('store_ext_id', $storeExtId);
    }

    public function getClientId(): string
    {
        return $this->getParameter('client_id');
    }

    public function setClientId(string $clientId): self
    {
        return $this->setParameter('client_id', $clientId);
    }

    public function getTransactionType(): bool
    {
        return $this->getParameter('transactionType');
    }

    public function setTransactionType(bool $transactionType): self
    {
        return $this->setParameter('transactionType', $transactionType);
    }

    public function getAmount(): ?string
    {
        return $this->getParameter('amount');
    }

    public function setAmount($value): self
    {
        return $this->setParameter('amount', $value * 100);
    }

    public function getPlatformType(): string
    {
        return $this->getParameter('platformType') ?? 'mweb';
    }

    public function setPlatformType(string $platformType): self
    {
        return $this->setParameter('platformType', $platformType);
    }

    public function setValidityTime(?DateTimeInterface $validityTime): self
    {
        return $this->setParameter('validityTime', $validityTime);
    }

    public function getValidityTime(): ?DateTimeInterface
    {
        return $this->getParameter('validityTime');
    }

    public function getSignature(): string
    {
        return $this->getParameter('signature');
    }

    public function setSignature(string $signature): self
    {
        return $this->setParameter('signature', $signature);
    }

    public function getSecretKey(): string
    {
        return $this->getParameter('secretKey');
    }

    public function setSecretKey(string $secretKey): self
    {
        return $this->setParameter('secretKey', $secretKey);
    }

    public function getEndpoint(): string
    {
        $endpoint = 'https://api.wallet.airpay.vn';
        if ($this->getTestMode()) {
            $endpoint = 'https://api.uat.wallet.airpay.vn';
        }

        return $endpoint;
    }

    protected function computeSignature(string $rawHash): string
    {
        return hash_hmac('sha256', $rawHash, $this->getSecretKey(), true);
    }

    protected function buildOrder(array $data): array
    {
        $order = [];
        array_walk(
            $data,
            static function ($value, $key) use (&$order) {
                $key = s($key)->snake()->toString();
                $order[$key] = $value;
            }
        );

        return $order;
    }
}