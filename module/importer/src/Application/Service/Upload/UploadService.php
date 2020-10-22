<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Service\Upload;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadService implements UploadServiceInterface
{
    private string $targetDirectory;

    /**
     * @param string $targetDirectory
     */
    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @param UploadedFile $file
     *
     * @return File
     */
    public function upload(UploadedFile $file): File
    {
        $fileName = sprintf('%s.%s', md5(uniqid($file->getClientOriginalName(), true)), $file->guessClientExtension());

        return $file->move($this->targetDirectory, $fileName);
    }
}
