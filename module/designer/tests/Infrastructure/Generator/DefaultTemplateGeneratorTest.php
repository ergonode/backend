<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Designer\Tests\Infrastructure\Generator;

use Ergonode\Designer\Domain\Entity\TemplateGroupId;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Designer\Infrastructure\Generator\DefaultTemplateGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class DefaultTemplateGeneratorTest extends TestCase
{
    /**
     */
    public function testTemplateGeneration(): void
    {
        /** @var TemplateId|MockObject $templateId */
        $templateId = $this->createMock(TemplateId::class);
        /** @var TemplateGroupId|MockObject $groupId */
        $groupId = $this->createMock(TemplateGroupId::class);

        $generator = new DefaultTemplateGenerator();
        $result = $generator->getTemplate($templateId, $groupId);
        $this->assertEquals($templateId, $result->getId());
        $this->assertEquals($groupId, $result->getGroupId());
    }
}
