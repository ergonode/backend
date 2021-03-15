<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Comment\Tests\Infrastructure\Grid;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Comment\Infrastructure\Grid\CommentGridBuilder;
use Ergonode\Account\Infrastructure\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\GridConfigurationInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CommentGridBuilderTest extends TestCase
{
    public function testGridInit(): void
    {
        /** @var GridConfigurationInterface $configuration */
        $configuration = $this->createMock(GridConfigurationInterface::class);
        /** @var Language $language */
        $language = $this->createMock(Language::class);
        /** @var AuthenticatedUserProviderInterface|MockObject $provider */
        $provider = $this->createMock(AuthenticatedUserProviderInterface::class);
        $provider->expects($this->once())->method('provide')->willReturn($this->createMock(User::class));
        $builder = new CommentGridBuilder($provider);
        $builder->build($configuration, $language);
    }
}
