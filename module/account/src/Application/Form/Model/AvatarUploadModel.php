<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Form\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable()
 */
class AvatarUploadModel
{
    /**
     * @Assert\File(
     *     maxSize="5M",
     *     mimeTypes={"image/png", "image/jpg", "image/jpeg", "image/gif"},
     *     mimeTypesMessage="Upload valid image format (png, gif, jpg, jpeg)"
     * )
     *
     * @Vich\UploadableField(mapping="attachment", fileNameProperty="fileName", size="fileSize")
     */
    public ?UploadedFile $upload;

    public function __construct()
    {
        $this->upload = null;
    }
}
