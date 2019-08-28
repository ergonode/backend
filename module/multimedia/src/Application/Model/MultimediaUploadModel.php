<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Application\Model;

use Ergonode\AttributeImage\Domain\ValueObject\ImageFormat;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable()
 */
class MultimediaUploadModel
{
    /**
     * @Assert\File(maxSize="100M")
     * @Vich\UploadableField(mapping="attachment", fileNameProperty="fileName", size="fileSize")
     *
     * @var UploadedFile
     */
    public $upload;

    /**
     * @Assert\Callback
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context): void
    {
        $isFileExtensionValid = \in_array(strtolower($this->upload->getClientOriginalExtension()), ImageFormat::AVAILABLE, true);

        if ($this->upload && !$isFileExtensionValid) {
            $context
                ->buildViolation('Not allowed file type')
                ->setTranslationDomain('file')
                ->atPath('upload')
                ->addViolation();
        }
    }
}
