<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Handler;

use Ergonode\Attribute\Domain\Command\AddAttributeOptionCommand;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractOptionAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;

/**
 */
class AddAttributeOptionCommandHandler
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @var AttributeQueryInterface
     */
    private AttributeQueryInterface $query;

    /**
     * @param AttributeRepositoryInterface $repository
     * @param AttributeQueryInterface      $query
     */
    public function __construct(AttributeRepositoryInterface $repository, AttributeQueryInterface $query)
    {
        $this->repository = $repository;
        $this->query = $query;
    }

    /**
     * @param AddAttributeOptionCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(AddAttributeOptionCommand $command): void
    {
        $key = $command->getOptionKey();
        $attributeId = $command->getAttributeId();
        $attributeType = $this->query->findAttributeType($command->getAttributeId());

        if ($attributeType &&
            in_array($attributeType->getValue(), [SelectAttribute::TYPE, MultiSelectAttribute::TYPE], true)
        ) {
            $oldOption = $this->query->findAttributeOption($command->getAttributeId(), $key);
            $newOption = $command->getOption();

            if (!$oldOption || !$oldOption->equal($newOption)) {
                $attribute = $this->repository->load($attributeId);
                if ($attribute instanceof AbstractOptionAttribute) {
                    if ($attribute->hasOption($key)) {
                        $attribute->changeOption($key, $newOption);
                    } else {
                        $attribute->addOption($key, $newOption);
                    }
                    $this->repository->save($attribute);
                }
            }
        }
    }
}
