<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Model\Form;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable()
 */
class UploadModel
{
    private const FILE_EXTENSIONS = [
        'csv',
    ];

    /**
     * @Assert\File(maxSize="500M")
     * @Vich\UploadableField(mapping="attachment", fileNameProperty="fileName", size="fileSize")
     *
     * @var UploadedFile
     */
    public $upload;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public $transformer;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public $reader;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public $action;

    /**
     * @Assert\Callback
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context): void
    {
        $isFileExtensionValid = \in_array($this->upload->getClientOriginalExtension(), self::FILE_EXTENSIONS, true);

        if ($this->upload && !$isFileExtensionValid) {
            $context
                ->buildViolation(sprintf('%s not allowed file type', $this->upload->getClientOriginalExtension()))
                ->setTranslationDomain('file')
                ->atPath('upload')
                ->addViolation();
        }
    }
}
