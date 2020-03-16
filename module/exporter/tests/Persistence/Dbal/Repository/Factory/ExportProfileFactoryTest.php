<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Tests\Persistence\Dbal\Repository\Factory;

use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use Ergonode\Exporter\Persistence\Dbal\Repository\Factory\ExportProfileFactory;
use PHPUnit\Framework\TestCase;

/**
 */
class ExportProfileFactoryTest extends TestCase
{
    /**
     * @var AbstractExportProfile
     */
    private AbstractExportProfile $testedClass;

    /**
     * @var string
     */
    private string $name;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->name = 'any name';

        $this->testedClass = new class() extends AbstractExportProfile {
            /**
             */
            public function __construct()
            {
                parent::__construct(ExportProfileId::generate(), '');
            }

            /**
             * @return string
             */
            public function getType(): string
            {
                return 'test';
            }
        };
    }

    /**
     */
    public function testCreate() :void
    {
        $object = new ExportProfileFactory();
        $id = ExportProfileId::generate();
        $exportProfile = $object->create(
            [
                'id' => $id->getValue(),
                'type' => get_class($this->testedClass),
                'name' => $this->name,
                'configuration' => '{}',
            ]
        );

        $this->assertTrue($id->isEqual($exportProfile->getId()));
        $this->assertSame($exportProfile->getName(), $this->name);
        $this->assertSame($exportProfile->getConfiguration(), []);
    }

    /**
     */
    public function testCreateException() :void
    {
        $this->expectException(\ReflectionException::class);
        $object = new ExportProfileFactory();
        $exportProfile = $object->create(
            [
                'id' => ExportProfileId::generate()->getValue(),
                'type' => \stdClass::class,
                'name' => $this->name,
                'configuration' => '{}',
            ]
        );
    }
}
