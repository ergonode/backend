<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Domain\Command;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Domain\Command\UpdateShopware6ExportProfileCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class UpdateShopware6ExportProfileCommandTest extends TestCase
{
    /**
     * @var ExportProfileId|MockObject
     */
    private ExportProfileId $id;

    /**
     * @var string
     */
    private string $name;
    /**
     * @var string
     */
    private string $host;

    /**
     * @var string
     */
    private string $clientId;

    /**
     * @var string
     */
    private string $clientKey;

    /**
     * @var Language|MockObject
     */
    private Language $defaultLanguage;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(ExportProfileId::class);
        $this->name = 'Any Name';
        $this->host = 'http://example';
        $this->clientId = 'Any Client ID';
        $this->clientKey = 'Any Client KEY';
        $this->defaultLanguage = $this->createMock(Language::class);
    }

    /**
     */
    public function testCreateCommand(): void
    {
        $command = new UpdateShopware6ExportProfileCommand(
            $this->id,
            $this->name,
            $this->host,
            $this->clientId,
            $this->clientKey,
            $this->defaultLanguage
        );

        $this->assertEquals($this->id, $command->getId());
        $this->assertEquals($this->name, $command->getName());
        $this->assertEquals($this->host, $command->getHost());
        $this->assertEquals($this->clientId, $command->getClientId());
        $this->assertEquals($this->clientKey, $command->getClientKey());
        $this->assertEquals($this->defaultLanguage, $command->getDefaultLanguage());
    }
}
