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

/**
 */
class PostUploadFile extends AbstractAction implements ActionInterface
{
    private const URI = '/api/v1/_action/media/%s/upload?%s';

    /**
     * @var string
     */
    private string $multimediaId;

    private $content;

    /**
     * @var Multimedia
     */
    private Multimedia $multimedia;

    /**
     * @param string     $multimediaId
     * @param            $content
     * @param Multimedia $multimedia
     */
    public function __construct(string $multimediaId, $content, Multimedia $multimedia)
    {
        $this->multimediaId = $multimediaId;
        $this->content = $content;
        $this->multimedia = $multimedia;
    }

    /**
     * @return Request
     */
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
        return $this->content;
    }

    /**
     * @return string
     */
    private function getUri(): string
    {
        $query = [
            'extension' => $this->multimedia->getExtension(),
            'fileName' => $this->multimedia->getHash()->getValue(),
        ];

        return rtrim(sprintf(self::URI, $this->multimediaId, http_build_query($query)), '?');
    }
}
