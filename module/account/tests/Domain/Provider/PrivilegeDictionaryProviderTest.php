<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Provider;

use Ergonode\Account\Domain\Provider\PrivilegeDictionaryProvider;
use Ergonode\Account\Domain\Query\PrivilegeQueryInterface;
use Ergonode\Account\Domain\ValueObject\Privilege;
use Ergonode\Account\Infrastructure\Resolver\PrivilegeTypeResolverInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class PrivilegeDictionaryProviderTest extends TestCase
{
    public function testProvidingPrivilegeDictionary(): void
    {
        /** @var PrivilegeQueryInterface | MockObject $query */
        $query = $this->createMock(PrivilegeQueryInterface::class);
        $query->method('getPrivileges')->willReturn(
            [
                [
                    'id' => 'id1',
                    'code' => 'code1',
                    'area' => 'area1',
                    'description' => 'description1',
                ],
            ]
        );

        /** @var TranslatorInterface | MockObject $translator */
        $translator = $this->createMock(TranslatorInterface::class);
        $translator->expects($this->at(0))->method('trans')->willReturn('area_translation1');
        $translator->expects($this->at(1))->method('trans')->willReturn('description_translation1');

        /** @var PrivilegeTypeResolverInterface | MockObject $resolver */
        $resolver = $this->createMock(PrivilegeTypeResolverInterface::class);
        $resolver->method('resolve')->willReturn('type');

        $provider = new PrivilegeDictionaryProvider($query, $translator, $resolver);

        /** @var Language | MockObject $language */
        $language = $this->createMock(Language::class);
        $result = $provider->provide($language);
        $this->assertSame('area_translation1', $result[0]['name']);
        $this->assertSame('description_translation1', $result[0]['description']);
        $this->assertEquals(new Privilege('code1'), $result[0]['privileges']['type']);
    }
}
