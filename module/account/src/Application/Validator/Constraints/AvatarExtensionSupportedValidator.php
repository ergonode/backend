<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Validator\Constraints;

use Ergonode\Account\Infrastructure\Provider\AvatarExtensionProvider;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 */
class AvatarExtensionSupportedValidator extends ConstraintValidator
{
    /**
     * @var AvatarExtensionProvider
     */
    private AvatarExtensionProvider $provider;

    /**
     * @param AvatarExtensionProvider $provider
     */
    public function __construct(AvatarExtensionProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof AvatarExtensionSupported) {
            throw new UnexpectedTypeException($constraint, AvatarExtensionSupported::class);
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
