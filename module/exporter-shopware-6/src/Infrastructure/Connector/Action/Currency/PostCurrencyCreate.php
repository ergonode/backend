<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Currency;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\HeaderProviderInterface;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class PostCurrencyCreate extends AbstractAction implements ActionInterface, HeaderProviderInterface
{
    private const URI = '/api/v1/currency';

    /**
     * @var string
     */
    private string $iso;

    /**
     * @param string $iso
     */
    public function __construct(string $iso)
    {
        $this->iso = $iso;
    }

    /**
     * @return Request
     */
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
     * @param string|null $content
     *
     * @return null
     */
    public function parseContent(?string $content)
    {
        return null;
    }

    /**
     * @return string
     */
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

    /**
     * @return string
     */
    private function getUri(): string
    {
        return self::URI;
    }
}
