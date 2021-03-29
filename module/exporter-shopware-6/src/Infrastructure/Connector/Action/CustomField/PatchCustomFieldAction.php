<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\CustomField;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6CustomField;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class PatchCustomFieldAction extends AbstractAction
{
    private const URI = '/api/v2/custom-field/';

    private AbstractShopware6CustomField $customField;

    public function __construct(AbstractShopware6CustomField $customField)
    {
        $this->customField = $customField;
    }

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
     * @return null
     */
    public function parseContent(?string $content)
    {
        return null;
    }

    private function buildBody(): string
    {
        return json_encode($this->customField->jsonSerialize(), JSON_THROW_ON_ERROR);
    }

    private function getUri(): string
    {
        return self::URI.$this->customField->getId();
    }
}
