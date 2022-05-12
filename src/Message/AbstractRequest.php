<?php

declare(strict_types=1);

namespace Omnipay\Shopeepay\Message;

use DateTimeInterface;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

use function Symfony\Component\String\s;

abstract class AbstractRequest extends BaseAbstractRequest
{
    public function getCurrency(): string
    {
        return 'VND';
    }

    public function getMerchantExtId(): string
    {
        return $this->getParameter('merchant_ext_id');
    }

    public function setMerchantExtId(string $merchant_ext_id): self
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

    public function getTransactionType(): string
    {
        return '13'; // payment
    }

    public function getPlatformType(): string
    {
        return 'mweb';
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
        return $this->getParameter('secret_key');
    }

    public function setSecretKey(string $secret_key): self
    {
        return $this->setParameter('secret_key', $secret_key);
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
        return base64_encode(hash_hmac('sha256', $rawHash, $this->getSecretKey(), true));
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