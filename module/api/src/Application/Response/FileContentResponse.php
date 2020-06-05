<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Application\Response;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Symfony\Component\HttpFoundation\Response;

/**
 */
class FileContentResponse extends AbstractResponse
{

    /**
     * @param            $content
     * @param Multimedia $multimedia
     */
    public function __construct($content, Multimedia $multimedia)
    {
        $headers = [
            'Cache-Control' => 'private',
            'Content-type' => $multimedia->getMime(),
            'Content-Disposition' => 'attachment; filename="'.basename($multimedia->getFileName()).'";',
            'Content-length' => $multimedia->getSize(),
        ];
        parent::__construct($content, Response::HTTP_OK, $headers);
    }
}
