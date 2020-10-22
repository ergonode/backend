<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Application\Validator\Constaints;

use Ergonode\Account\Application\Validator\Constraints\ConstraintPrivilegeRelations;
use Ergonode\Account\Application\Validator\Constraints\ConstraintPrivilegeRelationsValidator;
use Ergonode\Account\Domain\Provider\PrivilegeGroupedByAreaProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class ConstraintPrivilegeRelationsValidatorTest extends TestCase
{
    /**
     * @param array $value
     * @param bool  $isValid
     *
     * @dataProvider validatorDataProvider
     */
    public function testValidator(array $value, bool $isValid): void
    {
        $provider = $this->createMock(PrivilegeGroupedByAreaProvider::class);
        $provider
            ->expects($this->once())
            ->method('provide')
            ->willReturn([
                'A' => [
                    'read' => 'AR',
                    'create' => 'AC',
                    'update' => 'AU',
                    'delete' => 'AD',
                ],
                'B' => [
                    'read' => 'BR',
                    'create' => 'BC',
                    'update' => 'BU',
                    'delete' => 'BD',
                ],
            ]);

        $constraintViolationBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $constraintViolationBuilder
            ->expects($this->atLeast(0))
            ->method('addViolation')
            ->willReturn(null);

        $expected = $isValid ? $this->exactly(0) : $this->once();
        $context = $this->createMock(ExecutionContextInterface::class);
        $context
            ->expects($expected)
            ->method('buildViolation')
            ->willReturn($constraintViolationBuilder);

        $validator = new ConstraintPrivilegeRelationsValidator($provider);
        $validator->initialize($context);
        $validator->validate($value, new ConstraintPrivilegeRelations());
    }

    /**
     * @return array
     */
    public function validatorDataProvider(): array
    {
        return [
            [['AC', 'AU', 'AD'], false],
            [['AR', 'AU', 'AD'], true],
            [['AR'], true],
            [['AR', 'AC', 'AU', 'AD', 'BC'], false],
            [['AR', 'AC', 'AU', 'AD', 'BC', 'BR'], true],
        ];
    }
}
