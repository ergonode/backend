<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Infrastructure\Factory\Command\Create;

use Ergonode\Attribute\Application\Model\Attribute\AttributeFormModel;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\Attribute\Domain\Command\Attribute\AbstractCreateAttributeCommand;

/**
 */
abstract class AbstractCreateAttributeCommandFactoryTest extends TestCase
{
    /**
     * @param string $class
     *
     * @return AttributeFormModel
     *
     * @throws \Exception
     */
    protected function getAttributeFormModel(string $class): AttributeFormModel
    {
        $code = 'code';
        $label = ['en' => 'label', 'pl_PL' => 'etykieta'];
        $hint = ['en' => 'hint', 'pl_PL' => 'podpowiedź'];
        $placeholder = ['en' => 'placeholder', 'pl_PL' => 'placeholder'];
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

    /**
     * @param AttributeFormModel             $model
     * @param AbstractCreateAttributeCommand $command
     */
    protected function assertAttributeFormModel(
        AttributeFormModel $model,
        AbstractCreateAttributeCommand $command
    ): void {
        $groups = [];
        foreach ($model->groups as $group) {
            $groups[] = new AttributeGroupId($group);
        }

        $this->assertSame($command->getCode()->getValue(), $model->code);
        $this->assertSame($command->getLabel()->getTranslations(), $model->label);
        $this->assertSame($command->getHint()->getTranslations(), $model->hint);
        $this->assertSame($command->getPlaceholder()->getTranslations(), $model->placeholder);
        $this->assertSame($command->getScope()->getValue(), $model->scope);
        $this->assertEquals($command->getGroups(), $groups);
    }
}
