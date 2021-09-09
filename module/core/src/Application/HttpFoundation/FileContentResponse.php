<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\HttpFoundation;

use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\Response;

class FileContentResponse extends Response
{
    /**
     * @throws \League\Flysystem\FileNotFoundException|\LogicException
     */
    public function __construct(string $filename, FilesystemInterface $storage)
    {
        $headers = [
            'Cache-Control' => 'private',
            'Content-type' => $storage->getMimetype($filename),
            'Content-Disposition' => 'attachment; filename="'.basename($filename).'";',
            'Content-length' => $storage->getSize($filename),
        ];
        $content = $storage->read($filename);

        if (false === $content) {
            throw new \LogicException('There\'s no such file');
        }

        parent::__construct($content, Response::HTTP_OK, $headers);
    }
}
