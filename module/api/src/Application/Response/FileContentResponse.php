<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Application\Response;

use Ergonode\Multimedia\Domain\Entity\AbstractResource;
use Symfony\Component\HttpFoundation\Response;

/**
 */
class FileContentResponse extends AbstractResponse
{

    /**
     * @param            $content
     * @param AbstractResource $resource
     */
    public function __construct($content, $resource)
    {
        $headers = [
            'Cache-Control' => 'private',
            'Content-type' => $resource->getMime(),
            'Content-Disposition' => 'attachment; filename="'.basename($resource->getFileName()).'";',
            'Content-length' => $resource->getSize(),
        ];
        parent::__construct($content, Response::HTTP_OK, $headers);
    }
}
