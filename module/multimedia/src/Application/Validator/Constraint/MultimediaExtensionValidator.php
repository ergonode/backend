<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Application\Validator\Constraint;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Ergonode\Multimedia\Infrastructure\Provider\MultimediaExtensionProvider;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MultimediaExtensionValidator extends ConstraintValidator
{
    /**
     * @var MultimediaExtensionProvider
     */
    private MultimediaExtensionProvider $provider;

    /**
     * @param MultimediaExtensionProvider $provider
     */
    public function __construct(MultimediaExtensionProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof MultimediaExtension) {
            throw new UnexpectedTypeException($constraint, MultimediaExists::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof UploadedFile) {
            throw new UnexpectedTypeException($value, UploadedFile::class);
        }

        $isFileExtensionValid = \in_array(
            strtolower($value->getClientOriginalExtension()),
            $this->provider->dictionary(),
            true
        );

        if (!$isFileExtensionValid) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value->getClientOriginalExtension())
                ->addViolation();
        }
    }
}
