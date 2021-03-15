<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Provider\Dictionary;

use Ergonode\Attribute\Domain\Provider\Dictionary\AttributeGroupDictionaryProvider;
use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AttributeGroupDictionaryProviderTest extends TestCase
{
    public function testProvidingAttributeGroupDictionary(): void
    {
        /** @var AttributeGroupQueryInterface | MockObject $attributeGroupQuery */
        $attributeGroupQuery = $this->createMock(AttributeGroupQueryInterface::class);
        $attributeGroupQuery->method('getAttributeGroups')->willReturn([['id' => 'id1', 'label' => 'label1']]);
        /** @var Language | MockObject $language */
        $language = $this->createMock(Language::class);

        $provider = new AttributeGroupDictionaryProvider($attributeGroupQuery);

        $this->assertSame(['id1' => 'label1'], $provider->getDictionary($language));
    }
}
