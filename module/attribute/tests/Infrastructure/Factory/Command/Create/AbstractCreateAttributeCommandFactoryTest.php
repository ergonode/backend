<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Factory\Command\Create;

use Ergonode\Attribute\Application\Model\Attribute\AttributeFormModel;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\Attribute\Domain\Command\Attribute\AbstractCreateAttributeCommand;

abstract class AbstractCreateAttributeCommandFactoryTest extends TestCase
{
    /**
     * @throws \Exception
     */
    protected function getAttributeFormModel(string $class): AttributeFormModel
    {
        $code = 'code';
        $label = ['en_GB' => 'label', 'pl_PL' => 'etykieta'];
        $hint = ['en_GB' => 'hint', 'pl_PL' => 'podpowiedź'];
        $placeholder = ['en_GB' => 'placeholder', 'pl_PL' => 'placeholder'];
        $scope = 'local';
        $group = Uuid::uuid4()->toString();

        $data = $this->createMock(AttributeFormModel::class);
        $data->code = $code;
        $data->label = $label;
        $data->hint = $hint;
        $data->placeholder = $placeholder;
        $data->scope = $scope;
        $data->groups = [$group];

        return $data;
    }

    protected function assertAttributeFormModel(
        AttributeFormModel $model,
        AbstractCreateAttributeCommand $command
    ): void {
        $groups = [];
        foreach ($model->groups as $group) {
            $groups[] = new AttributeGroupId($group);
        }

        self::assertSame($command->getCode()->getValue(), $model->code);
        self::assertSame($command->getLabel()->getTranslations(), $model->label);
        self::assertSame($command->getHint()->getTranslations(), $model->hint);
        self::assertSame($command->getPlaceholder()->getTranslations(), $model->placeholder);
        self::assertSame($command->getScope()->getValue(), $model->scope);
        self::assertEquals($command->getGroups(), $groups);
    }
}
