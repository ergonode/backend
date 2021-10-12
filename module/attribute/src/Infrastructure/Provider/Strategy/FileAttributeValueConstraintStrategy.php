<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Infrastructure\Provider\AttributeValueConstraintStrategyInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Ergonode\Attribute\Domain\Entity\Attribute\FileAttribute;
use Symfony\Component\Validator\Constraints\All;
use Ergonode\Multimedia\Application\Validator\MultimediaExists;

class FileAttributeValueConstraintStrategy implements AttributeValueConstraintStrategyInterface
{
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof FileAttribute;
    }

    public function get(AbstractAttribute $attribute): Constraint
    {
        return new Collection([
            'value' => [
                new All([
                        new MultimediaExists(),
                ]),
            ],
        ]);
    }
}
