<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

namespace Ergonode\Exporter\Tests\Domain\Entity\Catalog;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Exporter\Domain\Entity\Catalog\ExportCategory;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 */
class CategoryTest extends TestCase
{
    /**
     * @var Uuid
     */
    private Uuid $id;

    /**
     * @var string
     */
    private string $code;

    /**
     * @var TranslatableString|string
     */
    private TranslatableString $name;

    /**
     */
    protected function setUp(): void
    {
        $this->code = 'CODE';
        $this->id = Uuid::uuid4();
        $this->name = new TranslatableString(['en_GB' => 'english', 'pl_PL' => 'polish']);
    }

    /**
     */
    public function testConstructor(): void
    {
        $category = new ExportCategory(
            $this->id,
            $this->code,
            $this->name
        );

        self::assertEquals($this->id, $category->getId());
        self::assertEquals($this->code, $category->getCode());
        self::assertEquals($this->name, $category->getName());
    }
}
