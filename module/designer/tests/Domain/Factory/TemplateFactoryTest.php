<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Tests\Domain\Factory;

use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\Designer\Domain\Entity\TemplateGroupId;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Designer\Domain\Factory\TemplateFactory;
use Ergonode\Designer\Domain\ValueObject\Position;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class TemplateFactoryTest extends TestCase
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
     */
    public function testFactoryCreateTemplate(): void
    {
        $factory = new TemplateFactory();
        $template = $factory->create($this->id, $this->groupId, $this->name, [$this->element]);

        $this->assertEquals($this->id, $template->getId());
        $this->assertEquals($this->groupId, $template->getGroupId());
        $this->assertEquals($this->name, $template->getName());
        $this->assertCount(1, $template->getElements());
        $this->assertContainsOnlyInstancesOf(TemplateElement::class, $template->getElements());
    }
}
