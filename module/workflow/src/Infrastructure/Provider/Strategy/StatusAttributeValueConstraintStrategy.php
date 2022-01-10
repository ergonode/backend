<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Attribute\Infrastructure\Provider\ContextAwareAttributeValueConstraintStrategyInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Workflow\Application\Validator\StatusAvailable;
use Ergonode\Workflow\Application\Validator\StatusExists;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class StatusAttributeValueConstraintStrategy implements ContextAwareAttributeValueConstraintStrategyInterface
{
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof StatusSystemAttribute;
    }

    /**
     * @param AbstractAttribute|PriceAttribute $attribute
     */
    public function get(
        AbstractAttribute $attribute,
        ?AggregateId $aggregateId = null,
        ?Language $language = null
    ): Constraint {
        return new Collection([
            'value' => [
                new NotBlank(['message' => 'Status Must be set']),
                new Length(['max' => 255]),
                new StatusExists(),
                new StatusAvailable(['aggregateId' => $aggregateId, 'language' => $language]),
            ],
        ]);
    }
}
