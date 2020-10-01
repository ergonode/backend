<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\Command\Option\CreateOptionCommand;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Attribute\Domain\Command\Option\UpdateOptionCommand;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

/**
 */
class OptionImportAction
{
    /**
     * @var AttributeQueryInterface
     */
    private AttributeQueryInterface $attributeQuery;

    /**
     * @var OptionQueryInterface
     */
    private OptionQueryInterface $optionQuery;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param AttributeQueryInterface $attributeQuery
     * @param OptionQueryInterface    $optionQuery
     * @param CommandBusInterface     $commandBus
     */
    public function __construct(
        AttributeQueryInterface $attributeQuery,
        OptionQueryInterface $optionQuery,
        CommandBusInterface $commandBus
    ) {
        $this->attributeQuery = $attributeQuery;
        $this->optionQuery = $optionQuery;
        $this->commandBus = $commandBus;
    }

    /**
     * @param AttributeCode      $code
     * @param OptionKey          $optionKey
     * @param TranslatableString $label
     *
     * @throws \Exception
     */
    public function action(AttributeCode $code, OptionKey $optionKey, TranslatableString $label): void
    {
        $attributeId = $this->attributeQuery->findAttributeIdByCode($code);
        Assert::notNull($attributeId);
        $optionId = $this->optionQuery->findIdByAttributeIdAndCode($attributeId, $optionKey);

        if (!$optionId) {
            $command = new CreateOptionCommand(
                $attributeId,
                $optionKey,
                $label
            );
        } else {
            $command = new UpdateOptionCommand(
                $optionId,
                $attributeId,
                $optionKey,
                $label
            );
        }

        $this->commandBus->dispatch($command, true);
    }
}
