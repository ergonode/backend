<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Application\Request\ParamConverter;

use Ergonode\Workflow\Application\Request\ParamConverter\AbstractWorkflowParamConverter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\MockObject\MockObject;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;

class AbstractWorkflowParamConverterTest extends TestCase
{
    /**
     * @var Request|MockObject
     */
    private Request $request;

    /**
     * @var ParamConverter|MockObject
     */
    private ParamConverter $configuration;

    /**
     * @var WorkflowProvider|MockObject
     */
    private WorkflowProvider $provider;

    protected function setUp(): void
    {
        $this->request = $this->createMock(Request::class);
        $this->configuration = $this->createMock(ParamConverter::class);
        $this->provider = $this->createMock(WorkflowProvider::class);
    }

    public function testSupportedClass(): void
    {
        $this->request->method('get')->willReturn(null);
        $this->configuration->method('getClass')->willReturn(AbstractWorkflow::class);

        $paramConverter = new AbstractWorkflowParamConverter($this->provider);
        self::assertTrue($paramConverter->supports($this->configuration));
    }

    public function testUnSupportedClass(): void
    {
        $this->request->method('get')->willReturn(null);
        $this->configuration->method('getClass')->willReturn('Any other class namespace');

        $paramConverter = new AbstractWorkflowParamConverter($this->provider);
        self::assertFalse($paramConverter->supports($this->configuration));
    }
}
