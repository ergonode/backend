<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Tests\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Entity\TemplateGroupId;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class TemplateTest extends TestCase
{
    /**
     * @var TemplateId|MockObject
     */
    private $id;

    /**
     * @var TemplateGroupId|MockObject
     */
    private $groupId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var TemplateElement|MockObject
     */
    private $element;

    /**
     */
    protected function setUp()
    {
        $this->id = $this->createMock(TemplateId::class);
        $this->groupId = $this->createMock(TemplateGroupId::class);
        $this->name = 'Any template name';
        $this->element = $this->createMock(TemplateElement::class);
        $this->element->method('getPosition')->willReturn(new Position(0,0));
    }

    /**
     *
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
     *
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
     * @expectedException \InvalidArgumentException
     */
    public function testAddExistsElement(): void
    {
        $template = $this->getTemplate();

        $template->addElement($this->element);
        $template->addElement($this->element);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testChangeElement(): void
    {
        $template = $this->getTemplate();

        $template->changeElement($this->element);
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
     * @expectedException \RuntimeException
     */
    public function testRemoveNotExistImage(): void
    {
        $template = $this->getTemplate();
        /** @var MultimediaId|MockObject $image */
        $template->removeImage();
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
