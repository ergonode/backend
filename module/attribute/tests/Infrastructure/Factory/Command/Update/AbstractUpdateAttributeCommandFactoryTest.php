<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Infrastructure\Factory\Command\Update;

use Ergonode\Attribute\Application\Model\Attribute\AttributeFormModel;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\Attribute\Domain\Command\Attribute\AbstractUpdateAttributeCommand;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

/**
 */
abstract class AbstractUpdateAttributeCommandFactoryTest extends TestCase
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
     * @param AttributeId                    $id
     * @param AttributeFormModel             $model
     * @param AbstractUpdateAttributeCommand $command
     */
    protected function assertAttributeFormModel(
        AttributeId $id,
        AttributeFormModel $model,
        AbstractUpdateAttributeCommand $command
    ): void {
        $groups = [];
        foreach ($model->groups as $group) {
            $groups[] = new AttributeGroupId($group);
        }

        $this->assertSame($command->getId(), $id);
        $this->assertSame($command->getLabel()->getTranslations(), $model->label);
        $this->assertSame($command->getHint()->getTranslations(), $model->hint);
        $this->assertSame($command->getPlaceholder()->getTranslations(), $model->placeholder);
        $this->assertSame($command->getScope()->getValue(), $model->scope);
        $this->assertEquals($command->getGroups(), $groups);
    }
}
