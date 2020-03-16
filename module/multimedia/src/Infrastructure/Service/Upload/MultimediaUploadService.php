<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Service\Upload;

use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Symfony\Component\HttpFoundation\File\File;

/**
 */
class MultimediaUploadService
{
    /**
     * @var string
     */
    private string $targetDirectory;

    /**
     * @param string $targetDirectory
     */
    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @param MultimediaId $id
     * @param File         $file
     *
     * @return File
     */
    public function upload(MultimediaId $id, File $file): File
    {
        $fileName = sprintf('%s.%s', $id->getValue(), $file->getExtension());

        return $file->move($this->targetDirectory, $fileName);
    }
}
