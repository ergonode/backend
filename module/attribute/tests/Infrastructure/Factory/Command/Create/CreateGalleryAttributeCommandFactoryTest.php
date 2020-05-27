<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Infrastructure\Factory\Command\Create;

use Ergonode\Attribute\Application\Model\Attribute\AttributeFormModel;
use Ergonode\Attribute\Domain\Command\Attribute\Create\CreateGalleryAttributeCommand;
use Ergonode\Attribute\Infrastructure\Factory\Command\Create\CreateGalleryAttributeCommandFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;

/**
 */
class CreateGalleryAttributeCommandFactoryTest extends TestCase
{
    /**
     */
    public function testCreation()
    {
        $code = 'code';
        $label = ['en' => 'label', 'pl_PL' => 'etykieta'];
        $hint = ['en' => 'hint', 'pl_PL' => 'podpowiedź'];
        $placeholder = ['en' => 'placeholder', 'pl_PL' => 'placeholder'];
        $scope = 'local';
        $groups = ['groups'];

        $form = $this->createMock(FormInterface::class);
        $data = $this->getMockBuilder(AttributeFormModel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $data->code = $code;
        $data->label = $label;
        $data->hint = $hint;
        $data->placeholder = $placeholder;
        $data->scope = $scope;
        $data->groups = $groups;
        $form->method('getData')->willReturn($data);

        $commandFactory = new CreateGalleryAttributeCommandFactory();

        /** @var CreateGalleryAttributeCommand $result */
        $result = $commandFactory->create($form);

        $this->assertSame($result->getCode()->getValue(), $code);
        $this->assertSame($result->getLabel()->getTranslations(), $label);
        $this->assertSame($result->getHint()->getTranslations(), $hint);
        $this->assertSame($result->getPlaceholder()->getTranslations(), $placeholder);
        $this->assertSame($result->getScope()->getValue(), $scope);
        $this->assertSame($result->getGroups(), $groups);
    }
}
