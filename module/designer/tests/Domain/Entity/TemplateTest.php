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

        $this->assertEquals($this->id, $template->getId());
        $this->assertEquals($this->groupId, $template->getGroupId());
        $this->assertEquals($this->name, $template->getName());
        $this->assertNull($template->getImageId());
    }

    /**
     */
    public function testAddedElementExists(): void
    {
        $template = $this->getTemplate();

        $template->addElement($this->element);
        $this->assertTrue($template->hasElement($this->element->getPosition()));
        $this->assertEquals($template->getElement($this->element->getPosition()), $this->element);
        $this->assertEquals($template->getElements()->toArray(), [$this->element]);
        $template->removeElement($this->element->getPosition());
        $this->assertFalse($template->hasElement($this->element->getPosition()));
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
        $this->assertSame($groupId, $template->getGroupId());
        $this->assertSame($name, $template->getName());
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
        $this->assertNull($template->getImageId());
        $template->addImage($imageId1);
        $this->assertEquals($imageId1, $template->getImageId());
        $template->changeImage($imageId2);
        $this->assertEquals($imageId2, $template->getImageId());
        $template->removeImage();
        $this->assertNull($template->getImageId());
    }

    /**
     */
    public function testAddedImageExists(): void
    {
        $template = $this->getTemplate();
        /** @var MultimediaId|MockObject $image */
        $image = $this->createMock(MultimediaId::class);

        $template->addImage($image);
        $this->assertEquals($template->getImageId(), $image);
        $template->removeImage();
        $this->assertEquals(null, $template->getImageId());
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
        $this->assertNull($template->getDefaultText());
        $template->addDefaultText($defaultText1);
        $this->assertEquals($defaultText1, $template->getDefaultText());
        $template->changeDefaultText($defaultText2);
        $this->assertEquals($defaultText2, $template->getDefaultText());
        $template->removeDefaultText();
        $this->assertNull($template->getDefaultText());
    }

    /**
     */
    public function testAddedDefaultTextExists(): void
    {
        $template = $this->getTemplate();
        /** @var AttributeId|MockObject $defaultText */
        $defaultText = $this->createMock(AttributeId::class);

        $template->addDefaultText($defaultText);
        $this->assertEquals($template->getDefaultText(), $defaultText);
        $template->removeDefaultText();
        $this->assertEquals(null, $template->getDefaultText());
    }

    /**
     */
    public function testRemoveNotExistDefaultText(): void
    {
        $this->expectException(\RuntimeException::class);
        $template = $this->getTemplate();
        /** @var AttributeId|MockObject $defaultText */
        $template->removeDefaultText();
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
        $this->assertNull($template->getDefaultImage());
        $template->addDefaultImage($defaultImage1);
        $this->assertEquals($defaultImage1, $template->getDefaultImage());
        $template->changeDefaultImage($defaultImage2);
        $this->assertEquals($defaultImage2, $template->getDefaultImage());
        $template->removeDefaultImage();
        $this->assertNull($template->getDefaultImage());
    }

    /**
     */
    public function testAddedDefaultImageExists(): void
    {
        $template = $this->getTemplate();
        /** @var AttributeId|MockObject $defaultImage */
        $defaultImage = $this->createMock(AttributeId::class);

        $template->addDefaultImage($defaultImage);
        $this->assertEquals($template->getDefaultImage(), $defaultImage);
        $template->removeDefaultImage();
        $this->assertEquals(null, $template->getDefaultImage());
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
