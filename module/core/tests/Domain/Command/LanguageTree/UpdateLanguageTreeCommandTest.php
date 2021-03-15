<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Domain\Command\LanguageTree;

use Ergonode\Core\Application\Model\LanguageTree\LanguageTreeNodeFormModel;
use Ergonode\Core\Domain\Command\LanguageTree\UpdateLanguageTreeCommand;
use PHPUnit\Framework\TestCase;

class UpdateLanguageTreeCommandTest extends TestCase
{
    public function testCommand(): void
    {
        $language1 = $this->createMock(LanguageTreeNodeFormModel::class);
        $language1->languageId = 'f76bc354-5bfa-47da-b32b-38fd9fa1c775';
        $language2 = $this->createMock(LanguageTreeNodeFormModel::class);
        $language2->languageId = '5f9a0ad9-da4d-48a8-ab7c-a27d99c223d4';
        $language2->children = [];
        $language1->children = [$language2];
        $command = new UpdateLanguageTreeCommand($language1);

        $this->assertEquals($command->getLanguages()->getLanguageId(), $language1->languageId);
    }
}
