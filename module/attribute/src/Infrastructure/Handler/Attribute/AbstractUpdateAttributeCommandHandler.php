<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Handler\Attribute;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Command\Attribute\AbstractUpdateAttributeCommand;

abstract class AbstractUpdateAttributeCommandHandler
{
    /**
     * @throws \Exception
     */
    public function update(AbstractUpdateAttributeCommand $command, AbstractAttribute $attribute): AbstractAttribute
    {
        $attribute->changeLabel($command->getLabel());
        $attribute->changeHint($command->getHint());
        $attribute->changePlaceholder($command->getPlaceholder());
        $attribute->changeScope($command->getScope());

        foreach ($command->getGroups() as $groupId) {
            if (!$attribute->inGroup($groupId)) {
                $attribute->addGroup($groupId);
            }
        }

        foreach ($attribute->getGroups() as $groupId) {
            if (!$command->hasGroup($groupId)) {
                $attribute->removeGroup($groupId);
            }
        }

        return $attribute;
    }
}
