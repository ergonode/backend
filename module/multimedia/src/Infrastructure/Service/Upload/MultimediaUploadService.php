<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Service\Upload;

use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 */
class MultimediaUploadService
{
    private $targetDirectory;

    /**
     * @param string $targetDirectory
     */
    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @param MultimediaId $id
     * @param UploadedFile $file
     *
     * @return File
     */
    public function upload(MultimediaId $id, UploadedFile $file): File
    {
        $fileName = sprintf('%s.%s', $id->getValue(), $file->guessClientExtension());

        return $file->move($this->targetDirectory, $fileName);
    }
}
