<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Tests\Infrastructure\Strategy\Relationship;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Designer\Infrastructure\Strategy\Relationship\TemplateAttributeRelationshipStrategy;
use PHPUnit\Framework\TestCase;

/**
 */
class TemplateAttributeRelationshipStrategyTest extends TestCase
{

    /**
     * @var TemplateQueryInterface
     */
    private $query;

    /**
     */
    protected function setUp()
    {
        $this->query = $this->createMock(TemplateQueryInterface::class);
        parent::setUp();
    }

    /**
     */
    public function testIsSupported(): void
    {
        $templateAttributeRelationshipStrategy = new TemplateAttributeRelationshipStrategy($this->query);
        $attributeId = $this->createMock(AttributeId::class);
        $this->assertTrue($templateAttributeRelationshipStrategy->supports($attributeId));
    }

    /**
     */
    public function testIsNotSupported(): void
    {
        $templateAttributeRelationshipStrategy = new TemplateAttributeRelationshipStrategy($this->query);
        $roleId = $this->createMock(RoleId::class);
        $this->assertFalse($templateAttributeRelationshipStrategy->supports($roleId));
    }

    /**
     */
    public function testRelationships(): void
    {
        $templateAttributeRelationshipStrategy = new TemplateAttributeRelationshipStrategy($this->query);
        $attributeId = $this->createMock(AttributeId::class);
        $this->assertIsArray($templateAttributeRelationshipStrategy->getRelationships($attributeId));
    }
}
