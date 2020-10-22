<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\CustomField;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6CustomField;
use GuzzleHttp\Psr7\Request;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class PatchCustomFieldAction extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v2/custom-field/';

    /**
     * @var Shopware6CustomField
     */
    private Shopware6CustomField $customField;

    /**
     * @param Shopware6CustomField $customField
     */
    public function __construct(Shopware6CustomField $customField)
    {
        $this->customField = $customField;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return new Request(
            HttpRequest::METHOD_PATCH,
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
        $serializer = SerializerBuilder::create()->build();

        return $serializer->serialize($this->customField, 'json');
    }

    /**
     * @return string
     */
    private function getUri(): string
    {
        return self::URI.$this->customField->getId();
    }
}
