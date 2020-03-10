<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Tests\Infrastructure\Strategy\Relationship;

use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Designer\Infrastructure\Strategy\Relationship\TemplateAttributeRelationshipStrategy;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\AggregateId;
use PHPUnit\Framework\TestCase;

/**
 */
class TemplateAttributeRelationshipStrategyTest extends TestCase
{

    /**
     * @var TemplateQueryInterface
     */
    private TemplateQueryInterface $query;

    /**
     */
    protected function setUp(): void
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
        $roleId = $this->createMock(AggregateId::class);
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
