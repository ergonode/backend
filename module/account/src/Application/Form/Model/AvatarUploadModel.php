<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Form\Model;

use Ergonode\Account\Application\Validator\Constraints\AvatarExtensionSupported;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable()
 */
class AvatarUploadModel
{
    /**
     * @Assert\File(maxSize="100M")
     *
     * @Vich\UploadableField(mapping="attachment", fileNameProperty="fileName", size="fileSize")
     *
     * @AvatarExtensionSupported()
     *
     * @var null|UploadedFile
     */
    public ?UploadedFile $upload;

    /**
     */
    public function __construct()
    {
        $this->upload = null;
    }
}
