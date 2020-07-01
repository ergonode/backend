<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Application\Response;

use Ergonode\Multimedia\Infrastructure\Storage\MultimediaStorageInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 */
class FileContentResponse extends AbstractResponse
{

    /**
     * @param string                     $filename
     * @param MultimediaStorageInterface $storage
     */
    public function __construct(string $filename, MultimediaStorageInterface $storage)
    {
        $info = $storage->info($filename);

        $headers = [
            'Cache-Control' => 'private',
            'Content-type' => $info['mime'],
            'Content-Disposition' => 'attachment; filename="'.basename($filename).'";',
            'Content-length' => $info['size'],
        ];
        parent::__construct($storage->read($filename), Response::HTTP_OK, $headers);
    }
}
