<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributeImage\Domain\Validator;

use Ergonode\Attribute\Domain\AttributeValidatorInterface;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\AttributeImage\Domain\Entity\ImageAttribute;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;

/**
 */
class ImageAttributeValidator implements AttributeValidatorInterface
{
    /**
     * @var MultimediaRepositoryInterface
     */
    private $repository;

    /**
     * @param MultimediaRepositoryInterface $repository
     */
    public function __construct(MultimediaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param AbstractAttribute|ImageAttribute $attribute
     * @param mixed                            $value
     *
     * @return bool
     *
     * @throws \ReflectionException
     */
    public function isValid(AbstractAttribute $attribute, $value): bool
    {
        if (!MultimediaId::isValid($value)) {
            return false;
        }

        $multimedia = $this->repository->load(new MultimediaId($value));

        if (null === $multimedia) {
            return false;
        }

        if (\in_array($multimedia->getExtension(), $attribute->getFormats()->toArray(), true)) {
            return false;
        }

        return true;
    }

    /**
     * @param AbstractAttribute $attribute
     *
     * @return bool
     */
    public function isSupported(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof ImageAttribute;
    }
}
