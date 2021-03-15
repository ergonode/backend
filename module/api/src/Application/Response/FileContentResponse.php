<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Application\Response;

use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\Response;

class FileContentResponse extends AbstractResponse
{

    /**
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function __construct(string $filename, FilesystemInterface $storage)
    {
        $headers = [
            'Cache-Control' => 'private',
            'Content-type' => $storage->getMimetype($filename),
            'Content-Disposition' => 'attachment; filename="'.basename($filename).'";',
            'Content-length' => $storage->getSize($filename),
        ];
        parent::__construct($storage->read($filename), Response::HTTP_OK, $headers);
    }
}
