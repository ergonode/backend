<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Language;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Locate;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 */
class GetLocate extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/locale/%s';

    /**
     * @var string
     */
    private string $locateId;

    /**
     * @param string $locateId
     */
    public function __construct(string $locateId)
    {
        $this->locateId = $locateId;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return new Request(
            HttpRequest::METHOD_GET,
            $this->getUri(),
            $this->buildHeaders()
        );
    }

    /**
     * @param string|null $content
     *
     * @return Shopware6Locate
     *
     * @throws \JsonException
     */
    public function parseContent(?string $content): Shopware6Locate
    {
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        return new Shopware6Locate(
            $data['data']['id'],
            $data['data']['attributes']['code'],
            $data['data']['attributes']['name'],
        );
    }

    /**
     * @return string
     */
    private function getUri(): string
    {
        return sprintf(self::URI, $this->locateId);
    }
}
