<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Entity;

use Ergonode\Workflow\Domain\Entity\Workflow;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;

/**
 */
class WorkflowTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testType(): void
    {
        $workflow = new Workflow(WorkflowId::generate(), 'code', []);
        $this->assertSame(Workflow::TYPE, $workflow->getType());
    }

    /**
     */
    public function testShouldSortTransitionStatuses(): void
    {
        $workflow = new Workflow(
            $this->id,
            '1',
            [
                new StatusCode('1'),
                new StatusCode('2'),
                new StatusCode('3'),
                new StatusCode('4'),
                new StatusCode('5'),
                new StatusCode('6'),
                new StatusCode('7'),
                new StatusCode('8'),
            ],
        );
        $workflow->addTransition(new StatusCode('1'), new StatusCode('2'));
        $workflow->addTransition(new StatusCode('7'), new StatusCode('8'));
        $workflow->addTransition(new StatusCode('4'), new StatusCode('1'));
        $workflow->addTransition(new StatusCode('3'), new StatusCode('4'));
        $workflow->addTransition(new StatusCode('2'), new StatusCode('3'));

        $sorted = $workflow->getSortedTransitionStatuses();

        $this->assertEquals(
            [
                new StatusCode('1'),
                new StatusCode('2'),
                new StatusCode('3'),
                new StatusCode('4'),
            ],
            $sorted,
        );
    }
}
