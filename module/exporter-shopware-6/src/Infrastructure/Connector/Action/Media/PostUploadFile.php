<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Media;

use Ergonode\ExporterShopware6\Infrastructure\Connector\AbstractAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\ActionInterface;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class PostUploadFile extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v1/_action/media/%s/upload?%s';

    private string $multimediaId;

    private $content;

    private Multimedia $multimedia;

    /**
     * @param            $content
     */
    public function __construct(string $multimediaId, $content, Multimedia $multimedia)
    {
        $this->multimediaId = $multimediaId;
        $this->content = $content;
        $this->multimedia = $multimedia;
    }

    public function getRequest(): Request
    {
        $this->addHeader('Content-Type', $this->multimedia->getMime());

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
        return $this->content;
    }

    private function getUri(): string
    {
        $query = [
            'extension' => $this->multimedia->getExtension(),
            'fileName' => $this->multimedia->getHash()->getValue(),
        ];

        return rtrim(sprintf(self::URI, $this->multimediaId, http_build_query($query)), '?');
    }
}
