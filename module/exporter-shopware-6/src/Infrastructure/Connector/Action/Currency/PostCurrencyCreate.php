<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Currency;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class PostCurrencyCreate extends AbstractAction
{
    private const URI = '/api/v2/currency';

    private string $iso;

    public function __construct(string $iso)
    {
        $this->iso = $iso;
    }

    public function getRequest(): Request
    {
        return new Request(
            HttpRequest::METHOD_POST,
            $this->getUri(),
            $this->buildHeaders(),
            $this->buildBody()
        );
    }

    /**
     * @return null
     */
    public function parseContent(?string $content)
    {
        return null;
    }

    private function buildBody(): string
    {
        $body = [
            'factor' => 1,
            'symbol' => $this->iso,
            'isoCode' => $this->iso,
            'shortName' => $this->iso,
            'name' => $this->iso,
            'decimalPrecision' => 2,
        ];

        return json_encode($body);
    }

    private function getUri(): string
    {
        return self::URI;
    }
}
