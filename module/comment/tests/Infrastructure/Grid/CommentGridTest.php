<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Tests\Infrastructure\Grid;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Comment\Infrastructure\Grid\CommentGrid;
use Ergonode\Core\Application\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\GridConfigurationInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class CommentGridTest extends TestCase
{
    /**
     */
    public function testGridInit(): void
    {
        /** @var GridConfigurationInterface $configuration */
        $configuration = $this->createMock(GridConfigurationInterface::class);
        /** @var Language $language */
        $language = $this->createMock(Language::class);
        /** @var AuthenticatedUserProviderInterface|MockObject $provider */
        $provider = $this->createMock(AuthenticatedUserProviderInterface::class);
        $provider->expects($this->once())->method('provide')->willReturn($this->createMock(User::class));
        $grid = new CommentGrid($provider);
        $grid->init($configuration, $language);
    }
}
