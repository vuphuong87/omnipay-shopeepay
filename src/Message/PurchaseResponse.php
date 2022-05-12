<?php

declare(strict_types=1);

namespace Omnipay\ShopeePay\Message;

use Omnipay\Common\Message\AbstractResponse;

class PurchaseResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return true;
    }

    public function isPending(): bool
    {
        return true;
    }

    public function isRedirect(): bool
    {
        return true;
    }

    public function isTransparentRedirect(): bool
    {
        return true;
    }

    public function getRedirectUrl(): string
    {
        return $this->data['redirectUrl'];
    }

    public function getRedirectData(): array
    {
        return $this->data;
    }
}