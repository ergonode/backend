<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Tests\Domain\Entity;

use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class TemplateTest extends TestCase
{
    /**
     * @var TemplateId|MockObject
     */
    private MockObject $id;

    /**
     * @var TemplateGroupId|MockObject
     */
    private MockObject $groupId;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var TemplateElement|MockObject
     */
    private MockObject $element;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(TemplateId::class);
        $this->groupId = $this->createMock(TemplateGroupId::class);
        $this->name = 'Any template name';
        $this->element = $this->createMock(TemplateElement::class);
        $this->element->method('getPosition')->willReturn(new Position(0, 0));
    }

    /**
     */
    public function testCreateTemplate(): void
    {
        $template = $this->getTemplate();

        self::assertEquals($this->id, $template->getId());
        self::assertEquals($this->groupId, $template->getGroupId());
        self::assertEquals($this->name, $template->getName());
        self::assertNull($template->getImageId());
    }

    /**
     */
    public function testAddedElementExists(): void
    {
        $template = $this->getTemplate();

        $template->addElement($this->element);
        self::assertTrue($template->hasElement($this->element->getPosition()));
        self::assertEquals($template->getElement($this->element->getPosition()), $this->element);
        self::assertEquals($template->getElements()->toArray(), [$this->element]);
        $template->removeElement($this->element->getPosition());
        self::assertFalse($template->hasElement($this->element->getPosition()));
    }

    /**
     */
    public function testAddExistsElement(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $template = $this->getTemplate();

        $template->addElement($this->element);
        $template->addElement($this->element);
    }

    /**
     */
    public function testChangeElement(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $template = $this->getTemplate();

        $template->changeElement($this->element);
    }

    /**
     */
    public function testTemplateEdit(): void
    {
        /** @var TemplateGroupId|MockObject $groupId */
        $groupId = $this->createMock(TemplateGroupId::class);
        $groupId->method('isEqual')->willReturn(false);
        $name = 'New Name';

        $template = $this->getTemplate();
        $template->changeGroup($groupId);
        $template->changeName($name);
        self::assertSame($groupId, $template->getGroupId());
        self::assertSame($name, $template->getName());
    }

    /**
     */
    public function testImageManipulation():void
    {
        /** @var MultimediaId|MockObject $imageId1 */
        $imageId1 = $this->createMock(MultimediaId::class);
        $imageId1->method('isEqual')->willReturn(false);
        /** @var MultimediaId|MockObject $imageId2 */
        $imageId2 = $this->createMock(MultimediaId::class);
        $imageId2->method('isEqual')->willReturn(false);

        $template = $this->getTemplate();
        self::assertNull($template->getImageId());
        $template->addImage($imageId1);
        self::assertEquals($imageId1, $template->getImageId());
        $template->changeImage($imageId2);
        self::assertEquals($imageId2, $template->getImageId());
        $template->removeImage();
        self::assertNull($template->getImageId());
    }

    /**
     */
    public function testAddedImageExists(): void
    {
        $template = $this->getTemplate();
        /** @var MultimediaId|MockObject $image */
        $image = $this->createMock(MultimediaId::class);

        $template->addImage($image);
        self::assertEquals($template->getImageId(), $image);
        $template->removeImage();
        self::assertEquals(null, $template->getImageId());
    }

    /**
     */
    public function testRemoveNotExistImage(): void
    {
        $this->expectException(\RuntimeException::class);
        $template = $this->getTemplate();
        /** @var MultimediaId|MockObject $image */
        $template->removeImage();
    }

    /**
     */
    public function testDefaultTextManipulation():void
    {
        /** @var AttributeId|MockObject $defaultText1 */
        $defaultText1 = $this->createMock(AttributeId::class);
        $defaultText1->method('isEqual')->willReturn(false);
        /** @var AttributeId|MockObject $defaultText2 */
        $defaultText2 = $this->createMock(AttributeId::class);
        $defaultText2->method('isEqual')->willReturn(false);

        $template = $this->getTemplate();
        self::assertNull($template->getDefaultLabel());
        $template->addDefaultLabel($defaultText1);
        self::assertEquals($defaultText1, $template->getDefaultLabel());
        $template->changeDefaultLabel($defaultText2);
        self::assertEquals($defaultText2, $template->getDefaultLabel());
        $template->removeDefaultLabel();
        self::assertNull($template->getDefaultLabel());
    }

    /**
     */
    public function testAddedDefaultTextExists(): void
    {
        $template = $this->getTemplate();
        /** @var AttributeId|MockObject $defaultText */
        $defaultText = $this->createMock(AttributeId::class);

        $template->addDefaultLabel($defaultText);
        self::assertEquals($template->getDefaultLabel(), $defaultText);
        $template->removeDefaultLabel();
        self::assertEquals(null, $template->getDefaultLabel());
    }

    /**
     */
    public function testRemoveNotExistDefaultText(): void
    {
        $this->expectException(\RuntimeException::class);
        $template = $this->getTemplate();
        /** @var AttributeId|MockObject $defaultText */
        $template->removeDefaultLabel();
    }

    /**
     */
    public function testDefaultImageManipulation():void
    {
        /** @var AttributeId|MockObject $defaultImage1 */
        $defaultImage1 = $this->createMock(AttributeId::class);
        $defaultImage1->method('isEqual')->willReturn(false);
        /** @var AttributeId|MockObject $defaultImage2 */
        $defaultImage2 = $this->createMock(AttributeId::class);
        $defaultImage2->method('isEqual')->willReturn(false);

        $template = $this->getTemplate();
        self::assertNull($template->getDefaultImage());
        $template->addDefaultImage($defaultImage1);
        self::assertEquals($defaultImage1, $template->getDefaultImage());
        $template->changeDefaultImage($defaultImage2);
        self::assertEquals($defaultImage2, $template->getDefaultImage());
        $template->removeDefaultImage();
        self::assertNull($template->getDefaultImage());
    }

    /**
     */
    public function testAddedDefaultImageExists(): void
    {
        $template = $this->getTemplate();
        /** @var AttributeId|MockObject $defaultImage */
        $defaultImage = $this->createMock(AttributeId::class);

        $template->addDefaultImage($defaultImage);
        self::assertEquals($template->getDefaultImage(), $defaultImage);
        $template->removeDefaultImage();
        self::assertEquals(null, $template->getDefaultImage());
    }

    /**
     */
    public function testRemoveNotExistDefaultImage(): void
    {
        $this->expectException(\RuntimeException::class);
        $template = $this->getTemplate();
        /** @var AttributeId|MockObject $defaultImage */
        $template->removeDefaultImage();
    }

    /**
     * @param MultimediaId|null $multimediaId
     *
     * @return Template
     */
    private function getTemplate(MultimediaId $multimediaId = null): Template
    {
        return new Template(
            $this->id,
            $this->groupId,
            $this->name,
            $multimediaId
        );
    }
}
