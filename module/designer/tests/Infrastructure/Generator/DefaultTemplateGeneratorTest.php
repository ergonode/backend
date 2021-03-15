<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Tests\Infrastructure\Generator;

use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Designer\Infrastructure\Generator\DefaultTemplateGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DefaultTemplateGeneratorTest extends TestCase
{
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
