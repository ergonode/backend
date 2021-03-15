<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Infrastructure\Provider\AttributeValueConstraintStrategyInterface;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Ergonode\Attribute\Domain\Entity\Attribute\FileAttribute;

class FileAttributeValueConstraintStrategy implements AttributeValueConstraintStrategyInterface
{
    private MultimediaQueryInterface $query;

    public function __construct(MultimediaQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof FileAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function get(AbstractAttribute $attribute): Constraint
    {
        $multimedia = $this->query->getAll();

        return new Collection([
            'value' => [
                new Choice(['choices' => $multimedia, 'multiple' => true]),
            ],
        ]);
    }
}
