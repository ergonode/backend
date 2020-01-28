<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

namespace Ergonode\Exporter\Tests\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Exporter\Domain\Entity\Category;
use PHPUnit\Framework\TestCase;

/**
 */
class CategoryTest extends TestCase
{
    /**
     * @var string
     */
    private string $id;

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
    protected function setUp()
    {
        $this->id = 'test';
        $this->code = 'test';
        $this->name = new TranslatableString([]);
    }

    /**
     */
    public function testConstructor():void
    {
        $category = new Category(
            $this->id,
            $this->code,
            $this->name
        );

        $this->assertEquals($this->id, $category->getId());
        $this->assertEquals($this->code, $category->getCode());
        $this->assertEquals($this->name, $category->getName());
    }
}
