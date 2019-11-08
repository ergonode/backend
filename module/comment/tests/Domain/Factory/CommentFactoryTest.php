<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Tests\Domain\Factory;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Comment\Domain\Entity\CommentId;
use Ergonode\Comment\Domain\Factory\CommentFactory;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 */
class CommentFactoryTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testFactoryCreation(): void
    {
        /** @var CommentId $CommentId */
        $CommentId = $this->createMock(CommentId::class);
        /** @var UserId $userId */
        $userId = $this->createMock(UserId::class);
        /** @var Uuid $objectId */
        $objectId = $this->createMock(Uuid::class);
        $content = 'Any content';

        $factory = new CommentFactory();

        $Comment = $factory->create($CommentId, $userId, $objectId, $content);
        $this->assertSame($CommentId, $Comment->getId());
        $this->assertSame($userId, $Comment->getAuthorId());
        $this->assertSame($objectId, $Comment->getObjectId());
        $this->assertSame($content, $Comment->getContent());
    }
}
