<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Handler\Attribute;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Command\Attribute\AbstractUpdateAttributeCommand;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;

/**
 */
abstract class AbstractUpdateAttributeCommandHandler
{
    /**
     * @param AbstractUpdateAttributeCommand $command
     * @param AbstractAttribute              $attribute
     *
     * @return AbstractAttribute
     *
     * @throws \Exception
     */
    public function update(AbstractUpdateAttributeCommand $command, AbstractAttribute $attribute): AbstractAttribute
    {
        $attribute->changeLabel($command->getLabel());
        $attribute->changeHint($command->getHint());
        $attribute->changePlaceholder($command->getPlaceholder());

        foreach ($command->getGroups() as $group) {
            $groupId = new AttributeGroupId($group);
            if (!$attribute->inGroup($groupId)) {
                $attribute->addGroup($groupId);
            }
        }

        foreach ($attribute->getGroups() as $groupId) {
            if (!\in_array($groupId->getValue(), $command->getGroups(), true)) {
                $attribute->removeGroup($groupId);
            }
        }

        return $attribute;
    }
}
